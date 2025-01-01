<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PenerimaanModel;
use App\Models\ProsesModel\PengirisanModel;
use App\Models\HpaModel;

use App\Models\MutuModel;
use Exception;

class Penerimaan extends BaseController
{
    // Memuat model di constructor
    protected $penerimaanModel;

    public function __construct()
    {
        $this->penerimaanModel = new PenerimaanModel();
    }
    public function index_penerimaan()
    {
        // Mengambil id_user dan nama_user dari session
        $penerimaanModel = new PenerimaanModel();

        // Mengambil data HPA beserta relasinya
        $penerimaanData['penerimaanData'] = $penerimaanModel->getPenerimaanWithRelations();

        // Menggabungkan data dari model dan session
        $data = [
            'penerimaanData' => $penerimaanData['penerimaanData'],
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        // Mengirim data ke view untuk ditampilkan
        return view('proses/penerimaan', $data);
    }
    public function proses_penerimaan()
    {
        // Get user ID from session
        $id_user = session()->get('id_user');

        // Form validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_proses' => [
                'rules' => 'required',
                'errors' => ['required' => 'Pilih data terlebih dahulu.'],
            ],
            'action' => [
                'rules' => 'required',
                'errors' => ['required' => 'Tombol aksi harus diklik.'],
            ],
        ]);

        // Check validation
        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            // Get the selected IDs and action
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');

            // Check if selected IDs are provided
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            // Process the action for each selected item
            if (!empty($selectedIds)) {
                foreach ($selectedIds as $id) {
                    list($id_penerimaan, $id_hpa, $id_mutu) = explode(':', $id);
                    $indikator_1 = (string) ($this->request->getPost('indikator_1') ?? '0');
                    $indikator_2 = (string) ($this->request->getPost('indikator_2') ?? '0');
                    $total_nilai_mutu = $this->request->getPost('total_nilai_mutu');
                    $this->processAction($action, $id_penerimaan, $id_hpa, $id_user, $id_mutu, $indikator_1, $indikator_2, $total_nilai_mutu);
                }

                return redirect()->to('/penerimaan/index_penerimaan');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_penerimaan, $id_hpa, $id_user, $id_mutu, $indikator_1, $indikator_2, $total_nilai_mutu)
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $penerimaanModel = new PenerimaanModel();
        $pengirisanModel = new PengirisanModel();
        $mutuModel = new MutuModel();

        try {
            switch ($action) {
                
                    // TOMBOL MULAI
                case 'mulai':
                    // Update status_hpa menjadi 'Penerimaan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Penerimaan']);
                    // Update data penerimaan
                    $penerimaanModel->updatePenerimaan($id_penerimaan, [
                        'id_user_penerimaan' => $id_user,
                        'status_penerimaan' => 'Proses Pemeriksaan',
                        'mulai_penerimaan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                    // TOMBOL SELESAI
                case 'selesai':
                    // Update data penerimaan ketika selesai
                    $penerimaanModel->updatePenerimaan($id_penerimaan, [
                        'id_user_penerimaan' => $id_user,  // Menggunakan id_user dari session
                        'status_penerimaan' => 'Sudah Diperiksa', // Status menjadi 'Sudah Diperiksa'
                        'selesai_penerimaan' => date('Y-m-d H:i:s'), // Menggunakan waktu lokal Asia/Jakarta
                    ]);
                    // update data mutu
                    $keseluruhan_nilai_mutu = $total_nilai_mutu + $indikator_1 + $indikator_2;
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_1' => $indikator_1,
                        'indikator_2' => $indikator_2, 
                        'total_nilai_mutu' => $keseluruhan_nilai_mutu, 
                    ]);
                    break;
                    
                    // TOMBOL KEMBALIKAN
                case 'kembalikan':
                    $penerimaanModel->updatePenerimaan($id_penerimaan, [
                        'id_user_penerimaan' => null,  // Menggunakan id_user dari session
                        'status_penerimaan' => 'Belum Diperiksa', // Status menjadi 'Belum'
                        'mulai_penerimaan' => null,
                        'selesai_penerimaan' => null, // Menggunakan waktu lokal Asia/Jakarta
                    ]);
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_1' => '0',
                        'indikator_2' => '0',
                        'total_nilai_mutu' => '0',
                    ]);
                    break;

                    // TOMBOL LANJUT
                case 'lanjut':
                    // Update status_hpa menjadi 'pengirisan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pengirisan']);

                    // Data untuk tabel pengirisan
                    $pengirisanData = [
                        'id_hpa'              => $id_hpa,  // Menambahkan id_hpa yang baru
                        'status_pengirisan'     => 'Belum Diiris', // Status awal
                    ];

                    // Simpan data ke tabel pengirisan
                    if (!$pengirisanModel->insert($pengirisanData)) {
                        throw new Exception('Gagal menyimpan data pengirisan.');
                    }

                    // Ambil id_pengirisan yang baru saja disimpan
                    $id_pengirisan = $pengirisanModel->getInsertID();

                    // Update id_pengirisan pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_pengirisan' => $id_pengirisan]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            // Anda bisa melempar exception atau memberikan pesan error yang lebih spesifik
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }
    public function penerimaan_details()
    {
        // Ambil id_penerimaan dari parameter GET
        $id_penerimaan = $this->request->getGet('id_penerimaan');

        if ($id_penerimaan) {
            // Muat model Penerimaan
            $model = new PenerimaanModel();

            // Ambil data penerimaan berdasarkan id_penerimaan dan relasi yang ada
            $data = $model->select(
                'penerimaan.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_penerimaan,
                mutu.indikator_1,
                mutu.indikator_2'
            )
            ->join('hpa',
                'penerimaan.id_hpa = hpa.id_hpa',
                'left'
            ) // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penerimaan.id_user_penerimaan = users.id_user', 'left') // Relasi dengan tabel users untuk penerimaan
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('penerimaan.id_penerimaan', $id_penerimaan) // Menambahkan filter berdasarkan id_penerimaan
            ->first(); // Mengambil satu data penerimaan berdasarkan id_penerimaan

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID Penerimaan tidak ditemukan.']);
        }
    }
}
