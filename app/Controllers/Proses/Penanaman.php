<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PenanamanModel;
use App\Models\ProsesModel\PemotonganTipisModel;
use App\Models\HpaModel;
use App\Models\MutuModel;
use Exception;

class Penanaman extends BaseController // Update nama controller
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }

    public function index_penanaman() // Update method
    {
        // Mengambil id_user dan nama_user dari session
        $penanamanModel = new PenanamanModel(); // Update model

        // Mengambil data HPA beserta relasinya
        $penanamanData['penanamanData'] = $penanamanModel->getPenanamanWithRelations(); // Update data

        // Menggabungkan data dari model dan session
        $data = [
            'penanamanData' => $penanamanData['penanamanData'], // Update data
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('proses/penanaman', $data); // Update view
    }

    public function proses_penanaman() // Update method
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
                    list($id_penanaman, $id_hpa, $id_mutu) = explode(':', $id);
                    $indikator_3 = (string) ($this->request->getPost('indikator_3') ?? '0');
                    $total_nilai_mutu = $this->request->getPost('total_nilai_mutu');
                    $this->processAction($action, $id_penanaman, $id_hpa, $id_user, $id_mutu, $indikator_3, $total_nilai_mutu); // Update method call
                }

                return redirect()->to('/penanaman/index_penanaman'); // Update URL
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Process action based on the action value
    private function processAction($action, $id_penanaman, $id_hpa, $id_user, $id_mutu, $indikator_3, $total_nilai_mutu) // Update parameter
    {
        // Set zona waktu Indonesia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $hpaModel = new HpaModel();
        $penanamanModel = new PenanamanModel();
        $pemotongan_tipisModel = new PemotonganTipisModel();
        $mutuModel = new MutuModel();

        try {
            switch ($action) {
                case 'mulai':
                    // Update data penanaman
                    $penanamanModel->updatePenanaman($id_penanaman, [
                        'id_user_penanaman' => $id_user,
                        'status_penanaman' => 'Proses Penanaman',
                        'mulai_penanaman' => date('Y-m-d H:i:s'),
                    ]);
                    break;

                case 'selesai':
                    // Update data penanaman ketika selesai
                    $penanamanModel->updatePenanaman($id_penanaman, [
                        'id_user_penanaman' => $id_user,
                        'status_penanaman' => 'Sudah Ditanam',
                        'selesai_penanaman' => date('Y-m-d H:i:s'),
                    ]);
                    // update data mutu
                    $keseluruhan_nilai_mutu = $total_nilai_mutu + $indikator_3;
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_3' => $indikator_3,
                        'total_nilai_mutu' => $keseluruhan_nilai_mutu,
                    ]);
                    break;

                case 'kembalikan':
                    $penanamanModel->updatePenanaman($id_penanaman, [
                        'id_user_penanaman' => null,
                        'status_penanaman' => 'Belum Ditanam',
                        'mulai_penanaman' => null,
                        'selesai_penanaman' => null,
                    ]);
                    // Konversi VARCHAR ke INTEGER
                    $total_nilai_mutu_int = (int)$total_nilai_mutu;
                    $keseluruhan_nilai_mutu = $total_nilai_mutu_int - 10;
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_3' => '0',
                        'total_nilai_mutu' => $keseluruhan_nilai_mutu,
                    ]);
                    break;

                case 'lanjut':
                    // Update status_hpa menjadi 'pemotongan_tipis' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemotongan Tipis']);

                    // Data untuk tabel pemotongan_tipis
                    $pemotongan_tipisData = [
                        'id_hpa'              => $id_hpa,
                        'status_pemotongan_tipis'  => 'Belum Dipotong Tipis',
                    ];

                    // Simpan data ke tabel pemotongan_tipis
                    if (!$pemotongan_tipisModel->insert($pemotongan_tipisData)) {
                        throw new Exception('Gagal menyimpan data pemotongan_tipis.');
                    }

                    // Ambil id_pemotongan_tipis yang baru saja disimpan
                    $id_pemotongan_tipis = $pemotongan_tipisModel->getInsertID();

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
