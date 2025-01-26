<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PengirisanModel;
use App\Models\ProsesModel\PemotonganModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class Pengirisan extends BaseController
{
    protected $pengirisanModel;
    protected $userModel;

    public function __construct()
    {
        $this->pengirisanModel = new PengirisanModel();
        $this->userModel = new UsersModel();
    }

    public function index_pengirisan()
    {
        // Mengambil id_user dan nama_user dari session
        $pengirisanModel = new PengirisanModel();

        // Mengambil data HPA beserta relasinya
        $pengirisanData['pengirisanData'] = $pengirisanModel->getPengirisanWithRelations();

        // Menggabungkan data dari model dan session
        $data = [
            'pengirisanData' => $pengirisanData['pengirisanData'],
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/pengirisan', $data);
    }

    public function proses_pengirisan()
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
                    list($id_pengirisan, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_pengirisan, $id_hpa, $id_user, $id_mutu);
                }

                return redirect()->to('/pengirisan/index_pengirisan');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pengirisan, $id_hpa, $id_user, $id_mutu)
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pengirisanModel = new PengirisanModel();
        $pemotonganModel = new PemotonganModel();

        try {
            switch ($action) {
                    // TOMBOL MULAI
                case 'mulai':
                    // Update data pengirisan
                    $pengirisanModel->updatePengirisan($id_pengirisan, [
                        'id_user_pengirisan' => $id_user,
                        'status_pengirisan' => 'Proses Pengirisan',
                        'mulai_pengirisan' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                    // TOMBOL SELESAI PENGECEKAN
                case 'selesai':
                    // Update data pengirisan ketika selesai
                    $pengirisanModel->updatePengirisan($id_pengirisan, [
                        'id_user_pengirisan' => $id_user,
                        'status_pengirisan' => 'Selesai Pengirisan',
                        'selesai_pengirisan' => date('Y-m-d H:i:s'),
                    ]);

                    break;
                    // TOMBOL RESET
                case 'reset':
                    $pengirisanModel->updatePengirisan($id_pengirisan, [
                        'id_user_pengirisan' => null,
                        'status_pengirisan' => 'Belum Pengirisan',
                        'mulai_pengirisan' => null,
                        'selesai_pengirisan' => null,
                    ]);
                    break;

                    // TOMBOL KEMBALI
                case 'kembalikan':
                        // Menghapus data pengirisan berdasarkan id_pengirisan
                        $pengirisanModel->deletePengirisan($id_pengirisan);
                        $hpaModel->updateHpa($id_hpa, [
                            'status_hpa' => 'Penerimaan',
                            'id_pengirisan' => null,
                        ]);
                    break;                    

                    // TOMBOL LANJUT
                case 'lanjut':
                    // Update status_hpa menjadi 'pemotongan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemotongan']);

                    // Data untuk tabel pemotongan
                    $pemotonganData = [
                        'id_hpa'              => $id_hpa,  // Menambahkan id_hpa yang baru
                        'status_pemotongan'     => 'Belum Pemotongan', // Status awal
                    ];

                    // Simpan data ke tabel pemotongan
                    if (!$pemotonganModel->insert($pemotonganData)) {
                        throw new Exception('Gagal menyimpan data pemotongan.');
                    }

                    // Ambil id_pemotongan yang baru saja disimpan
                    $id_pemotongan = $pemotonganModel->getInsertID();

                    // Update id_pemotongan pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_pemotongan' => $id_pemotongan]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            // Anda bisa melempar exception atau memberikan pesan error yang lebih spesifik
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }
    
    public function pengirisan_details()
    {
        // Ambil id_pengirisan dari parameter GET
        $id_pengirisan = $this->request->getGet('id_pengirisan');

        if ($id_pengirisan) {
            // Muat model pengirisan
            $model = new PengirisanModel();

            // Ambil data pengirisan berdasarkan id_pengirisan dan relasi yang ada
            $data = $model->select(
                'pengirisan.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_pengirisan'
            )
                ->join(
                    'hpa',
                    'pengirisan.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pengirisan.id_user_pengirisan = users.id_user', 'left')
                ->where('pengirisan.id_pengirisan', $id_pengirisan)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pengirisan tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_pengirisan = $this->request->getPost('id_pengirisan');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_pengirisan && $id_hpa) {
            // Load model
            $pengirisanModel = new PengirisanModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel pengirisan
            $deleteResult = $pengirisanModel->deletePengirisan($id_pengirisan);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                // Update field id_pengirisan menjadi null pada tabel hpa
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Penerimaan',
                    'id_pengirisan' => null,
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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data pengirisan.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_pengirisan()
    {
        $id_pengirisan = $this->request->getGet('id_pengirisan');

        if (!$id_pengirisan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pengirisan tidak ditemukan.');
        }

        // Ambil data pengirisan berdasarkan ID
        $pengirisanData = $this->pengirisanModel->find($id_pengirisan);

        if (!$pengirisanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pengirisan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pengirisanData' => $pengirisanData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pengirisan', $data);
    }

    public function update_pengirisan()
    {
        $id_pengirisan = $this->request->getPost('id_pengirisan');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_pengirisan_date');
        $mulai_time = $this->request->getPost('mulai_pengirisan_time');
        $selesai_date = $this->request->getPost('selesai_pengirisan_date');
        $selesai_time = $this->request->getPost('selesai_pengirisan_time');

        // Combine date and time into one value
        $mulai_pengirisan = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_pengirisan = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_pengirisan' => $this->request->getPost('id_user_pengirisan'),
            'status_pengirisan'  => $this->request->getPost('status_pengirisan'),
            'mulai_pengirisan'   => $mulai_pengirisan,
            'selesai_pengirisan' => $selesai_pengirisan,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pengirisanModel->update($id_pengirisan, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
