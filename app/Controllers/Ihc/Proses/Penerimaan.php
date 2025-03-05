<?php

namespace App\Controllers\Ihc\Proses;

use App\Controllers\BaseController;
use App\Models\ihc\ihcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Ihc\Proses\Penerimaan_ihc;
use App\Models\Ihc\Proses\Pembacaan_ihc;
use App\Models\Ihc\Proses\Penulisan_ihc;
use App\Models\Ihc\Proses\Pemverifikasi_ihc;
use App\Models\Ihc\Proses\Authorized_ihc;
use App\Models\Ihc\Proses\Pencetakan_ihc;
use App\Models\Ihc\Mutu_ihc;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

class Penerimaan extends BaseController
{
    protected $ihcModel;
    protected $userModel;
    protected $patientModel;
    protected $Penerimaan_ihc;
    protected $Pembacaan_ihc;
    protected $Penulisan_ihc;
    protected $Pemverifikasi_ihc;
    protected $Authorized_ihc;
    protected $Pencetakan_ihc;
    protected $Mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->Penerimaan_ihc = new Penerimaan_ihc();
        $this->Pembacaan_ihc = new Pembacaan_ihc();
        $this->Penulisan_ihc = new Penulisan_ihc();
        $this->Pemverifikasi_ihc = new Pemverifikasi_ihc();
        $this->Authorized_ihc = new Authorized_ihc();
        $this->Pencetakan_ihc = new Pencetakan_ihc();
        $this->Mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penerimaanData_ihc = $this->Penerimaan_ihc->getPenerimaan_ihc();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penerimaanDataihc' => $penerimaanData_ihc,
        ];

        return view('Ihc/Proses/penerimaan', $data);
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
                list($id_penerimaan_ihc, $id_ihc, $id_mutu_ihc) = explode(':', $id);
                $indikator_1 = (string) ($this->request->getPost('indikator_1') ?? '0');
                $indikator_2 = (string) ($this->request->getPost('indikator_2') ?? '0');
                $indikator_3 = (string) ($this->request->getPost('indikator_3') ?? '0');
                $total_nilai_mutu_ihc = $this->request->getPost('total_nilai_mutu_ihc');
                $this->processAction($action, $id_penerimaan_ihc, $id_ihc, $id_user, $id_mutu_ihc, $indikator_1, $indikator_2, $indikator_3, $total_nilai_mutu_ihc);
            }

            return redirect()->to('penerimaan_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penerimaan_ihc, $id_ihc, $id_user, $id_mutu_ihc, $indikator_1, $indikator_2, $indikator_3, $total_nilai_mutu_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Penerimaan']);
                    $this->Penerimaan_ihc->update($id_penerimaan_ihc, [
                        'id_user_penerimaan_ihc' => $id_user,
                        'status_penerimaan_ihc' => 'Proses Penerimaan',
                        'mulai_penerimaan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->Penerimaan_ihc->update($id_penerimaan_ihc, [
                        'id_user_penerimaan_ihc' => $id_user,
                        'status_penerimaan_ihc' => 'Selesai Penerimaan',
                        'selesai_penerimaan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                    $this->Mutu_ihc->update($id_mutu, [
                        'indikator_1' => $indikator_1,
                        'indikator_2' => $indikator_2,
                        'indikator_3' => $indikator_3,
                        'total_nilai_mutu_ihc' => $total_nilai_mutu_ihc + $indikator_1 + $indikator_2 + $indikator_3,
                    ]);
                    break;
                case 'reset':
                    $this->Penerimaan_ihc->update($id_penerimaan_ihc, [
                        'id_user_penerimaan_ihc' => null,
                        'status_penerimaan_ihc' => 'Belum Penerimaan',
                        'mulai_penerimaan_ihc' => null,
                        'selesai_penerimaan_ihc' => null,
                    ]);
                    break;
                    $this->Mutu_ihc->update($id_mutu, [
                        'indikator_1' => '0',
                        'indikator_2' => '0',
                        'indikator_3' => '0',
                        'total_nilai_mutu_ihc' => '0',
                    ]);
                    break;
                case 'lanjut':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Pembacaan']);
                    $pembacaanData = [
                        'id_ihc'            => $id_ihc,
                        'status_pembacaan_ihc' => 'Belum Pembacaan',
                    ];
                    if (!$this->Pembacaan_ihc->insert($pembacaanData)) {
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
        // Ambil id_penerimaan_ihc dari parameter GET
        $id_penerimaan_ihc = $this->request->getGet('id_penerimaan_ihc');

        if ($id_penerimaan_ihc) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->Penerimaan_ihc->select(
                'penerimaan.*, 
            ihc.*, 
            patient.*, 
            users.nama_user AS nama_user_penerimaan'
            )
                ->join('ihc', 'penerimaan.id_ihc = ihc.id_ihc', 'left')
                ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'penerimaan.id_user_penerimaan_ihc = users.id_user', 'left')
                ->where('penerimaan.id_penerimaan_ihc', $id_penerimaan_ihc)
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
        $id_penerimaan_ihc = $this->request->getGet('id_penerimaan_ihc');

        if (!$id_penerimaan_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penerimaan tidak ditemukan.');
        }

        // Ambil data penerimaan
        $penerimaanData = $this->Penerimaan_ihc->find($id_penerimaan_ihc);

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
        $id_penerimaan_ihc = $this->request->getPost('id_penerimaan_ihc');

        // Gabungkan input tanggal dan waktu
        $mulai_penerimaan_ihc = $this->request->getPost('mulai_penerimaan_ihc_date') . ' ' . $this->request->getPost('mulai_penerimaan_ihc_time');
        $selesai_penerimaan_ihc = $this->request->getPost('selesai_penerimaan_ihc_date') . ' ' . $this->request->getPost('selesai_penerimaan_ihc_time');

        $data = [
            'id_user_penerimaan_ihc' => $this->request->getPost('id_user_penerimaan_ihc'),
            'status_penerimaan_ihc'  => $this->request->getPost('status_penerimaan_ihc'),
            'mulai_penerimaan_ihc'   => $mulai_penerimaan_ihc,
            'selesai_penerimaan_ihc' => $selesai_penerimaan_ihc,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->Penerimaan_ihc->update($id_penerimaan_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penerimaan/index_penerimaan'))->with('success', 'Data berhasil diperbarui.');
    }
}
