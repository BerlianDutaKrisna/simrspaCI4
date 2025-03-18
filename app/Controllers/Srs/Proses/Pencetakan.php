<?php

namespace App\Controllers\Srs\Proses;

use App\Controllers\BaseController;
use App\Models\Srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\Pencetakan_srs;
use App\Models\Srs\Mutu_srs;
use Exception;

class Pencetakan extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $pencetakan_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pencetakan_srs = new Pencetakan_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pencetakanData_srs = $this->pencetakan_srs->getpencetakan_srs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pencetakanDatasrs' => $pencetakanData_srs,
        ];

        return view('srs/Proses/pencetakan', $data);
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
                list($id_pencetakan_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                $this->processAction($action, $id_pencetakan_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('pencetakan_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pencetakan_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pencetakan_srs->update($id_pencetakan_srs, [
                        'id_user_pencetakan_srs' => $id_user,
                        'status_pencetakan_srs' => 'Proses Pencetakan',
                        'mulai_pencetakan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pencetakan_srs->update($id_pencetakan_srs, [
                        'id_user_pencetakan_srs' => $id_user,
                        'status_pencetakan_srs' => 'Selesai Pencetakan',
                        'selesai_pencetakan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pencetakan_srs->update($id_pencetakan_srs, [
                        'id_user_pencetakan_srs' => null,
                        'status_pencetakan_srs' => 'Belum Pencetakan',
                        'mulai_pencetakan_srs' => null,
                        'selesai_pencetakan_srs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Selesai']);
                    break;
                case 'kembalikan':
                    $this->pencetakan_srs->delete($id_pencetakan_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Authorized',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pencetakan_details()
    {
        // Ambil id_pencetakan_srs dari parameter GET
        $id_pencetakan_srs = $this->request->getGet('id_pencetakan_srs');

        if ($id_pencetakan_srs) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pencetakan_srs->select(
                'pencetakan.*, 
            srs.*, 
            patient.*, 
            users.nama_user AS nama_user_pencetakan'
            )
                ->join('srs', 'pencetakan.id_srs = srs.id_srs', 'left')
                ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pencetakan.id_user_pencetakan_srs = users.id_user', 'left')
                ->where('pencetakan.id_pencetakan_srs', $id_pencetakan_srs)
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
        $id_pencetakan_srs = $this->request->getGet('id_pencetakan_srs');

        if (!$id_pencetakan_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pencetakan tidak ditemukan.');
        }

        // Ambil data pencetakan
        $pencetakanData = $this->pencetakan_srs->find($id_pencetakan_srs);

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
        $id_pencetakan_srs = $this->request->getPost('id_pencetakan_srs');

        // Gabungkan input tanggal dan waktu
        $mulai_pencetakan_srs = $this->request->getPost('mulai_pencetakan_srs_date') . ' ' . $this->request->getPost('mulai_pencetakan_srs_time');
        $selesai_pencetakan_srs = $this->request->getPost('selesai_pencetakan_srs_date') . ' ' . $this->request->getPost('selesai_pencetakan_srs_time');

        $data = [
            'id_user_pencetakan_srs' => $this->request->getPost('id_user_pencetakan_srs'),
            'status_pencetakan_srs'  => $this->request->getPost('status_pencetakan_srs'),
            'mulai_pencetakan_srs'   => $mulai_pencetakan_srs,
            'selesai_pencetakan_srs' => $selesai_pencetakan_srs,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pencetakan_srs->update($id_pencetakan_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pencetakan/index_pencetakan'))->with('success', 'Data berhasil diperbarui.');
    }
}
