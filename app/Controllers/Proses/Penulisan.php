<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PenulisanModel;
use App\Models\ProsesModel\PemverifikasiModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class Penulisan extends BaseController
{
    protected $penulisanModel;
    protected $userModel;

    public function __construct()
    {
        $this->penulisanModel = new PenulisanModel();
        $this->userModel = new UsersModel();
    }


    public function index_penulisan() // Update nama method
    {
        // Mengambil id_user dan nama_user dari session
        $penulisanModel = new PenulisanModel(); // Update nama model

        // Mengambil data HPA beserta relasinya
        $penulisanData['penulisanData'] = $penulisanModel->getPenulisanWithRelations(); // Update nama variabel

        // Menggabungkan data dari model dan session
        $data = [
            'penulisanData' => $penulisanData['penulisanData'], // Update nama variabel
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/penulisan', $data); // Update nama view
    }

    public function proses_penulisan() // Update nama method
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
                    list($id_penulisan, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_penulisan, $id_hpa, $id_user, $id_mutu); // Update method call
                }

                return redirect()->to('/penulisan/index_penulisan'); // Update URL
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_penulisan, $id_hpa, $id_user, $id_mutu) // Update parameter
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $penulisanModel = new PenulisanModel();
        $pemverifikasiModel = new PemverifikasiModel();

        try {
            switch ($action) {
                case 'mulai':
                    $penulisanModel->updatePenulisan($id_penulisan, [
                        'id_user_penulisan' => $id_user,
                        'status_penulisan' => 'Proses Penulisan',
                        'mulai_penulisan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'selesai':
                    // Update data penulisan ketika selesai
                    $penulisanModel->updatePenulisan($id_penulisan, [
                        'id_user_penulisan' => $id_user,
                        'status_penulisan' => 'Selesai Penulisan',
                        'selesai_penulisan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'kembalikan':
                    $penulisanModel->updatePenulisan($id_penulisan, [
                        'id_user_penulisan' => null,
                        'status_penulisan' => 'Belum Penulisan',
                        'mulai_penulisan' => null,
                        'selesai_penulisan' => null,
                    ]);
                    break;

                case 'lanjut':
                    // Update status_hpa menjadi 'pemverifikasi' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemverifikasi']);

                    // Data untuk tabel pemverifikasi
                    $pemverifikasiData = [
                        'id_hpa'                 => $id_hpa,
                        'status_pemverifikasi'       => 'Belum Pemverifikasi',
                    ];

                    // Simpan data ke tabel pemverifikasi
                    if (!$pemverifikasiModel->insert($pemverifikasiData)) {
                        throw new Exception('Gagal menyimpan data pemverifikasi.');
                    }

                    // Ambil id_pemverifikasi yang baru saja disimpan
                    $id_pemverifikasi = $pemverifikasiModel->getInsertID();

                    // Update id_pemverifikasi pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_pemverifikasi' => $id_pemverifikasi]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function penulisan_details()
    {
        // Ambil id_penulisan dari parameter GET
        $id_penulisan = $this->request->getGet('id_penulisan');

        if ($id_penulisan) {
            // Muat model penulisan
            $model = new PenulisanModel();

            // Ambil data penulisan berdasarkan id_penulisan dan relasi yang ada
            $data = $model->select(
                'penulisan.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_penulisan'
            )
                ->join(
                    'hpa',
                    'penulisan.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'penulisan.id_user_penulisan = users.id_user', 'left')
                ->where('penulisan.id_penulisan', $id_penulisan)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID penulisan tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_penulisan = $this->request->getPost('id_penulisan');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_penulisan && $id_hpa) {
            // Load model
            $penulisanModel = new PenulisanModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel penulisan
            $deleteResult = $penulisanModel->deletepenulisan($id_penulisan);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                // Update field id_penulisan menjadi null pada tabel hpa
                $hpaModel->updateIdpenulisan($id_hpa);

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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data penulisan.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_penulisan()
    {
        $id_penulisan = $this->request->getGet('id_penulisan');

        if (!$id_penulisan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penulisan tidak ditemukan.');
        }

        // Ambil data penulisan berdasarkan ID
        $penulisanData = $this->penulisanModel->find($id_penulisan);

        if (!$penulisanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penulisan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'penulisanData' => $penulisanData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_penulisan', $data);
    }

    public function update_penulisan()
    {
        $id_penulisan = $this->request->getPost('id_penulisan');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_penulisan_date');
        $mulai_time = $this->request->getPost('mulai_penulisan_time');
        $selesai_date = $this->request->getPost('selesai_penulisan_date');
        $selesai_time = $this->request->getPost('selesai_penulisan_time');

        // Combine date and time into one value
        $mulai_penulisan = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_penulisan = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_penulisan' => $this->request->getPost('id_user_penulisan'),
            'status_penulisan'  => $this->request->getPost('status_penulisan'),
            'mulai_penulisan'   => $mulai_penulisan,
            'selesai_penulisan' => $selesai_penulisan,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->penulisanModel->update($id_penulisan, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
