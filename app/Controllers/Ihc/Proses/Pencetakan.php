<?php

namespace App\Controllers\Ihc\Proses;

use App\Controllers\BaseController;
use App\Models\Ihc\ihcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Ihc\Proses\Pencetakan_ihc;
use App\Models\Ihc\Mutu_ihc;
use Exception;

class Pencetakan extends BaseController
{
    protected $ihcModel;
    protected $userModel;
    protected $patientModel;
    protected $pencetakan_ihc;
    protected $mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pencetakan_ihc = new Pencetakan_ihc();
        $this->mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pencetakanData_ihc = $this->pencetakan_ihc->getpencetakan_ihc();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pencetakanDataihc' => $pencetakanData_ihc,
        ];

        return view('ihc/Proses/pencetakan', $data);
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
                list($id_pencetakan_ihc, $id_ihc, $id_mutu_ihc) = explode(':', $id);
                $this->processAction($action, $id_pencetakan_ihc, $id_ihc, $id_user, $id_mutu_ihc);
            }

            return redirect()->to('pencetakan_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pencetakan_ihc, $id_ihc, $id_user, $id_mutu_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pencetakan_ihc->update($id_pencetakan_ihc, [
                        'id_user_pencetakan_ihc' => $id_user,
                        'status_pencetakan_ihc' => 'Proses Pencetakan',
                        'mulai_pencetakan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pencetakan_ihc->update($id_pencetakan_ihc, [
                        'id_user_pencetakan_ihc' => $id_user,
                        'status_pencetakan_ihc' => 'Selesai Pencetakan',
                        'selesai_pencetakan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pencetakan_ihc->update($id_pencetakan_ihc, [
                        'id_user_pencetakan_ihc' => null,
                        'status_pencetakan_ihc' => 'Belum Pencetakan',
                        'mulai_pencetakan_ihc' => null,
                        'selesai_pencetakan_ihc' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Selesai']);
                    break;
                case 'kembalikan':
                    $this->pencetakan_ihc->delete($id_pencetakan_ihc);
                    $this->ihcModel->update($id_ihc, [
                        'status_ihc' => 'Authorized',
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
        // Ambil id_pencetakan_ihc dari parameter GET
        $id_pencetakan_ihc = $this->request->getGet('id_pencetakan_ihc');

        if ($id_pencetakan_ihc) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pencetakan_ihc->select(
                'pencetakan.*, 
            ihc.*, 
            patient.*, 
            users.nama_user AS nama_user_pencetakan'
            )
                ->join('ihc', 'pencetakan.id_ihc = ihc.id_ihc', 'left')
                ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pencetakan.id_user_pencetakan_ihc = users.id_user', 'left')
                ->where('pencetakan.id_pencetakan_ihc', $id_pencetakan_ihc)
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
        $id_pencetakan_ihc = $this->request->getGet('id_pencetakan_ihc');

        if (!$id_pencetakan_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pencetakan tidak ditemukan.');
        }

        // Ambil data pencetakan
        $pencetakanData = $this->pencetakan_ihc->find($id_pencetakan_ihc);

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
        $id_pencetakan_ihc = $this->request->getPost('id_pencetakan_ihc');

        // Gabungkan input tanggal dan waktu
        $mulai_pencetakan_ihc = $this->request->getPost('mulai_pencetakan_ihc_date') . ' ' . $this->request->getPost('mulai_pencetakan_ihc_time');
        $selesai_pencetakan_ihc = $this->request->getPost('selesai_pencetakan_ihc_date') . ' ' . $this->request->getPost('selesai_pencetakan_ihc_time');

        $data = [
            'id_user_pencetakan_ihc' => $this->request->getPost('id_user_pencetakan_ihc'),
            'status_pencetakan_ihc'  => $this->request->getPost('status_pencetakan_ihc'),
            'mulai_pencetakan_ihc'   => $mulai_pencetakan_ihc,
            'selesai_pencetakan_ihc' => $selesai_pencetakan_ihc,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pencetakan_ihc->update($id_pencetakan_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pencetakan/index_pencetakan'))->with('success', 'Data berhasil diperbarui.');
    }
}
