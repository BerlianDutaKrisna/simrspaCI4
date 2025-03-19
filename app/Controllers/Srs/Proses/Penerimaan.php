<?php

namespace App\Controllers\Srs\Proses;

use App\Controllers\BaseController;
use App\Models\Srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\Penerimaan_srs;
use App\Models\Srs\Proses\Pembacaan_srs;
use App\Models\Srs\Proses\Penulisan_srs;
use App\Models\Srs\Proses\Pemverifikasi_srs;
use App\Models\Srs\Proses\Authorized_srs;
use App\Models\Srs\Proses\Pencetakan_srs;
use App\Models\Srs\Mutu_srs;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

class Penerimaan extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $Penerimaan_srs;
    protected $Pembacaan_srs;
    protected $Penulisan_srs;
    protected $Pemverifikasi_srs;
    protected $Authorized_srs;
    protected $Pencetakan_srs;
    protected $Mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->Penerimaan_srs = new Penerimaan_srs();
        $this->Pembacaan_srs = new Pembacaan_srs();
        $this->Penulisan_srs = new Penulisan_srs();
        $this->Pemverifikasi_srs = new Pemverifikasi_srs();
        $this->Authorized_srs = new Authorized_srs();
        $this->Pencetakan_srs = new Pencetakan_srs();
        $this->Mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penerimaanData_srs = $this->Penerimaan_srs->getPenerimaan_srs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penerimaanDatasrs' => $penerimaanData_srs,
        ];

        return view('srs/Proses/penerimaan', $data);
    }

    public function proses_penerimaan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_penerimaan_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                // $indikator_1 = (string) ($this->request->getPost('indikator_1') ?? '0');
                // $indikator_2 = (string) ($this->request->getPost('indikator_2') ?? '0');
                // $total_nilai_mutu_srs = $this->request->getPost('total_nilai_mutu_srs');
                $this->processAction($action, $id_penerimaan_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('penerimaan_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penerimaan_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Penerimaan']);
                    $this->Penerimaan_srs->update($id_penerimaan_srs, [
                        'id_user_penerimaan_srs' => $id_user,
                        'status_penerimaan_srs' => 'Proses Penerimaan',
                        'mulai_penerimaan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->Penerimaan_srs->update($id_penerimaan_srs, [
                        'id_user_penerimaan_srs' => $id_user,
                        'status_penerimaan_srs' => 'Selesai Penerimaan',
                        'selesai_penerimaan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->Penerimaan_srs->update($id_penerimaan_srs, [
                        'id_user_penerimaan_srs' => null,
                        'status_penerimaan_srs' => 'Belum Penerimaan',
                        'mulai_penerimaan_srs' => null,
                        'selesai_penerimaan_srs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Pembacaan']);
                    $pembacaanData = [
                        'id_srs'            => $id_srs,
                        'status_pembacaan_srs' => 'Belum Pembacaan',
                    ];
                    if (!$this->Pembacaan_srs->insert($pembacaanData)) {
                        throw new Exception('Gagal menyimpan data Pembacaan.');
                    }
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function penerimaan_details()
    {
        // Ambil id_penerimaan_srs dari parameter GET
        $id_penerimaan_srs = $this->request->getGet('id_penerimaan_srs');

        if ($id_penerimaan_srs) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->Penerimaan_srs->select(
                'penerimaan.*, 
            srs.*, 
            patient.*, 
            users.nama_user AS nama_user_penerimaan'
            )
                ->join('srs', 'penerimaan.id_srs = srs.id_srs', 'left')
                ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'penerimaan.id_user_penerimaan_srs = users.id_user', 'left')
                ->where('penerimaan.id_penerimaan_srs', $id_penerimaan_srs)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID Penerimaan tidak ditemukan.']);
        }
    }

    public function edit_penerimaan()
    {
        $id_penerimaan_srs = $this->request->getGet('id_penerimaan_srs');

        if (!$id_penerimaan_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penerimaan tidak ditemukan.');
        }

        // Ambil data penerimaan
        $penerimaanData = $this->Penerimaan_srs->find($id_penerimaan_srs);

        if (!$penerimaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penerimaan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'penerimaanData' => $penerimaanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_penerimaan', $data);
    }

    public function update_penerimaan()
    {
        $id_penerimaan_srs = $this->request->getPost('id_penerimaan_srs');

        // Gabungkan input tanggal dan waktu
        $mulai_penerimaan_srs = $this->request->getPost('mulai_penerimaan_srs_date') . ' ' . $this->request->getPost('mulai_penerimaan_srs_time');
        $selesai_penerimaan_srs = $this->request->getPost('selesai_penerimaan_srs_date') . ' ' . $this->request->getPost('selesai_penerimaan_srs_time');

        $data = [
            'id_user_penerimaan_srs' => $this->request->getPost('id_user_penerimaan_srs'),
            'status_penerimaan_srs'  => $this->request->getPost('status_penerimaan_srs'),
            'mulai_penerimaan_srs'   => $mulai_penerimaan_srs,
            'selesai_penerimaan_srs' => $selesai_penerimaan_srs,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->Penerimaan_srs->update($id_penerimaan_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penerimaan/index_penerimaan'))->with('success', 'Data berhasil diperbarui.');
    }
}
