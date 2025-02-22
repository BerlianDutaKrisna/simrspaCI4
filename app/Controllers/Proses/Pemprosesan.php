<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PemprosesanModel;
use App\Models\ProsesModel\PenanamanModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class Pemprosesan extends BaseController
{
    protected $pemprosesanModel;
    protected $penanamanModel;
    protected $userModel;
    protected $hpaModel;
    protected $session;

    public function __construct()
    {
        $this->pemprosesanModel = new PemprosesanModel();
        $this->penanamanModel = new PenanamanModel();
        $this->userModel = new UsersModel();
        $this->hpaModel = new HpaModel();
        $this->session = session();
    }

    public function index_pemprosesan() 
    {
        $pemprosesanData = $this->pemprosesanModel->getPemprosesanWithRelations();

        $data = [
            'pemprosesanData' => $pemprosesanData,
            'countPenerimaan' => $this->hpaModel->countPenerimaan(),
            'countPengirisan' => $this->hpaModel->countPengirisan(),
            'countPemotongan' => $this->hpaModel->countPemotongan(),
            'countPemprosesan' => $this->hpaModel->countPemprosesan(),
            'countPenanaman' => $this->hpaModel->countPenanaman(),
            'countPemotonganTipis' => $this->hpaModel->countPemotonganTipis(),
            'countPewarnaan' => $this->hpaModel->countPewarnaan(),
            'countPembacaan' => $this->hpaModel->countPembacaan(),
            'countPenulisan' => $this->hpaModel->countPenulisan(),
            'countPemverifikasi' => $this->hpaModel->countPemverifikasi(),
            'countAutorized' => $this->hpaModel->countAutorized(),
            'countPencetakan' => $this->hpaModel->countPencetakan(),
            'id_user' => $this->session->get('id_user'),
            'nama_user' => $this->session->get('nama_user'),
        ];

        return view('proses/pemprosesan', $data); // Update view
    }

    public function proses_pemprosesan() // Update method
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
                    list($id_pemprosesan, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_pemprosesan, $id_hpa, $id_user, $id_mutu); // Update method call
                }

                return redirect()->to('/pemprosesan/index_pemprosesan'); // Update URL
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pemprosesan, $id_hpa, $id_user, $id_mutu) // Update parameter
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pemprosesanModel = new PemprosesanModel();
        $penanamanModel = new PenanamanModel();

        try {
            switch ($action) {
                case 'mulai':
                    // Update data pemprosesan
                    $pemprosesanModel->updatePemprosesan($id_pemprosesan, [
                        'id_user_pemprosesan' => $id_user,
                        'status_pemprosesan' => 'Proses Pemprosesan',
                        'mulai_pemprosesan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'selesai':
                    // Update data pemprosesan ketika selesai
                    $pemprosesanModel->updatePemprosesan($id_pemprosesan, [
                        'id_user_pemprosesan' => $id_user,
                        'status_pemprosesan' => 'Selesai Pemprosesan',
                        'selesai_pemprosesan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                    // TOMBOL RESET PENGECEKAN
                case 'reset':
                    $pemprosesanModel->updatePemprosesan($id_pemprosesan, [
                        'id_user_pemprosesan' => null,
                        'status_pemprosesan' => 'Belum Pemprosesan',
                        'mulai_pemprosesan' => null,
                        'selesai_pemprosesan' => null,
                    ]);
                    break;

                    // TOMBOL KEMBALI
                case 'kembalikan':
                    $pemprosesanModel->deletePemprosesan($id_pemprosesan);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Pemotongan',
                        'id_pemprosesan' => null,
                    ]);
                    break;   

                case 'lanjut':
                    // Update status_hpa menjadi 'penanaman' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Penanaman']);

                    // Data untuk tabel penanaman
                    $penanamanData = [
                        'id_hpa'              => $id_hpa,
                        'status_penanaman'  => 'Belum Penanaman',
                    ];

                    // Simpan data ke tabel penanaman
                    if (!$penanamanModel->insert($penanamanData)) {
                        throw new Exception('Gagal menyimpan data penanaman.');
                    }

                    // Ambil id_penanaman yang baru saja disimpan
                    $id_penanaman = $penanamanModel->getInsertID();

                    // Update id_penanaman pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_penanaman' => $id_penanaman]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pemprosesan_details()
    {
        // Ambil id_pemprosesan dari parameter GET
        $id_pemprosesan = $this->request->getGet('id_pemprosesan');

        if ($id_pemprosesan) {
            // Muat model pemprosesan
            $model = new PemprosesanModel();

            // Ambil data pemprosesan berdasarkan id_pemprosesan dan relasi yang ada
            $data = $model->select(
                'pemprosesan.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_pemprosesan'
            )
                ->join(
                    'hpa',
                    'pemprosesan.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pemprosesan.id_user_pemprosesan = users.id_user', 'left')
                ->where('pemprosesan.id_pemprosesan', $id_pemprosesan)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pemprosesan tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_pemprosesan = $this->request->getPost('id_pemprosesan');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_pemprosesan && $id_hpa) {
            // Load model
            $pemprosesanModel = new PemprosesanModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel pemprosesan
            $deleteResult = $pemprosesanModel->deletePemprosesan($id_pemprosesan);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                // Update field id_pemprosesan menjadi null pada tabel hpa
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Pemotongan',
                    'id_pemprosesan' => null,
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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data pemprosesan.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_pemprosesan()
    {
        $id_pemprosesan = $this->request->getGet('id_pemprosesan');

        if (!$id_pemprosesan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemprosesan tidak ditemukan.');
        }

        // Ambil data pemprosesan berdasarkan ID
        $pemprosesanData = $this->pemprosesanModel->find($id_pemprosesan);

        if (!$pemprosesanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pemprosesan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pemprosesanData' => $pemprosesanData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pemprosesan', $data);
    }

    public function update_pemprosesan()
    {
        $id_pemprosesan = $this->request->getPost('id_pemprosesan');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_pemprosesan_date');
        $mulai_time = $this->request->getPost('mulai_pemprosesan_time');
        $selesai_date = $this->request->getPost('selesai_pemprosesan_date');
        $selesai_time = $this->request->getPost('selesai_pemprosesan_time');

        // Combine date and time into one value
        $mulai_pemprosesan = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_pemprosesan = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_pemprosesan' => $this->request->getPost('id_user_pemprosesan'),
            'status_pemprosesan'  => $this->request->getPost('status_pemprosesan'),
            'mulai_pemprosesan'   => $mulai_pemprosesan,
            'selesai_pemprosesan' => $selesai_pemprosesan,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemprosesanModel->update($id_pemprosesan, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
