<?php

namespace App\Controllers\Proses;

use App\Controllers\BaseController;
use App\Models\ProsesModel\PenanamanModel;
use App\Models\ProsesModel\PemotonganTipisModel;
use App\Models\HpaModel;
use App\Models\UsersModel;
use App\Models\MutuModel;
use Exception;

class Penanaman extends BaseController
{
    protected $penanamanModel;
    protected $userModel;

    public function __construct()
    {
        $this->penanamanModel = new PenanamanModel();
        $this->userModel = new UsersModel();
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
                        'status_penanaman' => 'Selesai Penanaman',
                        'selesai_penanaman' => date('Y-m-d H:i:s'),
                    ]);
                    // update data mutu
                    $keseluruhan_nilai_mutu = $total_nilai_mutu + $indikator_3;
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_3' => $indikator_3,
                        'total_nilai_mutu' => $keseluruhan_nilai_mutu,
                    ]);
                    break;

                case 'reset':
                    $penanamanModel->updatePenanaman($id_penanaman, [
                        'id_user_penanaman' => null,
                        'status_penanaman' => 'Belum Penanaman',
                        'mulai_penanaman' => null,
                        'selesai_penanaman' => null,
                    ]);
                    // Konversi VARCHAR ke INTEGER
                    $mutuModel->updateMutu($id_mutu, [
                        'indikator_3' => '0',
                        'total_nilai_mutu' => '20'
                    ]);
                    break;

                    // TOMBOL KEMBALI
                case 'kembalikan':
                    $penanamanModel->deletePenanaman($id_penanaman);
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Pemprosesan',
                        'id_penanaman' => null,
                    ]);
                    break; 

                case 'lanjut':
                    // Update status_hpa menjadi 'pemotongan_tipis' pada tabel hpa
                    $hpaModel->updateHpa($id_hpa, ['status_hpa' => 'Pemotongan Tipis']);

                    // Data untuk tabel pemotongan_tipis
                    $pemotongan_tipisData = [
                        'id_hpa'              => $id_hpa,
                        'status_pemotongan_tipis'  => 'Belum Pemotongan Tipis',
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

    public function penanaman_details()
    {
        // Ambil id_penanaman dari parameter GET
        $id_penanaman = $this->request->getGet('id_penanaman');

        if ($id_penanaman) {
            // Muat model penanaman
            $model = new PenanamanModel();

            // Ambil data penanaman berdasarkan id_penanaman dan relasi yang ada
            $data = $model->select(
                'penanaman.*, 
                hpa.*, 
                patient.*, 
                users.nama_user AS nama_user_penanaman'
            )
                ->join(
                    'hpa',
                    'penanaman.id_hpa = hpa.id_hpa',
                    'left'
                ) // Relasi dengan tabel hpa
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'penanaman.id_user_penanaman = users.id_user', 'left')
                ->where('penanaman.id_penanaman', $id_penanaman)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID penanaman tidak ditemukan.']);
        }
    }

    public function delete()
    {
        // Mendapatkan data dari request
        $id_penanaman = $this->request->getPost('id_penanaman');
        $id_hpa = $this->request->getPost('id_hpa');

        if ($id_penanaman && $id_hpa) {
            // Load model
            $penanamanModel = new PenanamanModel();
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel penanaman
            $deleteResult = $penanamanModel->deletePenanaman($id_penanaman);

            // Cek apakah delete berhasil
            if ($deleteResult) {
                // Update field id_penanaman menjadi null pada tabel hpa
                $hpaModel->updateHpa($id_hpa, [
                    'status_hpa' => 'Pemprosesan',
                    'id_penanaman' => null,
                ]);

                // Selesaikan transaksi
                $db->transComplete();

                // Cek apakah transaksi berhasil
                if ($db->transStatus() === FALSE) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus atau memperbarui data.']);
                }

                return $this->response->setJSON(['success' => true]);
            } else {
                // Jika delete gagal, rollback transaksi
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data penanaman.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak valid.']);
        }
    }

    public function edit_penanaman()
    {
        $id_penanaman = $this->request->getGet('id_penanaman');

        if (!$id_penanaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penanaman tidak ditemukan.');
        }

        // Ambil data penanaman berdasarkan ID
        $penanamanData = $this->penanamanModel->find($id_penanaman);

        if (!$penanamanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penanaman tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        // Pastikan nama model benar
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'penanamanData' => $penanamanData,
            'users' => $users, // Tambahkan data users ke view
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_penanaman', $data);
    }

    public function update_penanaman()
    {
        $id_penanaman = $this->request->getPost('id_penanaman');
        // Get individual date and time inputs
        $mulai_date = $this->request->getPost('mulai_penanaman_date');
        $mulai_time = $this->request->getPost('mulai_penanaman_time');
        $selesai_date = $this->request->getPost('selesai_penanaman_date');
        $selesai_time = $this->request->getPost('selesai_penanaman_time');

        // Combine date and time into one value
        $mulai_penanaman = $mulai_date . ' ' . $mulai_time;  // Format: YYYY-MM-DD HH:MM
        $selesai_penanaman = $selesai_date . ' ' . $selesai_time;  // Format: YYYY-MM-DD HH:MM

        $data = [
            'id_user_penanaman' => $this->request->getPost('id_user_penanaman'),
            'status_penanaman'  => $this->request->getPost('status_penanaman'),
            'mulai_penanaman'   => $mulai_penanaman,
            'selesai_penanaman' => $selesai_penanaman,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->penanamanModel->update($id_penanaman, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}
