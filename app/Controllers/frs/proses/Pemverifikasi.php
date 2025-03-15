<?php

namespace App\Controllers\Frs\Proses;

use App\Controllers\BaseController;
use App\Models\Frs\frsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Frs\Proses\Pemverifikasi_frs;
use App\Models\Frs\Proses\Authorized_frs;
use App\Models\Frs\Mutu_frs;
use Exception;

class Pemverifikasi extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $pemverifikasi_frs;
    protected $authorized_frs;
    protected $mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pemverifikasi_frs = new Pemverifikasi_frs();
        $this->authorized_frs = new Authorized_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pemverifikasiData_frs = $this->pemverifikasi_frs->getpemverifikasi_frs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pemverifikasiDatafrs' => $pemverifikasiData_frs,
        ];
        
        return view('frs/Proses/pemverifikasi', $data);
    }

    public function proses_pemverifikasi()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pemverifikasi_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                $this->processAction($action, $id_pemverifikasi_frs, $id_frs, $id_user, $id_mutu_frs);
            }

            return redirect()->to('pemverifikasi_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pemverifikasi_frs, $id_frs, $id_user, $id_mutu_frs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pemverifikasi_frs->update($id_pemverifikasi_frs, [
                        'id_user_pemverifikasi_frs' => $id_user,
                        'status_pemverifikasi_frs' => 'Proses Pemverifikasi',
                        'mulai_pemverifikasi_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pemverifikasi_frs->update($id_pemverifikasi_frs, [
                        'id_user_pemverifikasi_frs' => $id_user,
                        'status_pemverifikasi_frs' => 'Selesai Pemverifikasi',
                        'selesai_pemverifikasi_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pemverifikasi_frs->update($id_pemverifikasi_frs, [
                        'id_user_pemverifikasi_frs' => null,
                        'status_pemverifikasi_frs' => 'Belum Pemverifikasi',
                        'mulai_pemverifikasi_frs' => null,
                        'selesai_pemverifikasi_frs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Authorized']);
                    $authorizedData = [
                        'id_frs'            => $id_frs,
                        'status_authorized_frs' => 'Belum Authorized',
                    ];
                    if (!$this->authorized_frs->insert($authorizedData)) {
                        throw new Exception('Gagal menyimpan data authorized.');
                    }
                    break;
                case 'kembalikan':
                    $this->pemverifikasi_frs->delete($id_pemverifikasi_frs);
                    $this->frsModel->update($id_frs, [
                        'status_frs' => 'Penulisan',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pemverifikasi_details()
    {
        // Ambil id_pemverifikasi_frs dari parameter GET
        $id_pemverifikasi_frs = $this->request->getGet('id_pemverifikasi_frs');

        if ($id_pemverifikasi_frs) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pemverifikasi_frs->select(
                'pemverifikasi.*, 
            frs.*, 
            patient.*, 
            users.nama_user AS nama_user_pemverifikasi'
            )
                ->join('frs', 'pemverifikasi.id_frs = frs.id_frs', 'left')
                ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pemverifikasi.id_user_pemverifikasi_frs = users.id_user', 'left')
                ->where('pemverifikasi.id_pemverifikasi_frs', $id_pemverifikasi_frs)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pemverifikasi tidak ditemukan.']);
        }
    }

    public function edit_pemverifikasi()
    {
        $id_pemverifikasi_frs = $this->request->getGet('id_pemverifikasi_frs');

        if (!$id_pemverifikasi_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemverifikasi tidak ditemukan.');
        }

        // Ambil data pemverifikasi
        $pemverifikasiData = $this->pemverifikasi_frs->find($id_pemverifikasi_frs);

        if (!$pemverifikasiData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pemverifikasi tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pemverifikasiData' => $pemverifikasiData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pemverifikasi', $data);
    }

    public function update_pemverifikasi()
    {
        $id_pemverifikasi_frs = $this->request->getPost('id_pemverifikasi_frs');

        // Gabungkan input tanggal dan waktu
        $mulai_pemverifikasi_frs = $this->request->getPost('mulai_pemverifikasi_frs_date') . ' ' . $this->request->getPost('mulai_pemverifikasi_frs_time');
        $selesai_pemverifikasi_frs = $this->request->getPost('selesai_pemverifikasi_frs_date') . ' ' . $this->request->getPost('selesai_pemverifikasi_frs_time');

        $data = [
            'id_user_pemverifikasi_frs' => $this->request->getPost('id_user_pemverifikasi_frs'),
            'status_pemverifikasi_frs'  => $this->request->getPost('status_pemverifikasi_frs'),
            'mulai_pemverifikasi_frs'   => $mulai_pemverifikasi_frs,
            'selesai_pemverifikasi_frs' => $selesai_pemverifikasi_frs,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemverifikasi_frs->update($id_pemverifikasi_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pemverifikasi/index_pemverifikasi'))->with('success', 'Data berhasil diperbarui.');
    }
}
