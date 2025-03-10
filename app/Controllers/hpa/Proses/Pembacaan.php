<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Pembacaan_hpa;
use App\Models\Hpa\Proses\Penulisan_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Pembacaan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $pembacaan_hpa;
    protected $penulisan_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pembacaan_hpa = new Pembacaan_hpa();
        $this->penulisan_hpa = new Penulisan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pembacaanData_hpa = $this->pembacaan_hpa->getpembacaan_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pembacaanDatahpa' => $pembacaanData_hpa,
        ];
        dd($data);
        return view('Hpa/Proses/pembacaan', $data);
    }

    public function proses_pembacaan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pembacaan_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $this->processAction($action, $id_pembacaan_hpa, $id_hpa, $id_user, $id_mutu_hpa);
            }

            return redirect()->to('pembacaan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pembacaan_hpa, $id_hpa, $id_user, $id_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pembacaan_hpa->update($id_pembacaan_hpa, [
                        'id_user_pembacaan_hpa' => $id_user,
                        'status_pembacaan_hpa' => 'Proses Pembacaan',
                        'mulai_pembacaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pembacaan_hpa->update($id_pembacaan_hpa, [
                        'id_user_pembacaan_hpa' => $id_user,
                        'status_pembacaan_hpa' => 'Selesai Pembacaan',
                        'selesai_pembacaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pembacaan_hpa->update($id_pembacaan_hpa, [
                        'id_user_pembacaan_hpa' => null,
                        'status_pembacaan_hpa' => 'Belum Pembacaan',
                        'mulai_pembacaan_hpa' => null,
                        'selesai_pembacaan_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Penulisan']);
                    $penulisanData = [
                        'id_hpa'            => $id_hpa,
                        'status_penulisan_hpa' => 'Belum Penulisan',
                    ];
                    if (!$this->penulisan_hpa->insert($penulisanData)) {
                        throw new Exception('Gagal menyimpan data penulisan.');
                    }
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pembacaan_details()
    {
        // Ambil id_pembacaan_hpa dari parameter GET
        $id_pembacaan_hpa = $this->request->getGet('id_pembacaan_hpa');

        if ($id_pembacaan_hpa) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pembacaan_hpa->select(
                'pembacaan.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_pembacaan'
            )
                ->join('hpa', 'pembacaan.id_hpa = hpa.id_hpa', 'left')
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pembacaan.id_user_pembacaan_hpa = users.id_user', 'left')
                ->where('pembacaan.id_pembacaan_hpa', $id_pembacaan_hpa)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pembacaan tidak ditemukan.']);
        }
    }

    public function edit_pembacaan()
    {
        $id_pembacaan_hpa = $this->request->getGet('id_pembacaan_hpa');

        if (!$id_pembacaan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pembacaan tidak ditemukan.');
        }

        // Ambil data pembacaan
        $pembacaanData = $this->pembacaan_hpa->find($id_pembacaan_hpa);

        if (!$pembacaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pembacaan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pembacaanData' => $pembacaanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pembacaan', $data);
    }

    public function update_pembacaan()
    {
        $id_pembacaan_hpa = $this->request->getPost('id_pembacaan_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_pembacaan_hpa = $this->request->getPost('mulai_pembacaan_hpa_date') . ' ' . $this->request->getPost('mulai_pembacaan_hpa_time');
        $selesai_pembacaan_hpa = $this->request->getPost('selesai_pembacaan_hpa_date') . ' ' . $this->request->getPost('selesai_pembacaan_hpa_time');

        $data = [
            'id_user_pembacaan_hpa' => $this->request->getPost('id_user_pembacaan_hpa'),
            'status_pembacaan_hpa'  => $this->request->getPost('status_pembacaan_hpa'),
            'mulai_pembacaan_hpa'   => $mulai_pembacaan_hpa,
            'selesai_pembacaan_hpa' => $selesai_pembacaan_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pembacaan_hpa->update($id_pembacaan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pembacaan/index_pembacaan'))->with('success', 'Data berhasil diperbarui.');
    }
}
