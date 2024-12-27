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
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
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
                    // TOMBOL MULAI PENGECEKAN
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
                    // TOMBOL SELESAI PENGECEKAN
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
                        'indikator_1' => $indikator_1,  // Menggunakan id_user dari session
                        'indikator_2' => $indikator_2, // Status menjadi 'Sudah Diperiksa'
                        'total_nilai_mutu' => $keseluruhan_nilai_mutu, // Menggunakan waktu lokal Asia/Jakarta
                    ]);
                    break;
                    // TOMBOL KEMBALIKAN PENGECEKAN
                case 'kembalikan':
                    $penerimaanModel->updatePenerimaan($id_penerimaan, [
                        'id_user_penerimaan' => $id_user,  // Menggunakan id_user dari session
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

                case 'lanjut':
                    // Update status_hpa menjadi 'pengirisan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pengirisan']);

                    // Data untuk tabel pengirisan
                    $pengirisanData = [
                        'id_hpa'              => $id_hpa,  // Menambahkan id_hpa yang baru
                        'id_user_pengirisan'    => $id_user,
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
}
