<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Pencetakan_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Pencetakan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $pencetakan_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pencetakan_hpa = new Pencetakan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pencetakanData_hpa = $this->pencetakan_hpa->getpencetakan_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pencetakanDatahpa' => $pencetakanData_hpa,
        ];
        
        return view('Hpa/Proses/pencetakan', $data);
    }

    public function proses_pencetakan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pencetakan_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $this->processAction($action, $id_pencetakan_hpa, $id_hpa, $id_user, $id_mutu_hpa);
            }

            return redirect()->to('pencetakan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pencetakan_hpa, $id_hpa, $id_user, $id_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pencetakan_hpa->update($id_pencetakan_hpa, [
                        'id_user_pencetakan_hpa' => $id_user,
                        'status_pencetakan_hpa' => 'Proses Pencetakan',
                        'mulai_pencetakan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pencetakan_hpa->update($id_pencetakan_hpa, [
                        'id_user_pencetakan_hpa' => $id_user,
                        'status_pencetakan_hpa' => 'Selesai Pencetakan',
                        'selesai_pencetakan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pencetakan_hpa->update($id_pencetakan_hpa, [
                        'id_user_pencetakan_hpa' => null,
                        'status_pencetakan_hpa' => 'Belum Pencetakan',
                        'mulai_pencetakan_hpa' => null,
                        'selesai_pencetakan_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Selesai']);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pencetakan_details()
    {
        // Ambil id_pencetakan_hpa dari parameter GET
        $id_pencetakan_hpa = $this->request->getGet('id_pencetakan_hpa');

        if ($id_pencetakan_hpa) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pencetakan_hpa->select(
                'pencetakan.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_pencetakan'
            )
                ->join('hpa', 'pencetakan.id_hpa = hpa.id_hpa', 'left')
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pencetakan.id_user_pencetakan_hpa = users.id_user', 'left')
                ->where('pencetakan.id_pencetakan_hpa', $id_pencetakan_hpa)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pencetakan tidak ditemukan.']);
        }
    }

    public function edit_pencetakan()
    {
        $id_pencetakan_hpa = $this->request->getGet('id_pencetakan_hpa');

        if (!$id_pencetakan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pencetakan tidak ditemukan.');
        }

        // Ambil data pencetakan
        $pencetakanData = $this->pencetakan_hpa->find($id_pencetakan_hpa);

        if (!$pencetakanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pencetakan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pencetakanData' => $pencetakanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pencetakan', $data);
    }

    public function update_pencetakan()
    {
        $id_pencetakan_hpa = $this->request->getPost('id_pencetakan_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_pencetakan_hpa = $this->request->getPost('mulai_pencetakan_hpa_date') . ' ' . $this->request->getPost('mulai_pencetakan_hpa_time');
        $selesai_pencetakan_hpa = $this->request->getPost('selesai_pencetakan_hpa_date') . ' ' . $this->request->getPost('selesai_pencetakan_hpa_time');

        $data = [
            'id_user_pencetakan_hpa' => $this->request->getPost('id_user_pencetakan_hpa'),
            'status_pencetakan_hpa'  => $this->request->getPost('status_pencetakan_hpa'),
            'mulai_pencetakan_hpa'   => $mulai_pencetakan_hpa,
            'selesai_pencetakan_hpa' => $selesai_pencetakan_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pencetakan_hpa->update($id_pencetakan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pencetakan/index_pencetakan'))->with('success', 'Data berhasil diperbarui.');
    }
}
