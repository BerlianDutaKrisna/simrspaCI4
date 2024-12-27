<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PemverifikasiModel; // Update nama model
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Pemverifikasi extends BaseController // Update nama controller
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }

    public function index_pemverifikasi() // Update nama method
    {
        // Mengambil id_user dan nama_user dari session
        $pemverifikasiModel = new PemverifikasiModel(); // Update nama model

        // Mengambil data HPA beserta relasinya
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
        $pemverifikasiModel = new PemverifikasiModel(); // Update nama model

        try {
            switch ($action) {
                    // TOMBOL MULAI PENGECEKAN
                case 'mulai':
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemverifikasi']); // Update status
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
                        'status_pemverifikasi' => 'Sudah Diverifikasi',
                        'selesai_pemverifikasi' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                    // TOMBOL KEMBALIKAN PENGECEKAN
                case 'kembalikan':
                    $pemverifikasiModel->updatePemverifikasi($id_pemverifikasi, [ // Update nama method dan variabel
                        'id_user_pemverifikasi' => $id_user,
                        'status_pemverifikasi' => 'Belum Diverifikasi',
                        'mulai_pemverifikasi' => null,
                        'selesai_pemverifikasi' => null,
                    ]);
                    break;

                case 'lanjut':
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemverifikasi']); // Update status

                    $pemverifikasiData = [
                        'id_hpa' => $id_hpa,
                        'id_user_pemverifikasi' => $id_user,
                        'status_pemverifikasi' => 'Belum Diverifikasi',
                    ];

                    if (!$pemverifikasiModel->insert($pemverifikasiData)) { // Update nama method dan variabel
                        throw new Exception('Gagal menyimpan data pemverifikasi.');
                    }

                    $id_pemverifikasi = $pemverifikasiModel->getInsertID(); // Update nama variabel
                    $hpaModel->update($id_hpa, ['id_pemverifikasi' => $id_pemverifikasi]); // Update nama variabel
                    break;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }
}
