<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PewarnaanModel; // Update nama model
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Pewarnaan extends BaseController // Update nama controller
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }

    public function index_pewarnaan() // Update method
    {
        // Mengambil id_user dan nama_user dari session
        $pewarnaanModel = new PewarnaanModel(); // Update model

        // Mengambil data HPA beserta relasinya
        $pewarnaanData['pewarnaanData'] = $pewarnaanModel->getPewarnaanWithRelations(); // Update data

        // Menggabungkan data dari model dan session
        $data = [
            'pewarnaanData' => $pewarnaanData['pewarnaanData'], // Update data
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/pewarnaan', $data); // Update view
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
        $pewarnaanModel = new PewarnaanModel(); // Update model

        try {
            switch ($action) {
                case 'mulai':
                    // Update status_hpa menjadi 'Pewarnaan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pewarnaan']);
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
                        'status_pewarnaan' => 'Sudah Diwarnai',
                        'selesai_pewarnaan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'kembalikan':
                    $pewarnaanModel->updatePewarnaan($id_pewarnaan, [
                        'id_user_pewarnaan' => $id_user,
                        'status_pewarnaan' => 'Belum Diwarnai',
                        'mulai_pewarnaan' => null,
                        'selesai_pewarnaan' => null,
                    ]);
                    break;

                case 'lanjut':
                    // Update status_hpa menjadi 'Pewarnaan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pewarnaan']);

                    // Data untuk tabel pewarnaan
                    $pewarnaanData = [
                        'id_hpa'                   => $id_hpa,  // Menambahkan id_hpa yang baru
                        'id_user_pewarnaan'        => $id_user,
                        'status_pewarnaan'         => 'Belum Diwarnai', // Status awal
                    ];

                    // Simpan data ke tabel pewarnaan
                    if (!$pewarnaanModel->insert($pewarnaanData)) {
                        throw new Exception('Gagal menyimpan data pewarnaan.');
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
}
