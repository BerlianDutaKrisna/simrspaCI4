<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PewarnaanModel;
use App\Models\ProsesModel\PembacaanModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class Pewarnaan extends BaseController
{
    protected $pewarnaanModel;
    protected $userModel;
    protected $hpaModel;

    public function __construct()
    {
        $this->pewarnaanModel = new PewarnaanModel();
        $this->userModel = new UsersModel();
        $this->hpaModel = new HpaModel();
    }

    public function index_pewarnaan()
    {
        $pewarnaanData = $this->pewarnaanModel->getPewarnaanWithRelations();

        $data = [
            'pewarnaanData' => $pewarnaanData,
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
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('proses/pewarnaan', $data);
    }

    public function proses_pewarnaan() // Update method
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
                    list($id_pewarnaan, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_pewarnaan, $id_hpa, $id_user, $id_mutu); // Update method call
                }

                return redirect()->to('/pewarnaan/index_pewarnaan'); // Update URL
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pewarnaan, $id_hpa, $id_user, $id_mutu) // Update parameter
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pewarnaanModel = new PewarnaanModel();
        $pembacaanModel = new PembacaanModel();

        try {
            switch ($action) {
                case 'mulai':
                    // Update data pewarnaan
                    $pewarnaanModel->updatePewarnaan($id_pewarnaan, [
                        'id_user_pewarnaan' => $id_user,
                        'status_pewarnaan' => 'Proses Pewarnaan',
                        'mulai_pewarnaan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'selesai':
                    // Update data pewarnaan ketika selesai
                    $pewarnaanModel->updatePewarnaan($id_pewarnaan, [
                        'id_user_pewarnaan' => $id_user,
                        'status_pewarnaan' => 'Selesai Pewarnaan',
                        'selesai_pewarnaan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'reset':
                    $pewarnaanModel->updatePewarnaan($id_pewarnaan, [
                        'id_user_pewarnaan' => null,
                        'status_pewarnaan' => 'Belum Pewarnaan',
                        'mulai_pewarnaan' => null,
                        'selesai_pewarnaan' => null,
                    ]);
                    break;

                    // TOMBOL KEMBALI
                case 'kembalikan':
                    $pewarnaanModel->deletePewarnaan($id_pewarnaan);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Pemotongan Tipis',
                        'id_pewarnaan' => null,
                    ]);
                    break; 

                case 'lanjut':
                    // Update status_hpa menjadi 'pembacaan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pembacaan']);

                    // Data untuk tabel pembacaan
                    $pembacaanData = [
                        'id_hpa'                   => $id_hpa,
                        'status_pembacaan'         => 'Belum Pembacaan',
                    ];

                    // Simpan data ke tabel pembacaan
                    if (!$pembacaanModel->insert($pembacaanData)) {
                        throw new Exception('Gagal menyimpan data pembacaan.');
                    }

                    // Ambil id_pembacaan yang baru saja disimpan
                    $id_pembacaan = $pembacaanModel->getInsertID();

                    // Update id_pembacaan pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_pembacaan' => $id_pembacaan]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pewarnaan_details()
    {
        // Ambil id_pewarnaan dari parameter GET
        $id_pewarnaan = $this->request->getGet('id_pewarnaan');

        if ($id_pewarnaan) {
            // Muat model pewarnaan
            $model = new PewarnaanModel();

            // Ambil data pewarnaan berdasarkan id_pewarnaan dan relasi yang ada
            $data = $model->select(
                'pewarnaan.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_pewarnaan'
            )
                ->join(
                    'hpa',
                    'pewarnaan.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pewarnaan.id_user_pewarnaan = users.id_user', 'left')
                ->where('pewarnaan.id_pewarnaan', $id_pewarnaan)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pewarnaan tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_pewarnaan = $this->request->getPost('id_pewarnaan');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_pewarnaan && $id_hpa) {
            // Load model
            $pewarnaanModel = new PewarnaanModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel pewarnaan
            $deleteResult = $pewarnaanModel->deletePewarnaan($id_pewarnaan);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Pemotongan Tipis',
                    'id_pewarnaan' => null,
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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data pewarnaan.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_pewarnaan()
    {
        $id_pewarnaan = $this->request->getGet('id_pewarnaan');

        if (!$id_pewarnaan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pewarnaan tidak ditemukan.');
        }

        // Ambil data pewarnaan berdasarkan ID
        $pewarnaanData = $this->pewarnaanModel->find($id_pewarnaan);

        if (!$pewarnaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pewarnaan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pewarnaanData' => $pewarnaanData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pewarnaan', $data);
    }

    public function update_pewarnaan()
    {
        $id_pewarnaan = $this->request->getPost('id_pewarnaan');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_pewarnaan_date');
        $mulai_time = $this->request->getPost('mulai_pewarnaan_time');
        $selesai_date = $this->request->getPost('selesai_pewarnaan_date');
        $selesai_time = $this->request->getPost('selesai_pewarnaan_time');

        // Combine date and time into one value
        $mulai_pewarnaan = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_pewarnaan = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_pewarnaan' => $this->request->getPost('id_user_pewarnaan'),
            'status_pewarnaan'  => $this->request->getPost('status_pewarnaan'),
            'mulai_pewarnaan'   => $mulai_pewarnaan,
            'selesai_pewarnaan' => $selesai_pewarnaan,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pewarnaanModel->update($id_pewarnaan, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
