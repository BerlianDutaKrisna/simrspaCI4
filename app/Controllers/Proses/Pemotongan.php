<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PemotonganModel;
use App\Models\ProsesModel\PemprosesanModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class Pemotongan extends BaseController
{
    protected $pemotonganModel;
    protected $userModel;

    public function __construct()
    {
        $this->pemotonganModel = new PemotonganModel();
        $this->userModel = new UsersModel();
        session()->set('previous_url', previous_url());
    }

    public function index_pemotongan()
    {
        session()->set('previous_url', previous_url());
        // Mengambil id_user dan nama_user dari session
        $pemotonganModel = new PemotonganModel();

        // Mengambil data HPA beserta relasinya
        $pemotonganData['pemotonganData'] = $pemotonganModel->getPemotonganWithRelations();

        // Menggabungkan data dari model dan session
        $data = [
            'pemotonganData' => $pemotonganData['pemotonganData'],
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        // Mengirim data ke view untuk ditampilkan
        return view('proses/pemotongan', $data);
    }

    public function proses_pemotongan()
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
                    list($id_pemotongan, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_pemotongan, $id_hpa, $id_user, $id_mutu);
                }

                return redirect()->to('/pemotongan/index_pemotongan');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pemotongan, $id_hpa, $id_user, $id_mutu)
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pemotonganModel = new PemotonganModel();
        $pemprosesanModel = new PemprosesanModel();

        try {
            switch ($action) {
                    // TOMBOL MULAI PENGECEKAN
                case 'mulai':
                    // Update data pemotongan
                    $pemotonganModel->updatePemotongan($id_pemotongan, [
                        'id_user_pemotongan' => $id_user,
                        'status_pemotongan' => 'Proses Pemotongan',
                        'mulai_pemotongan' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                    // TOMBOL SELESAI PENGECEKAN
                case 'selesai':
                    // Update data pemotongan ketika selesai
                    $pemotonganModel->updatePemotongan($id_pemotongan, [
                        'id_user_pemotongan' => $id_user,
                        'status_pemotongan' => 'Selesai Pemotongan',
                        'selesai_pemotongan' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                    // TOMBOL RESET PENGECEKAN
                case 'reset':
                    $pemotonganModel->updatePemotongan($id_pemotongan, [
                        'id_user_pemotongan' => null,
                        'status_pemotongan' => 'Belum Pemotongan',
                        'mulai_pemotongan' => null,
                        'selesai_pemotongan' => null,
                    ]);
                    break;

                    // TOMBOL KEMBALI
                case 'kembalikan':
                    // Menghapus data pemotongan berdasarkan id_pemotongan
                    $pemotonganModel->deletePemotongan($id_pemotongan);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Pengirisan',
                        'id_pemotongan' => null,
                    ]);;
                break;     

                case 'lanjut':
                    // Update status_hpa menjadi 'Pemprosesan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemprosesan']);

                    // Data untuk tabel pemprosesan
                    $pemprosesanData = [
                        'id_hpa'              => $id_hpa,
                        'status_pemprosesan'     => 'Belum Pemprosesan',
                    ];

                    // Simpan data ke tabel pemprosesan
                    if (!$pemprosesanModel->insert($pemprosesanData)) {
                        throw new Exception('Gagal menyimpan data pemprosesan.');
                    }

                    // Ambil id_pemprosesan yang baru saja disimpan
                    $id_pemprosesan = $pemprosesanModel->getInsertID();

                    // Update id_pemprosesan pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_pemprosesan' => $id_pemprosesan]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            // Anda bisa melempar exception atau memberikan pesan error yang lebih spesifik
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pemotongan_details()
    {
        // Ambil id_pemotongan dari parameter GET
        $id_pemotongan = $this->request->getGet('id_pemotongan');

        if ($id_pemotongan) {
            // Muat model pemotongan
            $model = new PemotonganModel();

            // Ambil data pemotongan berdasarkan id_pemotongan dan relasi yang ada
            $data = $model->select(
                'pemotongan.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_pemotongan'
            )
                ->join(
                    'hpa',
                    'pemotongan.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pemotongan.id_user_pemotongan = users.id_user', 'left')
                ->where('pemotongan.id_pemotongan', $id_pemotongan)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pemotongan tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_pemotongan = $this->request->getPost('id_pemotongan');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_pemotongan && $id_hpa) {
            // Load model
            $pemotonganModel = new PemotonganModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel pemotongan
            $deleteResult = $pemotonganModel->deletePemotongan($id_pemotongan);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                // Update field id_pemotongan menjadi null pada tabel hpa
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Pengirisan',
                    'id_pemotongan' => null,
                ]);;

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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data pemotongan.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_pemotongan()
    {
        $id_pemotongan = $this->request->getGet('id_pemotongan');

        if (!$id_pemotongan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemotongan tidak ditemukan.');
        }

        // Ambil data pemotongan berdasarkan ID
        $pemotonganData = $this->pemotonganModel->find($id_pemotongan);

        if (!$pemotonganData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pemotongan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pemotonganData' => $pemotonganData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pemotongan', $data);
    }

    public function update_pemotongan()
    {
        $id_pemotongan = $this->request->getPost('id_pemotongan');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_pemotongan_date');
        $mulai_time = $this->request->getPost('mulai_pemotongan_time');
        $selesai_date = $this->request->getPost('selesai_pemotongan_date');
        $selesai_time = $this->request->getPost('selesai_pemotongan_time');

        // Combine date and time into one value
        $mulai_pemotongan = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_pemotongan = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_pemotongan' => $this->request->getPost('id_user_pemotongan'),
            'status_pemotongan'  => $this->request->getPost('status_pemotongan'),
            'mulai_pemotongan'   => $mulai_pemotongan,
            'selesai_pemotongan' => $selesai_pemotongan,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemotonganModel->update($id_pemotongan, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
