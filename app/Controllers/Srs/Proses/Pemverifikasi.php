<?php

namespace App\Controllers\srs\Proses;

use App\Controllers\BaseController;
use App\Models\srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\srs\Proses\Pemverifikasi_srs;
use App\Models\srs\Proses\Authorized_srs;
use App\Models\srs\Mutu_srs;
use Exception;

class Pemverifikasi extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $pemverifikasi_srs;
    protected $authorized_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pemverifikasi_srs = new Pemverifikasi_srs();
        $this->authorized_srs = new Authorized_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pemverifikasiData_srs = $this->pemverifikasi_srs->getpemverifikasi_srs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pemverifikasiDatasrs' => $pemverifikasiData_srs,
        ];
        
        return view('srs/Proses/pemverifikasi', $data);
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
                list($id_pemverifikasi_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                $this->processAction($action, $id_pemverifikasi_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('pemverifikasi_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pemverifikasi_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pemverifikasi_srs->update($id_pemverifikasi_srs, [
                        'id_user_pemverifikasi_srs' => $id_user,
                        'status_pemverifikasi_srs' => 'Proses Pemverifikasi',
                        'mulai_pemverifikasi_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pemverifikasi_srs->update($id_pemverifikasi_srs, [
                        'id_user_pemverifikasi_srs' => $id_user,
                        'status_pemverifikasi_srs' => 'Selesai Pemverifikasi',
                        'selesai_pemverifikasi_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pemverifikasi_srs->update($id_pemverifikasi_srs, [
                        'id_user_pemverifikasi_srs' => null,
                        'status_pemverifikasi_srs' => 'Belum Pemverifikasi',
                        'mulai_pemverifikasi_srs' => null,
                        'selesai_pemverifikasi_srs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Authorized']);
                    $authorizedData = [
                        'id_srs'            => $id_srs,
                        'status_authorized_srs' => 'Belum Authorized',
                    ];
                    if (!$this->authorized_srs->insert($authorizedData)) {
                        throw new Exception('Gagal menyimpan data authorized.');
                    }
                    break;
                case 'kembalikan':
                    $this->pemverifikasi_srs->delete($id_pemverifikasi_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Penulisan',
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
        // Ambil id_pemverifikasi_srs dari parameter GET
        $id_pemverifikasi_srs = $this->request->getGet('id_pemverifikasi_srs');

        if ($id_pemverifikasi_srs) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pemverifikasi_srs->select(
                'pemverifikasi.*, 
            srs.*, 
            patient.*, 
            users.nama_user AS nama_user_pemverifikasi'
            )
                ->join('srs', 'pemverifikasi.id_srs = srs.id_srs', 'left')
                ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pemverifikasi.id_user_pemverifikasi_srs = users.id_user', 'left')
                ->where('pemverifikasi.id_pemverifikasi_srs', $id_pemverifikasi_srs)
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
        $id_pemverifikasi_srs = $this->request->getGet('id_pemverifikasi_srs');

        if (!$id_pemverifikasi_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemverifikasi tidak ditemukan.');
        }

        // Ambil data pemverifikasi
        $pemverifikasiData = $this->pemverifikasi_srs->find($id_pemverifikasi_srs);

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
        $id_pemverifikasi_srs = $this->request->getPost('id_pemverifikasi_srs');

        // Gabungkan input tanggal dan waktu
        $mulai_pemverifikasi_srs = $this->request->getPost('mulai_pemverifikasi_srs_date') . ' ' . $this->request->getPost('mulai_pemverifikasi_srs_time');
        $selesai_pemverifikasi_srs = $this->request->getPost('selesai_pemverifikasi_srs_date') . ' ' . $this->request->getPost('selesai_pemverifikasi_srs_time');

        $data = [
            'id_user_pemverifikasi_srs' => $this->request->getPost('id_user_pemverifikasi_srs'),
            'status_pemverifikasi_srs'  => $this->request->getPost('status_pemverifikasi_srs'),
            'mulai_pemverifikasi_srs'   => $mulai_pemverifikasi_srs,
            'selesai_pemverifikasi_srs' => $selesai_pemverifikasi_srs,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemverifikasi_srs->update($id_pemverifikasi_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pemverifikasi/index_pemverifikasi'))->with('success', 'Data berhasil diperbarui.');
    }
}
