<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PencetakanModel; // Update nama model
use App\Models\HpaModel;
use App\Models\UsersModel;
use Exception;

class Pencetakan extends BaseController
{
    protected $pencetakanModel;
    protected $userModel;

    public function __construct()
    {
        $this->pencetakanModel = new PencetakanModel();
        $this->userModel = new UsersModel();
        session()->set('previous_url', previous_url());
    }

    public function index_pencetakan() // Update nama method
    {
        session()->set('previous_url', previous_url());
        $pencetakanModel = new PencetakanModel(); // Update nama model
        $pencetakanData['pencetakanData'] = $pencetakanModel->getPencetakanWithRelations(); // Update nama variabel

        // Menggabungkan data dari model dan session
        $data = [
            'pencetakanData' => $pencetakanData['pencetakanData'], // Update nama variabel
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/pencetakan', $data); // Update nama view
    }

    public function proses_pencetakan() // Update nama method
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
                    list($id_pencetakan, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_pencetakan, $id_hpa, $id_user, $id_mutu); // Update nama variabel
                }

                return redirect()->to('/pencetakan/index_pencetakan'); // Update nama route
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pencetakan, $id_hpa, $id_user, $id_mutu) // Update nama variabel
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pencetakanModel = new PencetakanModel(); // Update nama model

        try {
            switch ($action) {
                    // TOMBOL MULAI PENGECEKAN
                case 'mulai':
                    $pencetakanModel->updatePencetakan($id_pencetakan, [ // Update nama method dan variabel
                        'id_user_pencetakan' => $id_user,
                        'status_pencetakan' => 'Proses Pencetakan',
                        'mulai_pencetakan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                    // TOMBOL SELESAI PENGECEKAN
                case 'selesai':
                    $pencetakanModel->updatePencetakan($id_pencetakan, [ // Update nama method dan variabel
                        'id_user_pencetakan' => $id_user,
                        'status_pencetakan' => 'Selesai Pencetakan',
                        'selesai_pencetakan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                    // TOMBOL KEMBALIKAN PENGECEKAN
                case 'reset':
                    $pencetakanModel->updatePencetakan($id_pencetakan, [ // Update nama method dan variabel
                        'id_user_pencetakan' => null,
                        'status_pencetakan' => 'Belum Pencetakan',
                        'mulai_pencetakan' => null,
                        'selesai_pencetakan' => null,
                    ]);
                    break;

                case 'kembalikan':
                    $pencetakanModel->deletePencetakan($id_pencetakan);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Autorized',
                        'id_pencetakan' => null,
                    ]);
                    break;

                case 'lanjut':
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Selesai']); // Update status
                    break;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pencetakan_details()
    {
        // Ambil id_pencetakan dari parameter GET
        $id_pencetakan = $this->request->getGet('id_pencetakan');

        if ($id_pencetakan) {
            // Muat model pencetakan
            $model = new PencetakanModel();

            // Ambil data pencetakan berdasarkan id_pencetakan dan relasi yang ada
            $data = $model->select(
                'pencetakan.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_pencetakan'
            )
                ->join(
                    'hpa',
                    'pencetakan.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pencetakan.id_user_pencetakan = users.id_user', 'left')
                ->where('pencetakan.id_pencetakan', $id_pencetakan)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pencetakan tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_pencetakan = $this->request->getPost('id_pencetakan');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_pencetakan && $id_hpa) {
            // Load model
            $pencetakanModel = new PencetakanModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel pencetakan
            $deleteResult = $pencetakanModel->deletePencetakan($id_pencetakan);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Autorized',
                    'id_pencetakan' => null,
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
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data pencetakan.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_pencetakan()
    {
        $id_pencetakan = $this->request->getGet('id_pencetakan');

        if (!$id_pencetakan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pencetakan tidak ditemukan.');
        }

        // Ambil data pencetakan berdasarkan ID
        $pencetakanData = $this->pencetakanModel->find($id_pencetakan);

        if (!$pencetakanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pencetakan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pencetakanData' => $pencetakanData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pencetakan', $data);
    }

    public function update_pencetakan()
    {
        $id_pencetakan = $this->request->getPost('id_pencetakan');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_pencetakan_date');
        $mulai_time = $this->request->getPost('mulai_pencetakan_time');
        $selesai_date = $this->request->getPost('selesai_pencetakan_date');
        $selesai_time = $this->request->getPost('selesai_pencetakan_time');

        // Combine date and time into one value
        $mulai_pencetakan = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_pencetakan = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_pencetakan' => $this->request->getPost('id_user_pencetakan'),
            'status_pencetakan'  => $this->request->getPost('status_pencetakan'),
            'mulai_pencetakan'   => $mulai_pencetakan,
            'selesai_pencetakan' => $selesai_pencetakan,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pencetakanModel->update($id_pencetakan, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
