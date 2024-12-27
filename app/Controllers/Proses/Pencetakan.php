<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PencetakanModel; // Update nama model
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Pencetakan extends BaseController // Update nama controller
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }

    public function index_pencetakan() // Update nama method
    {
        // Mengambil id_user dan nama_user dari session
        $pencetakanModel = new PencetakanModel(); // Update nama model

        // Mengambil data HPA beserta relasinya
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
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pencetakan']); // Update status
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
                        'status_pencetakan' => 'Sudah Dicetak',
                        'selesai_pencetakan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                    // TOMBOL KEMBALIKAN PENGECEKAN
                case 'kembalikan':
                    $pencetakanModel->updatePencetakan($id_pencetakan, [ // Update nama method dan variabel
                        'id_user_pencetakan' => $id_user,
                        'status_pencetakan' => 'Belum Dicetak',
                        'mulai_pencetakan' => null,
                        'selesai_pencetakan' => null,
                    ]);
                    break;

                case 'lanjut':
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pencetakan']); // Update status

                    $pencetakanData = [
                        'id_hpa' => $id_hpa,
                        'id_user_pencetakan' => $id_user,
                        'status_pencetakan' => 'Belum Dicetak',
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
}
