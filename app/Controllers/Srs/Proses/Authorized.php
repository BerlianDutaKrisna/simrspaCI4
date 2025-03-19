<?php

namespace App\Controllers\Srs\Proses;

use App\Controllers\BaseController;
use App\Models\Srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\Authorized_srs;
use App\Models\Srs\Proses\Pencetakan_srs;
use App\Models\Srs\Mutu_srs;
use Exception;

class Authorized extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $authorized_srs;
    protected $pencetakan_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->authorized_srs = new Authorized_srs();
        $this->pencetakan_srs = new Pencetakan_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $authorizedData_srs = $this->authorized_srs->getauthorized_srs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'authorizedDatasrs' => $authorizedData_srs,
        ];
        
        return view('srs/Proses/authorized', $data);
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
                list($id_authorized_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                $this->processAction($action, $id_authorized_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('authorized_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_authorized_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->authorized_srs->update($id_authorized_srs, [
                        'id_user_authorized_srs' => $id_user,
                        'status_authorized_srs' => 'Proses Authorized',
                        'mulai_authorized_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->authorized_srs->update($id_authorized_srs, [
                        'id_user_authorized_srs' => $id_user,
                        'status_authorized_srs' => 'Selesai Authorized',
                        'selesai_authorized_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->authorized_srs->update($id_authorized_srs, [
                        'id_user_authorized_srs' => null,
                        'status_authorized_srs' => 'Belum Authorized',
                        'mulai_authorized_srs' => null,
                        'selesai_authorized_srs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Pencetakan']);
                    $pencetakanData = [
                        'id_srs'            => $id_srs,
                        'status_pencetakan_srs' => 'Belum Pencetakan',
                    ];
                    if (!$this->pencetakan_srs->insert($pencetakanData)) {
                        throw new Exception('Gagal menyimpan data pencetakan.');
                    }
                    break;
                case 'kembalikan':
                    $this->authorized_srs->delete($id_authorized_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Pemverifikasi',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function authorized_details()
    {
        // Ambil id_authorized_srs dari parameter GET
        $id_authorized_srs = $this->request->getGet('id_authorized_srs');

        if ($id_authorized_srs) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->authorized_srs->select(
                'authorized.*, 
            srs.*, 
            patient.*, 
            users.nama_user AS nama_user_authorized'
            )
                ->join('srs', 'authorized.id_srs = srs.id_srs', 'left')
                ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'authorized.id_user_authorized_srs = users.id_user', 'left')
                ->where('authorized.id_authorized_srs', $id_authorized_srs)
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
        $id_authorized_srs = $this->request->getGet('id_authorized_srs');

        if (!$id_authorized_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID authorized tidak ditemukan.');
        }

        // Ambil data authorized
        $authorizedData = $this->authorized_srs->find($id_authorized_srs);

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
        $id_authorized_srs = $this->request->getPost('id_authorized_srs');

        // Gabungkan input tanggal dan waktu
        $mulai_authorized_srs = $this->request->getPost('mulai_authorized_srs_date') . ' ' . $this->request->getPost('mulai_authorized_srs_time');
        $selesai_authorized_srs = $this->request->getPost('selesai_authorized_srs_date') . ' ' . $this->request->getPost('selesai_authorized_srs_time');

        $data = [
            'id_user_authorized_srs' => $this->request->getPost('id_user_authorized_srs'),
            'status_authorized_srs'  => $this->request->getPost('status_authorized_srs'),
            'mulai_authorized_srs'   => $mulai_authorized_srs,
            'selesai_authorized_srs' => $selesai_authorized_srs,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->authorized_srs->update($id_authorized_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('authorized/index_authorized'))->with('success', 'Data berhasil diperbarui.');
    }
}
