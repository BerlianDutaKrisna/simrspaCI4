<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PengirisanModel;
use App\Models\ProsesModel\PemotonganModel;
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Pengirisan extends BaseController
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }

    public function index_pengirisan()
    {
        // Mengambil id_user dan nama_user dari session
        $pengirisanModel = new PengirisanModel();

        // Mengambil data HPA beserta relasinya
        $pengirisanData['pengirisanData'] = $pengirisanModel->getPengirisanWithRelations();

        // Menggabungkan data dari model dan session
        $data = [
            'pengirisanData' => $pengirisanData['pengirisanData'],
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        
        // Mengirim data ke view untuk ditampilkan
        return view('proses/pengirisan', $data);
    }

    public function proses_pengirisan()
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
                    list($id_pengirisan, $id_hpa, $id_mutu) = explode(':', $id);
                    
                    $this->processAction($action, $id_pengirisan, $id_hpa, $id_user, $id_mutu);
                }

                return redirect()->to('/pengirisan/index_pengirisan');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_pengirisan, $id_hpa, $id_user, $id_mutu)
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $pengirisanModel = new PengirisanModel();
        $pemotonganModel = new PemotonganModel();

        try {
            switch ($action) {
                    // TOMBOL MULAI PENGECEKAN
                case 'mulai':
                    // Update status_hpa menjadi 'Pengirisan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pengirisan']);
                    // Update data pengirisan
                    $pengirisanModel->updatePengirisan($id_pengirisan, [
                        'id_user_pengirisan' => $id_user,
                        'status_pengirisan' => 'Proses Pengirisan',
                        'mulai_pengirisan' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                    // TOMBOL SELESAI PENGECEKAN
                case 'selesai':
                    // Update data pengirisan ketika selesai
                    $pengirisanModel->updatePengirisan($id_pengirisan, [
                        'id_user_pengirisan' => $id_user,  
                        'status_pengirisan' => 'Sudah Diiris', 
                        'selesai_pengirisan' => date('Y-m-d H:i:s'), 
                    ]);
                    
                    break;
                    // TOMBOL KEMBALIKAN PENGECEKAN
                case 'kembalikan':
                    $pengirisanModel->updatePengirisan($id_pengirisan, [
                        'id_user_pengirisan' => $id_user,  
                        'status_pengirisan' => 'Belum Diiris', 
                        'mulai_pengirisan' => null,
                        'selesai_pengirisan' => null, 
                    ]);
                    break;

                case 'lanjut':
                    // Update status_hpa menjadi 'pemotongan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'pemotongan']);

                    // Data untuk tabel pemotongan
                    $pemotonganData = [
                        'id_hpa'              => $id_hpa,  // Menambahkan id_hpa yang baru
                        'id_user_pemotongan'    => $id_user,
                        'status_pemotongan'     => 'Belum Diiris', // Status awal
                    ];

                    // Simpan data ke tabel pemotongan
                    if (!$pemotonganModel->insert($pemotonganData)) {
                        throw new Exception('Gagal menyimpan data pemotongan.');
                    }

                    // Ambil id_pemotongan yang baru saja disimpan
                    $id_pemotongan = $pemotonganModel->getInsertID();

                    // Update id_pemotongan pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_pemotongan' => $id_pemotongan]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            // Anda bisa melempar exception atau memberikan pesan error yang lebih spesifik
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }
}
