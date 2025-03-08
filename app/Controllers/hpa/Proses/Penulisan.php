<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Penulisan_hpa;
use App\Models\Hpa\Proses\Pemverifikasi_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Pewarnaan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $penulisan_hpa;
    protected $pemverifikasi_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penulisan_hpa = new Penulisan_hpa();
        $this->pemverifikasi_hpa = new Pemverifikasi_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penulisanData_hpa = $this->penulisan_hpa->getpenulisan_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penulisanDatahpa' => $penulisanData_hpa,
        ];

        return view('Hpa/Proses/penulisan', $data);
    }

    public function proses_penulisan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_penulisan_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $this->processAction($action, $id_penulisan_hpa, $id_hpa, $id_user, $id_mutu_hpa);
            }

            return redirect()->to('penulisan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penulisan_hpa, $id_hpa, $id_user, $id_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->penulisan_hpa->update($id_penulisan_hpa, [
                        'id_user_penulisan_hpa' => $id_user,
                        'status_penulisan_hpa' => 'Proses Penulisan',
                        'mulai_penulisan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->penulisan_hpa->update($id_penulisan_hpa, [
                        'id_user_penulisan_hpa' => $id_user,
                        'status_penulisan_hpa' => 'Selesai Penulisan',
                        'selesai_penulisan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->penulisan_hpa->update($id_penulisan_hpa, [
                        'id_user_penulisan_hpa' => null,
                        'status_penulisan_hpa' => 'Belum Penulisan',
                        'mulai_penulisan_hpa' => null,
                        'selesai_penulisan_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pemverifikasi']);
                    $pemverifikasiData = [
                        'id_hpa'            => $id_hpa,
                        'status_pemverifikasi_hpa' => 'Belum Pemverifikasi',
                    ];
                    if (!$this->pemverifikasi_hpa->insert($pemverifikasiData)) {
                        throw new Exception('Gagal menyimpan data pemverifikasi.');
                    }
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function penulisan_details()
    {
        // Ambil id_penulisan_hpa dari parameter GET
        $id_penulisan_hpa = $this->request->getGet('id_penulisan_hpa');

        if ($id_penulisan_hpa) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->penulisan_hpa->select(
                'penulisan.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_penulisan'
            )
                ->join('hpa', 'penulisan.id_hpa = hpa.id_hpa', 'left')
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'penulisan.id_user_penulisan_hpa = users.id_user', 'left')
                ->where('penulisan.id_penulisan_hpa', $id_penulisan_hpa)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID penulisan tidak ditemukan.']);
        }
    }

    public function edit_penulisan()
    {
        $id_penulisan_hpa = $this->request->getGet('id_penulisan_hpa');

        if (!$id_penulisan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penulisan tidak ditemukan.');
        }

        // Ambil data penulisan
        $penulisanData = $this->penulisan_hpa->find($id_penulisan_hpa);

        if (!$penulisanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penulisan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'penulisanData' => $penulisanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_penulisan', $data);
    }

    public function update_penulisan()
    {
        $id_penulisan_hpa = $this->request->getPost('id_penulisan_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_penulisan_hpa = $this->request->getPost('mulai_penulisan_hpa_date') . ' ' . $this->request->getPost('mulai_penulisan_hpa_time');
        $selesai_penulisan_hpa = $this->request->getPost('selesai_penulisan_hpa_date') . ' ' . $this->request->getPost('selesai_penulisan_hpa_time');

        $data = [
            'id_user_penulisan_hpa' => $this->request->getPost('id_user_penulisan_hpa'),
            'status_penulisan_hpa'  => $this->request->getPost('status_penulisan_hpa'),
            'mulai_penulisan_hpa'   => $mulai_penulisan_hpa,
            'selesai_penulisan_hpa' => $selesai_penulisan_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->penulisan_hpa->update($id_penulisan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penulisan/index_penulisan'))->with('success', 'Data berhasil diperbarui.');
    }
}
