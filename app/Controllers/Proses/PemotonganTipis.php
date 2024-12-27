<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PemotonganTipisModel; // Update nama model
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class PemotonganTipis extends BaseController // Update nama controller
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
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
        $pemotonganTipisModel = new PemotonganTipisModel(); // Update model

        try {
            switch ($action) {
                case 'mulai':
                    // Update status_hpa menjadi 'Pemotongan Tipis' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemotongan Tipis']);
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
                        'status_pemotongan_tipis' => 'Sudah Dipotong Tipis',
                        'selesai_pemotongan_tipis' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'kembalikan':
                    $pemotonganTipisModel->updatePemotonganTipis($id_pemotongan_tipis, [
                        'id_user_pemotongan_tipis' => $id_user,
                        'status_pemotongan_tipis' => 'Belum Dipotong Tipis',
                        'mulai_pemotongan_tipis' => null,
                        'selesai_pemotongan_tipis' => null,
                    ]);
                    break;

                case 'lanjut':
                    // Update status_hpa menjadi 'Pemotongan Tipis' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemotongan Tipis']);

                    // Data untuk tabel pemotongan tipis
                    $pemotonganTipisData = [
                        'id_hpa'                   => $id_hpa,  // Menambahkan id_hpa yang baru
                        'id_user_pemotongan_tipis' => $id_user,
                        'status_pemotongan_tipis'  => 'Belum Dipotong Tipis', // Status awal
                    ];

                    // Simpan data ke tabel pemotongan tipis
                    if (!$pemotonganTipisModel->insert($pemotonganTipisData)) {
                        throw new Exception('Gagal menyimpan data pemotongan tipis.');
                    }

                    // Ambil id_pemotongan_tipis yang baru saja disimpan
                    $id_pemotongan_tipis = $pemotonganTipisModel->getInsertID();

                    // Update id_pemotongan_tipis pada tabel hpa
                    $hpaModel->update($id_hpa, ['id_pemotongan_tipis' => $id_pemotongan_tipis]);
                    break;
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }
}
