<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Authorized_hpa;
use App\Models\Hpa\Proses\Pencetakan_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Pewarnaan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $authorized_hpa;
    protected $pencetakan_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->authorized_hpa = new Authorized_hpa();
        $this->pencetakan_hpa = new Pencetakan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $authorizedData_hpa = $this->authorized_hpa->getauthorized_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'authorizedDatahpa' => $authorizedData_hpa,
        ];

        return view('Hpa/Proses/authorized', $data);
    }

    public function proses_authorized()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_authorized_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $this->processAction($action, $id_authorized_hpa, $id_hpa, $id_user, $id_mutu_hpa);
            }

            return redirect()->to('authorized_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_authorized_hpa, $id_hpa, $id_user, $id_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->authorized_hpa->update($id_authorized_hpa, [
                        'id_user_authorized_hpa' => $id_user,
                        'status_authorized_hpa' => 'Proses Authorized',
                        'mulai_authorized_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->authorized_hpa->update($id_authorized_hpa, [
                        'id_user_authorized_hpa' => $id_user,
                        'status_authorized_hpa' => 'Selesai Authorized',
                        'selesai_authorized_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->authorized_hpa->update($id_authorized_hpa, [
                        'id_user_authorized_hpa' => null,
                        'status_authorized_hpa' => 'Belum Authorized',
                        'mulai_authorized_hpa' => null,
                        'selesai_authorized_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pencetakan']);
                    $pencetakanData = [
                        'id_hpa'            => $id_hpa,
                        'status_pencetakan_hpa' => 'Belum Pencetakan',
                    ];
                    if (!$this->pencetakan_hpa->insert($pencetakanData)) {
                        throw new Exception('Gagal menyimpan data pencetakan.');
                    }
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function authorized_details()
    {
        // Ambil id_authorized_hpa dari parameter GET
        $id_authorized_hpa = $this->request->getGet('id_authorized_hpa');

        if ($id_authorized_hpa) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->authorized_hpa->select(
                'authorized.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_authorized'
            )
                ->join('hpa', 'authorized.id_hpa = hpa.id_hpa', 'left')
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'authorized.id_user_authorized_hpa = users.id_user', 'left')
                ->where('authorized.id_authorized_hpa', $id_authorized_hpa)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID authorized tidak ditemukan.']);
        }
    }

    public function edit_authorized()
    {
        $id_authorized_hpa = $this->request->getGet('id_authorized_hpa');

        if (!$id_authorized_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID authorized tidak ditemukan.');
        }

        // Ambil data authorized
        $authorizedData = $this->authorized_hpa->find($id_authorized_hpa);

        if (!$authorizedData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data authorized tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'authorizedData' => $authorizedData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_authorized', $data);
    }

    public function update_authorized()
    {
        $id_authorized_hpa = $this->request->getPost('id_authorized_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_authorized_hpa = $this->request->getPost('mulai_authorized_hpa_date') . ' ' . $this->request->getPost('mulai_authorized_hpa_time');
        $selesai_authorized_hpa = $this->request->getPost('selesai_authorized_hpa_date') . ' ' . $this->request->getPost('selesai_authorized_hpa_time');

        $data = [
            'id_user_authorized_hpa' => $this->request->getPost('id_user_authorized_hpa'),
            'status_authorized_hpa'  => $this->request->getPost('status_authorized_hpa'),
            'mulai_authorized_hpa'   => $mulai_authorized_hpa,
            'selesai_authorized_hpa' => $selesai_authorized_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->authorized_hpa->update($id_authorized_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('authorized/index_authorized'))->with('success', 'Data berhasil diperbarui.');
    }
}
