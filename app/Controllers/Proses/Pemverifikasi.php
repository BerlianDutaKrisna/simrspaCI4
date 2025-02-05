<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PemverifikasiModel;
use App\Models\ProsesModel\AutorizedModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class Pemverifikasi extends BaseController
{
    protected $pemverifikasiModel;
    protected $userModel;

    public function __construct()
    {
        $this->pemverifikasiModel = new PemverifikasiModel();
        $this->userModel = new UsersModel();
        session()->set('previous_url', previous_url());
    }

    public function index_pemverifikasi() // Update nama method
    {
        session()->set('previous_url', previous_url());
        $pemverifikasiModel = new PemverifikasiModel();
        $pemverifikasiData['pemverifikasiData'] = $pemverifikasiModel->getPemverifikasiWithRelations(); // Update nama variabel

        // Menggabungkan data dari model dan session
        $data = [
            'pemverifikasiData' => $pemverifikasiData['pemverifikasiData'], // Update nama variabel
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/pemverifikasi', $data); // Update nama view
    }

    public function proses_pemverifikasi() // Update nama method
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
                    list($id_pemverifikasi, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_pemverifikasi, $id_hpa, $id_user, $id_mutu); // Update nama variabel
                }

                return redirect()->to('/pemverifikasi/index_pemverifikasi'); // Update nama route
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pemverifikasi, $id_hpa, $id_user, $id_mutu) // Update nama variabel
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pemverifikasiModel = new PemverifikasiModel();
        $autorizedModel = new AutorizedModel();

        try {
            switch ($action) {
                    // TOMBOL MULAI PENGECEKAN
                case 'mulai':
                    $pemverifikasiModel->updatePemverifikasi($id_pemverifikasi, [ // Update nama method dan variabel
                        'id_user_pemverifikasi' => $id_user,
                        'status_pemverifikasi' => 'Proses Pemverifikasi',
                        'mulai_pemverifikasi' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                    // TOMBOL SELESAI PENGECEKAN
                case 'selesai':
                    $pemverifikasiModel->updatePemverifikasi($id_pemverifikasi, [ // Update nama method dan variabel
                        'id_user_pemverifikasi' => $id_user,
                        'status_pemverifikasi' => 'Selesai Pemverifikasi',
                        'selesai_pemverifikasi' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'reset':
                    $pemverifikasiModel->updatePemverifikasi($id_pemverifikasi, [
                        'id_user_pemverifikasi' => null,
                        'status_pemverifikasi' => 'Belum Pemverifikasi',
                        'mulai_pemverifikasi' => null,
                        'selesai_pemverifikasi' => null,
                    ]);
                    break;

                case 'kembalikan':
                    $pemverifikasiModel->deletePemverifikasi($id_pemverifikasi);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Penulisan',
                        'id_pemverifikasi' => null,
                    ]);
                    break; 

                case 'lanjut':
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Autorized']);
                    $autorizedData = [
                        'id_hpa' => $id_hpa,
                        'status_autorized' => 'Belum Authorized',
                    ];

                    if (!$autorizedModel->insert($autorizedData)) { // Update nama method dan variabel
                        throw new Exception('Gagal menyimpan data autorized.');
                    }

                    $id_autorized = $autorizedModel->getInsertID(); // Update nama variabel
                    $hpaModel->update($id_hpa, ['id_autorized' => $id_autorized]); // Update nama variabel
                    break;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pemverifikasi_details()
    {
        // Ambil id_pemverifikasi dari parameter GET
        $id_pemverifikasi = $this->request->getGet('id_pemverifikasi');

        if ($id_pemverifikasi) {
            // Muat model pemverifikasi
            $model = new PemverifikasiModel();

            // Ambil data pemverifikasi berdasarkan id_pemverifikasi dan relasi yang ada
            $data = $model->select(
                'pemverifikasi.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_pemverifikasi'
            )
                ->join(
                    'hpa',
                    'pemverifikasi.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pemverifikasi.id_user_pemverifikasi = users.id_user', 'left')
                ->where('pemverifikasi.id_pemverifikasi', $id_pemverifikasi)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pemverifikasi tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_pemverifikasi = $this->request->getPost('id_pemverifikasi');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_pemverifikasi && $id_hpa) {
            // Load model
            $pemverifikasiModel = new PemverifikasiModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel pemverifikasi
            $deleteResult = $pemverifikasiModel->deletePemverifikasi($id_pemverifikasi);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Penulisan',
                    'id_pemverifikasi' => null,
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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data pemverifikasi.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_pemverifikasi()
    {
        $id_pemverifikasi = $this->request->getGet('id_pemverifikasi');

        if (!$id_pemverifikasi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemverifikasi tidak ditemukan.');
        }

        // Ambil data pemverifikasi berdasarkan ID
        $pemverifikasiData = $this->pemverifikasiModel->find($id_pemverifikasi);

        if (!$pemverifikasiData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pemverifikasi tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pemverifikasiData' => $pemverifikasiData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pemverifikasi', $data);
    }

    public function update_pemverifikasi()
    {
        $id_pemverifikasi = $this->request->getPost('id_pemverifikasi');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_pemverifikasi_date');
        $mulai_time = $this->request->getPost('mulai_pemverifikasi_time');
        $selesai_date = $this->request->getPost('selesai_pemverifikasi_date');
        $selesai_time = $this->request->getPost('selesai_pemverifikasi_time');

        // Combine date and time into one value
        $mulai_pemverifikasi = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_pemverifikasi = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_pemverifikasi' => $this->request->getPost('id_user_pemverifikasi'),
            'status_pemverifikasi'  => $this->request->getPost('status_pemverifikasi'),
            'mulai_pemverifikasi'   => $mulai_pemverifikasi,
            'selesai_pemverifikasi' => $selesai_pemverifikasi,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemverifikasiModel->update($id_pemverifikasi, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
