<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PembacaanModel;
use App\Models\ProsesModel\penulisanModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use App\Models\MutuModel;
use Exception;

class pembacaan extends BaseController
{
    protected $pembacaanModel;
    protected $userModel;

    public function __construct()
    {
        $this->pembacaanModel = new PembacaanModel();
        $this->userModel = new UsersModel();
        session()->set('previous_url', previous_url());
    }

    public function index_pembacaan()
    {
        session()->set('previous_url', previous_url());
        $pembacaanModel = new PembacaanModel();
        $pembacaanData['pembacaanData'] = $pembacaanModel->getPembacaanWithRelations();

        // Menggabungkan data dari model dan session
        $data = [
            'pembacaanData' => $pembacaanData['pembacaanData'],
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        // Mengirim data ke view untuk ditampilkan
        return view('proses/pembacaan', $data); // Update view
    }

    public function proses_pembacaan() // Update method
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
                    list($id_pembacaan, $id_hpa, $id_mutu) = explode(':', $id);
                    $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                    $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                    $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                    $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                    $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                    $total_nilai_mutu = $this->request->getPost('total_nilai_mutu');
                    $this->processAction($action, $id_pembacaan, $id_hpa, $id_user, $id_mutu, $indikator_4, $indikator_5, $indikator_6, $indikator_7, $indikator_8, $total_nilai_mutu); // Update method call
                }

                return redirect()->to('/pembacaan/index_pembacaan');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pembacaan, $id_hpa, $id_user, $id_mutu, $indikator_4, $indikator_5, $indikator_6, $indikator_7, $indikator_8, $total_nilai_mutu) // Update parameter
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pembacaanModel = new PembacaanModel();
        $penulisanModel = new PenulisanModel();
        $mutuModel = new MutuModel();

        try {
            switch ($action) {
                case 'mulai':
                    $pembacaanModel->updatePembacaan($id_pembacaan, [
                        'id_user_pembacaan' => $id_user,
                        'status_pembacaan' => 'Proses Pembacaan',
                        'mulai_pembacaan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'selesai':
                    // Update data pembacaan ketika selesai
                    $pembacaanModel->updatePembacaan($id_pembacaan, [
                        'id_user_pembacaan' => $id_user,
                        'status_pembacaan' => 'Selesai Pembacaan',
                        'selesai_pembacaan' => date('Y-m-d H:i:s'),
                    ]);
                    // update data mutu
                    $keseluruhan_nilai_mutu = $total_nilai_mutu + $indikator_5 + $indikator_6 + $indikator_7 + $indikator_8;
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_4' => $indikator_4,
                        'indikator_5' => $indikator_5,  
                        'indikator_6' => $indikator_6,
                        'indikator_7' => $indikator_7,
                        'indikator_8' => $indikator_8,                        
                        'total_nilai_mutu' => $keseluruhan_nilai_mutu,
                    ]);
                    break;

                case 'reset':
                    $pembacaanModel->updatePembacaan($id_pembacaan, [
                        'id_user_pembacaan' => null,
                        'status_pembacaan' => 'Belum Pembacaan',
                        'mulai_pembacaan' => null,
                        'selesai_pembacaan' => null,
                    ]);
                    // Konversi VARCHAR ke INTEGER
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_4' => '0',
                        'indikator_5' => '0',
                        'indikator_6' => '0',
                        'indikator_7' => '0',
                        'indikator_8' => '0',
                        'total_nilai_mutu' => '30',
                    ]);
                    break;

                    // TOMBOL KEMBALI
                case 'kembalikan':
                    $pembacaanModel->deletePembacaan($id_pembacaan);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Pewarnaan',
                        'id_pembacaan' => null,
                    ]);
                    break; 

                case 'lanjut':
                    // Update status_hpa menjadi 'penulisan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Penulisan']);

                    // Data untuk tabel penulisan
                    $penulisanData = [
                        'id_hpa'                   => $id_hpa,
                        'status_penulisan'         => 'Belum Penulisan',
                    ];

                    // Simpan data ke tabel penulisan
                    if (!$penulisanModel->insert($penulisanData)) {
                        throw new Exception('Gagal menyimpan data penulisan.');
                    }

                    // Ambil id_penulisan yang baru saja disimpan
                    $id_penulisan = $penulisanModel->getInsertID();

                    // Update id_penulisan pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_penulisan' => $id_penulisan]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pembacaan_details()
    {
        // Ambil id_pembacaan dari parameter GET
        $id_pembacaan = $this->request->getGet('id_pembacaan');

        if ($id_pembacaan) {
            // Muat model pembacaan
            $model = new PembacaanModel();

            // Ambil data pembacaan berdasarkan id_pembacaan dan relasi yang ada
            $data = $model->select(
                'pembacaan.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_pembacaan'
            )
                ->join(
                    'hpa',
                    'pembacaan.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pembacaan.id_user_pembacaan = users.id_user', 'left')
                ->where('pembacaan.id_pembacaan', $id_pembacaan)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pembacaan tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_pembacaan = $this->request->getPost('id_pembacaan');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_pembacaan && $id_hpa) {
            // Load model
            $pembacaanModel = new PembacaanModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel pembacaan
            $deleteResult = $pembacaanModel->deletePembacaan($id_pembacaan);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Pewarnaan',
                    'id_pembacaan' => null,
                ]);

                // Selesaikan transaksi
                $db->transComplete();

                // Cek apakah transaksi berhasil
                if ($db->transStatus() === FALSE) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus atau memperbarui data.']);
                }

                return $this->response->setJSON(['success' => true]);
            } else {
                // Jika delete gagal, rollback transaksi
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data pembacaan.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_pembacaan()
    {
        $id_pembacaan = $this->request->getGet('id_pembacaan');

        if (!$id_pembacaan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pembacaan tidak ditemukan.');
        }

        // Ambil data pembacaan berdasarkan ID
        $pembacaanData = $this->pembacaanModel->find($id_pembacaan);

        if (!$pembacaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pembacaan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pembacaanData' => $pembacaanData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pembacaan', $data);
    }

    public function update_pembacaan()
    {
        $id_pembacaan = $this->request->getPost('id_pembacaan');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_pembacaan_date');
        $mulai_time = $this->request->getPost('mulai_pembacaan_time');
        $selesai_date = $this->request->getPost('selesai_pembacaan_date');
        $selesai_time = $this->request->getPost('selesai_pembacaan_time');

        // Combine date and time into one value
        $mulai_pembacaan = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_pembacaan = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_pembacaan' => $this->request->getPost('id_user_pembacaan'),
            'status_pembacaan'  => $this->request->getPost('status_pembacaan'),
            'mulai_pembacaan'   => $mulai_pembacaan,
            'selesai_pembacaan' => $selesai_pembacaan,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pembacaanModel->update($id_pembacaan, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
