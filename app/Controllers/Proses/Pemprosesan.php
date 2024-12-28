<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PemprosesanModel; // Update nama model
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Pemprosesan extends BaseController // Update nama controller
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }

    public function index_pemprosesan() // Update method
    {
        // Mengambil id_user dan nama_user dari session
        $pemprosesanModel = new PemprosesanModel(); // Update model

        // Mengambil data HPA beserta relasinya
        $pemprosesanData['pemprosesanData'] = $pemprosesanModel->getPemprosesanWithRelations(); // Update data

        // Menggabungkan data dari model dan session
        $data = [
            'pemprosesanData' => $pemprosesanData['pemprosesanData'], // Update data
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        

        // Mengirim data ke view untuk ditampilkan
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
        $pemprosesanModel = new PemprosesanModel(); // Update model

        try {
            switch ($action) {
                case 'mulai':
                    // Update status_hpa menjadi 'Pemprosesan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemprosesan']);
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
                        'status_pemprosesan' => 'Sudah Diproses',
                        'selesai_pemprosesan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'kembalikan':
                    $pemprosesanModel->updatePemprosesan($id_pemprosesan, [
                        'id_user_pemprosesan' => $id_user,
                        'status_pemprosesan' => 'Belum Diproses',
                        'mulai_pemprosesan' => null,
                        'selesai_pemprosesan' => null,
                    ]);
                    break;

                case 'lanjut':
                    // Update status_hpa menjadi 'Pemprosesan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemprosesan']);

                    // Data untuk tabel pemprosesan
                    $pemprosesanData = [
                        'id_hpa'              => $id_hpa,  // Menambahkan id_hpa yang baru
                        'id_user_pemprosesan' => $id_user,
                        'status_pemprosesan'  => 'Belum Diproses', // Status awal
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
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }
}
