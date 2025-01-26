<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PemotonganTipisModel;
use App\Models\ProsesModel\PewarnaanModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class PemotonganTipis extends BaseController
{
    protected $pemotonganTipisModel;
    protected $userModel;

    public function __construct()
    {
        $this->pemotonganTipisModel = new PemotonganTipisModel();
        $this->userModel = new UsersModel();
    }

    public function index_pemotongan_tipis() // Update method
    {
        // Mengambil id_user dan nama_user dari session
        $pemotonganTipisModel = new PemotonganTipisModel(); // Update model

        // Mengambil data HPA beserta relasinya
        $pemotonganTipisData['pemotonganTipisData'] = $pemotonganTipisModel->getPemotonganTipisWithRelations(); // Update data

        // Menggabungkan data dari model dan session
        $data = [
            'pemotonganTipisData' => $pemotonganTipisData['pemotonganTipisData'], // Update data
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/pemotongan_tipis', $data); // Update view
    }

    public function proses_pemotongan_tipis() // Update method
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
                    list($id_pemotongan_tipis, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_pemotongan_tipis, $id_hpa, $id_user, $id_mutu); // Update method call
                }

                return redirect()->to('/pemotongan_tipis/index_pemotongan_tipis'); // Update URL
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pemotongan_tipis, $id_hpa, $id_user, $id_mutu) // Update parameter
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pemotonganTipisModel = new PemotonganTipisModel();
        $pewarnaanModel = new PewarnaanModel();

        try {
            switch ($action) {
                case 'mulai':
                    // Update data pemotongan tipis
                    $pemotonganTipisModel->updatePemotonganTipis($id_pemotongan_tipis, [
                        'id_user_pemotongan_tipis' => $id_user,
                        'status_pemotongan_tipis' => 'Proses Pemotongan Tipis',
                        'mulai_pemotongan_tipis' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'selesai':
                    // Update data pemotongan tipis ketika selesai
                    $pemotonganTipisModel->updatePemotonganTipis($id_pemotongan_tipis, [
                        'id_user_pemotongan_tipis' => $id_user,
                        'status_pemotongan_tipis' => 'Selesai Pemotongan Tipis',
                        'selesai_pemotongan_tipis' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'reset':
                    $pemotonganTipisModel->updatePemotonganTipis($id_pemotongan_tipis, [
                        'id_user_pemotongan_tipis' => null,
                        'status_pemotongan_tipis' => 'Belum Pemotongan Tipis',
                        'mulai_pemotongan_tipis' => null,
                        'selesai_pemotongan_tipis' => null,
                    ]);
                    break;

                    // TOMBOL KEMBALI
                case 'kembalikan':
                    $pemotonganTipisModel->deletePemotonganTipis($id_pemotongan_tipis);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Penanaman',
                        'id_pemotongan_tipis' => null,
                    ]);
                    break; 

                case 'lanjut':
                    // Update status_hpa menjadi 'Pewarnaan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pewarnaan']);

                    // Data untuk tabel Pewarnaan
                    $pewarnaanData = [
                        'id_hpa'            => $id_hpa,
                        'status_pewarnaan'  => 'Belum Pewarnaan',
                    ];

                    // Simpan data ke tabel Pewarnaan
                    if (!$pewarnaanModel->insert($pewarnaanData)) {
                        throw new Exception('Gagal menyimpan data Pewarnaan.');
                    }

                    // Ambil id_pewarnaan yang baru saja disimpan
                    $id_pewarnaan = $pewarnaanModel->getInsertID();

                    // Update id_pewarnaan pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_pewarnaan' => $id_pewarnaan]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pemotongan_tipis_details()
    {
        // Ambil id_pemotongan_tipis dari parameter GET
        $id_pemotongan_tipis = $this->request->getGet('id_pemotongan_tipis');

        if ($id_pemotongan_tipis) {
            // Muat model pemotongan_tipis
            $model = new PemotonganTipisModel();

            // Ambil data pemotongan_tipis berdasarkan id_pemotongan_tipis dan relasi yang ada
            $data = $model->select(
                'pemotongan_tipis.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_pemotongan_tipis'
            )
                ->join(
                    'hpa',
                    'pemotongan_tipis.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pemotongan_tipis.id_user_pemotongan_tipis = users.id_user', 'left')
                ->where('pemotongan_tipis.id_pemotongan_tipis', $id_pemotongan_tipis)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pemotongan_tipis tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_pemotongan_tipis = $this->request->getPost('id_pemotongan_tipis');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_pemotongan_tipis && $id_hpa) {
            // Load model
            $pemotongan_tipisModel = new PemotonganTipisModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel pemotongan_tipis
            $deleteResult = $pemotongan_tipisModel->deletePemotonganTipis($id_pemotongan_tipis);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Penanaman',
                    'id_pemotongan_tipis' => null,
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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data pemotongan_tipis.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_pemotongan_tipis()
    {
        $id_pemotongan_tipis = $this->request->getGet('id_pemotongan_tipis');

        if (!$id_pemotongan_tipis) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemotongan_tipis tidak ditemukan.');
        }

        // Ambil data pemotongan_tipis berdasarkan ID
        $pemotongan_tipisData = $this->pemotonganTipisModel->find($id_pemotongan_tipis);

        if (!$pemotongan_tipisData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pemotongan_tipis tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pemotongan_tipisData' => $pemotongan_tipisData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pemotongan_tipis', $data);
    }

    public function update_pemotongan_tipis()
    {
        $id_pemotongan_tipis = $this->request->getPost('id_pemotongan_tipis');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_pemotongan_tipis_date');
        $mulai_time = $this->request->getPost('mulai_pemotongan_tipis_time');
        $selesai_date = $this->request->getPost('selesai_pemotongan_tipis_date');
        $selesai_time = $this->request->getPost('selesai_pemotongan_tipis_time');

        // Combine date and time into one value
        $mulai_pemotongan_tipis = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_pemotongan_tipis = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_pemotongan_tipis' => $this->request->getPost('id_user_pemotongan_tipis'),
            'status_pemotongan_tipis'  => $this->request->getPost('status_pemotongan_tipis'),
            'mulai_pemotongan_tipis'   => $mulai_pemotongan_tipis,
            'selesai_pemotongan_tipis' => $selesai_pemotongan_tipis,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemotonganTipisModel->update($id_pemotongan_tipis, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
