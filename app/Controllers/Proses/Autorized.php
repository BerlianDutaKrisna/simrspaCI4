<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\AutorizedModel;
use App\Models\ProsesModel\PencetakanModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class Autorized extends BaseController
{
    protected $autorizedModel;
    protected $userModel;

    public function __construct()
    {
        $this->autorizedModel = new AutorizedModel();
        $this->userModel = new UsersModel();
        session()->set('previous_url', previous_url());
    }

    public function index_autorized() // Update nama method
    {
        session()->set('previous_url', previous_url());
        $autorizedModel = new autorizedModel();
        $autorizedData['autorizedData'] = $autorizedModel->getautorizedWithRelations(); // Update nama variabel

        // Menggabungkan data dari model dan session
        $data = [
            'autorizedData' => $autorizedData['autorizedData'], // Update nama variabel
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/autorized', $data); // Update nama view
    }

    public function proses_autorized() // Update nama method
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
                    list($id_autorized, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_autorized, $id_hpa, $id_user, $id_mutu); // Update nama variabel
                }

                return redirect()->to('/autorized/index_autorized'); // Update nama route
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_autorized, $id_hpa, $id_user, $id_mutu) // Update nama variabel
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $autorizedModel = new autorizedModel();
        $pencetakanModel = new PencetakanModel();

        try {
            switch ($action) {
                    // TOMBOL MULAI PENGECEKAN
                case 'mulai':
                    $autorizedModel->updateAutorized($id_autorized, [ // Update nama method dan variabel
                        'id_user_autorized' => $id_user,
                        'status_autorized' => 'Proses Authorized',
                        'mulai_autorized' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                    // TOMBOL SELESAI PENGECEKAN
                case 'selesai':
                    $autorizedModel->updateAutorized($id_autorized, [ // Update nama method dan variabel
                        'id_user_autorized' => $id_user,
                        'status_autorized' => 'Selesai Authorized',
                        'selesai_autorized' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'reset':
                    $autorizedModel->updateAutorized($id_autorized, [
                        'id_user_autorized' => null,
                        'status_autorized' => 'Belum Authorized',
                        'mulai_autorized' => null,
                        'selesai_autorized' => null,
                    ]);
                    break;

                case 'kembalikan':
                    $autorizedModel->deleteAutorized($id_autorized);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Pemverifikasi',
                        'id_autorized' => null,
                    ]);
                    break;

                case 'lanjut':
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pencetakan']);
                    $pencetakanData = [
                        'id_hpa' => $id_hpa,
                        'status_pencetakan' => 'Belum Pencetakan',
                    ];

                    if (!$pencetakanModel->insert($pencetakanData)) { // Update nama method dan variabel
                        throw new Exception('Gagal menyimpan data pencetakan.');
                    }

                    $id_pencetakan = $pencetakanModel->getInsertID(); // Update nama variabel
                    $hpaModel->update($id_hpa, ['id_pencetakan' => $id_pencetakan]); // Update nama variabel
                    break;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function autorized_details()
    {
        // Ambil id_autorized dari parameter GET
        $id_autorized = $this->request->getGet('id_autorized');

        if ($id_autorized) {
            // Muat model autorized
            $model = new autorizedModel();

            // Ambil data autorized berdasarkan id_autorized dan relasi yang ada
            $data = $model->select(
                'autorized.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_autorized'
            )
                ->join(
                    'hpa',
                    'autorized.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'autorized.id_user_autorized = users.id_user', 'left')
                ->where('autorized.id_autorized', $id_autorized)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID autorized tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_autorized = $this->request->getPost('id_autorized');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_autorized && $id_hpa) {
            // Load model
            $autorizedModel = new AutorizedModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel autorized
            $deleteResult = $autorizedModel->deleteAutorized($id_autorized);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Pemverifikasi',
                    'id_autorized' => null,
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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data autorized.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_autorized()
    {
        $id_autorized = $this->request->getGet('id_autorized');

        if (!$id_autorized) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID autorized tidak ditemukan.');
        }

        // Ambil data autorized berdasarkan ID
        $autorizedData = $this->autorizedModel->find($id_autorized);

        if (!$autorizedData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data autorized tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'autorizedData' => $autorizedData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_autorized', $data);
    }

    public function update_autorized()
    {
        $id_autorized = $this->request->getPost('id_autorized');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_autorized_date');
        $mulai_time = $this->request->getPost('mulai_autorized_time');
        $selesai_date = $this->request->getPost('selesai_autorized_date');
        $selesai_time = $this->request->getPost('selesai_autorized_time');

        // Combine date and time into one value
        $mulai_autorized = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_autorized = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_autorized' => $this->request->getPost('id_user_autorized'),
            'status_autorized'  => $this->request->getPost('status_autorized'),
            'mulai_autorized'   => $mulai_autorized,
            'selesai_autorized' => $selesai_autorized,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->autorizedModel->update($id_autorized, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
