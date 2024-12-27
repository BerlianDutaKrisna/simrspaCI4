<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PembacaanModel; // Update nama model
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Pembacaan extends BaseController // Update nama controller
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }

    public function index_pembacaan() // Update method
    {
        // Mengambil id_user dan nama_user dari session
        $pembacaanModel = new PembacaanModel(); // Update model

        // Mengambil data HPA beserta relasinya
        $pembacaanData['pembacaanData'] = $pembacaanModel->getPembacaanWithRelations(); // Update data

        // Menggabungkan data dari model dan session
        $data = [
            'pembacaanData' => $pembacaanData['pembacaanData'], // Update data
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/pembacaan', $data); // Update view
    }

    public function proses_pembacaan() // Update method
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
                    list($id_pembacaan, $id_hpa, $id_mutu) = explode(':', $id);

                    $this->processAction($action, $id_pembacaan, $id_hpa, $id_user, $id_mutu); // Update method call
                }

                return redirect()->to('/pembacaan/index_pembacaan'); // Update URL
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pembacaan, $id_hpa, $id_user, $id_mutu) // Update parameter
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pembacaanModel = new PembacaanModel(); // Update model

        try {
            switch ($action) {
                case 'mulai':
                    // Update status_hpa menjadi 'Pembacaan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pembacaan']);
                    // Update data pembacaan
                    $pembacaanModel->updatePembacaan($id_pembacaan, [
                        'id_user_pembacaan' => $id_user,
                        'status_pembacaan' => 'Proses Pembacaan',
                        'mulai_pembacaan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'selesai':
                    // Update data pembacaan ketika selesai
                    $pembacaanModel->updatePembacaan($id_pembacaan, [
                        'id_user_pembacaan' => $id_user,
                        'status_pembacaan' => 'Sudah Dibaca',
                        'selesai_pembacaan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'kembalikan':
                    $pembacaanModel->updatePembacaan($id_pembacaan, [
                        'id_user_pembacaan' => $id_user,
                        'status_pembacaan' => 'Belum Dibaca',
                        'mulai_pembacaan' => null,
                        'selesai_pembacaan' => null,
                    ]);
                    break;

                case 'lanjut':
                    // Update status_hpa menjadi 'Pembacaan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pembacaan']);

                    // Data untuk tabel pembacaan
                    $pembacaanData = [
                        'id_hpa'                   => $id_hpa,  // Menambahkan id_hpa yang baru
                        'id_user_pembacaan'        => $id_user,
                        'status_pembacaan'         => 'Belum Dibaca', // Status awal
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
}