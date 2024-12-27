<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PenerimaanModel;
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Penerimaan extends BaseController
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }
    public function index_penerimaan()
    {
        // Mengambil id_user dan nama_user dari session
        $penerimaanModel = new PenerimaanModel();

        // Mengambil data HPA beserta relasinya
        $penerimaanData['penerimaanData'] = $penerimaanModel->getPenerimaanWithRelations();

        // Menggabungkan data dari model dan session
        $data = [
            'penerimaanData' => $penerimaanData['penerimaanData'],
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        // Mengirim data ke view untuk ditampilkan
        return view('proses/penerimaan', $data);
    }
    public function proses_penerimaan()
    {
        // Models
        $hpaModel = new HpaModel();
        $penerimaanModel = new PenerimaanModel();

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
                    list($id_penerimaan, $id_hpa, $id_mutu) = explode(':', $id);
                    $this->processAction($action, $id_penerimaan, $id_hpa, $id_user, $id_mutu, $this->request->getPost('indikator_1'), $this->request->getPost('indikator_2'));
                }

                return redirect()->to('/penerimaan/index_penerimaan');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_penerimaan, $id_hpa, $id_user, $id_mutu, $indikator_1, $indikator_2)
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $penerimaanModel = new PenerimaanModel();
        $mutuModel = new MutuModel();

        try {
            switch ($action) {
                case 'mulai_penerimaan':
                    // Update status_hpa menjadi 'Penerimaan' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Penerimaan']);
                    // Update data penerimaan
                    $penerimaanModel->updatePenerimaan($id_penerimaan, [
                        'id_user_penerimaan' => $id_user,
                        'status_penerimaan' => 'Proses Pemeriksaan',
                        'mulai_penerimaan' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'selesai_penerimaan':
                    // Update data penerimaan ketika selesai
                    $penerimaanModel->updatePenerimaan($id_penerimaan, [
                        'id_user_penerimaan' => $id_user,  // Menggunakan id_user dari session
                        'status_penerimaan' => 'Sudah Diperiksa', // Status menjadi 'Sudah Diperiksa'
                        'selesai_penerimaan' => date('Y-m-d H:i:s'), // Menggunakan waktu lokal Asia/Jakarta
                    ]);
                    // update data mutu
                    $total_nilai_mutu = 0; // Inisialisasi nilai total_nilai_mutu

                    // Pastikan $indikator_1 dan $indikator_2 adalah array atau memiliki nilai yang valid
                    // Periksa jika indikator_1 tidak terkirim atau bernilai null
                    $nilai_indikator_1 = isset($indikator_1[$id_mutu]) ? 10 : 0; // Jika ada, beri nilai 10, jika tidak 0
                    $nilai_indikator_2 = isset($indikator_2[$id_mutu]) ? 10 : 0; // Jika ada, beri nilai 10, jika tidak 0

                    // Update nilai indikator_1 dan indikator_2, jika ada
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_1' => $nilai_indikator_1,
                        'indikator_2' => $nilai_indikator_2
                    ]);

                    // Tambahkan nilai dari indikator_1 dan indikator_2 ke total_nilai_mutu
                    $total_nilai_mutu = $nilai_indikator_1 + $nilai_indikator_2;

                    // Update total_nilai_mutu pada tabel mutu
                    $mutuModel->updateMutu($id_mutu, ['total_nilai_mutu' => $total_nilai_mutu]);
                    break;

                    // Tambahkan aksi lain jika diperlukan
            }
        } catch (\Exception $e) {
            // Tangani error yang terjadi selama proses action
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            // Anda bisa melempar exception atau memberikan pesan error yang lebih spesifik
            throw new \Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }
}
