<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PenulisanModel; // Update nama model
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Penulisan extends BaseController // Update nama controller
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
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
        $penulisanModel = new PenulisanModel(); // Update nama model

        try {
            switch ($action) {
                case 'mulai':
                    // Update status_hpa menjadi 'Penulisan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Penulisan']);
                    // Update data penulisan
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
                        'status_penulisan' => 'Sudah Ditulis',
                        'selesai_penulisan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'kembalikan':
                    $penulisanModel->updatePenulisan($id_penulisan, [
                        'id_user_penulisan' => $id_user,
                        'status_penulisan' => 'Belum Ditulis',
                        'mulai_penulisan' => null,
                        'selesai_penulisan' => null,
                    ]);
                    break;

                case 'lanjut':
                    // Update status_hpa menjadi 'Penulisan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Penulisan']);

                    // Data untuk tabel penulisan
                    $penulisanData = [
                        'id_hpa'                 => $id_hpa,  // Menambahkan id_hpa yang baru
                        'id_user_penulisan'      => $id_user,
                        'status_penulisan'       => 'Belum Ditulis', // Status awal
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
}
