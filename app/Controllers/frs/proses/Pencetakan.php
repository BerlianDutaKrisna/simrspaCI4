<?php

namespace App\Controllers\Frs\Proses;

use App\Controllers\BaseController;
use App\Models\Frs\frsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Frs\Proses\Pencetakan_frs;
use App\Models\Frs\Mutu_frs;
use Exception;

class Pencetakan extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $pencetakan_frs;
    protected $mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pencetakan_frs = new Pencetakan_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pencetakanData_frs = $this->pencetakan_frs->getpencetakan_frs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pencetakanDatafrs' => $pencetakanData_frs,
        ];

        return view('frs/Proses/pencetakan', $data);
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
                list($id_pencetakan_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                $this->processAction($action, $id_pencetakan_frs, $id_frs, $id_user, $id_mutu_frs);
            }

            return redirect()->to('pencetakan_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pencetakan_frs, $id_frs, $id_user, $id_mutu_frs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pencetakan_frs->update($id_pencetakan_frs, [
                        'id_user_pencetakan_frs' => $id_user,
                        'status_pencetakan_frs' => 'Proses Pencetakan',
                        'mulai_pencetakan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pencetakan_frs->update($id_pencetakan_frs, [
                        'id_user_pencetakan_frs' => $id_user,
                        'status_pencetakan_frs' => 'Selesai Pencetakan',
                        'selesai_pencetakan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pencetakan_frs->update($id_pencetakan_frs, [
                        'id_user_pencetakan_frs' => null,
                        'status_pencetakan_frs' => 'Belum Pencetakan',
                        'mulai_pencetakan_frs' => null,
                        'selesai_pencetakan_frs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Selesai']);
                    break;
                case 'kembalikan':
                    $this->pencetakan_frs->delete($id_pencetakan_frs);
                    $this->frsModel->update($id_frs, [
                        'status_frs' => 'Authorized',
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
        // Ambil id_pencetakan_frs dari parameter GET
        $id_pencetakan_frs = $this->request->getGet('id_pencetakan_frs');

        if ($id_pencetakan_frs) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pencetakan_frs->select(
                'pencetakan.*, 
            frs.*, 
            patient.*, 
            users.nama_user AS nama_user_pencetakan'
            )
                ->join('frs', 'pencetakan.id_frs = frs.id_frs', 'left')
                ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pencetakan.id_user_pencetakan_frs = users.id_user', 'left')
                ->where('pencetakan.id_pencetakan_frs', $id_pencetakan_frs)
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
        $id_pencetakan_frs = $this->request->getGet('id_pencetakan_frs');

        if (!$id_pencetakan_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pencetakan tidak ditemukan.');
        }

        // Ambil data pencetakan
        $pencetakanData = $this->pencetakan_frs->find($id_pencetakan_frs);

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
        $id_pencetakan_frs = $this->request->getPost('id_pencetakan_frs');

        // Gabungkan input tanggal dan waktu
        $mulai_pencetakan_frs = $this->request->getPost('mulai_pencetakan_frs_date') . ' ' . $this->request->getPost('mulai_pencetakan_frs_time');
        $selesai_pencetakan_frs = $this->request->getPost('selesai_pencetakan_frs_date') . ' ' . $this->request->getPost('selesai_pencetakan_frs_time');

        $data = [
            'id_user_pencetakan_frs' => $this->request->getPost('id_user_pencetakan_frs'),
            'status_pencetakan_frs'  => $this->request->getPost('status_pencetakan_frs'),
            'mulai_pencetakan_frs'   => $mulai_pencetakan_frs,
            'selesai_pencetakan_frs' => $selesai_pencetakan_frs,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pencetakan_frs->update($id_pencetakan_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pencetakan/index_pencetakan'))->with('success', 'Data berhasil diperbarui.');
    }
}
