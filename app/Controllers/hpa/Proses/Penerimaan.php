<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Penerimaan_hpa;
use App\Models\Hpa\Proses\Pengirisan_hpa;
use App\Models\Hpa\Proses\Pemotongan_hpa;
use App\Models\Hpa\Proses\Pembacaan_hpa;
use App\Models\Hpa\Proses\Penulisan_hpa;
use App\Models\Hpa\Proses\Pemverifikasi_hpa;
use App\Models\Hpa\Proses\Authorized_hpa;
use App\Models\Hpa\Proses\Pencetakan_hpa;
use App\Models\Hpa\Mutu_hpa;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

class Penerimaan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $Penerimaan_hpa;
    protected $Pengirisan_hpa;
    protected $Pemotongan_hpa;
    protected $Pembacaan_hpa;
    protected $Penulisan_hpa;
    protected $Pemverifikasi_hpa;
    protected $Authorized_hpa;
    protected $Pencetakan_hpa;
    protected $Mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->Penerimaan_hpa = new Penerimaan_hpa();
        $this->Pengirisan_hpa = new Pengirisan_hpa();
        $this->Pemotongan_hpa = new Pemotongan_hpa();
        $this->Pembacaan_hpa = new Pembacaan_hpa();
        $this->Penulisan_hpa = new Penulisan_hpa();
        $this->Pemverifikasi_hpa = new Pemverifikasi_hpa();
        $this->Authorized_hpa = new Authorized_hpa();
        $this->Pencetakan_hpa = new Pencetakan_hpa();
        $this->Mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penerimaanData_hpa = $this->Penerimaan_hpa->getPenerimaan_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penerimaanDatahpa' => $penerimaanData_hpa,
        ];
        
        return view('Hpa/Proses/penerimaan', $data);
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
                list($id_penerimaan_hpa, $id_hpa, $id_mutu) = explode(':', $id);
                $indikator_1 = (string) ($this->request->getPost('indikator_1') ?? '0');
                $indikator_2 = (string) ($this->request->getPost('indikator_2') ?? '0');
                $total_nilai_mutu_hpa = $this->request->getPost('total_nilai_mutu_hpa');
                $this->processAction($action, $id_penerimaan_hpa, $id_hpa, $id_user, $id_mutu, $indikator_1, $indikator_2, $total_nilai_mutu_hpa);
            }

            return redirect()->to('penerimaan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penerimaan_hpa, $id_hpa, $id_user, $id_mutu, $indikator_1, $indikator_2, $total_nilai_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Penerimaan']);
                    $this->Penerimaan_hpa->update($id_penerimaan_hpa, [
                        'id_user_penerimaan_hpa' => $id_user,
                        'status_penerimaan_hpa' => 'Proses Pemeriksaan',
                        'mulai_penerimaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->Penerimaan_hpa->update($id_penerimaan_hpa, [
                        'id_user_penerimaan_hpa' => $id_user,
                        'status_penerimaan_hpa' => 'Selesai Pemeriksaan',
                        'selesai_penerimaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    $this->Mutu_hpa->update($id_mutu, [
                        'indikator_1' => $indikator_1,
                        'indikator_2' => $indikator_2,
                        'total_nilai_mutu_hpa' => $total_nilai_mutu_hpa + $indikator_1 + $indikator_2,
                    ]);
                    break;
                case 'reset':
                    $this->Penerimaan_hpa->update($id_penerimaan_hpa, [
                        'id_user_penerimaan_hpa' => null,
                        'status_penerimaan_hpa' => 'Belum Pemeriksaan',
                        'mulai_penerimaan_hpa' => null,
                        'selesai_penerimaan_hpa' => null,
                    ]);
                    $this->Mutu_hpa->update($id_mutu, [
                        'indikator_1' => '0',
                        'indikator_2' => '0',
                        'total_nilai_mutu_hpa' => '0',
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pengirisan']);
                    $pengirisanData = [
                        'id_hpa'            => $id_hpa,
                        'status_pengirisan_hpa' => 'Belum Pengirisan',
                    ];
                    if (!$this->Pengirisan_hpa->insert($pengirisanData)) {
                        throw new Exception('Gagal menyimpan data pengirisan.');
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
        // Ambil id_penerimaan_hpa dari parameter GET
        $id_penerimaan_hpa = $this->request->getGet('id_penerimaan_hpa');

        if ($id_penerimaan_hpa) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->Penerimaan_hpa->select(
                'penerimaan.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_penerimaan'
            )
                ->join('hpa', 'penerimaan.id_hpa = hpa.id_hpa', 'left')
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'penerimaan.id_user_penerimaan_hpa = users.id_user', 'left')
                ->where('penerimaan.id_penerimaan_hpa', $id_penerimaan_hpa)
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
        $id_penerimaan_hpa = $this->request->getGet('id_penerimaan_hpa');

        if (!$id_penerimaan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penerimaan tidak ditemukan.');
        }

        // Ambil data penerimaan
        $penerimaanData = $this->Penerimaan_hpa->find($id_penerimaan_hpa);

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
        $id_penerimaan_hpa = $this->request->getPost('id_penerimaan_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_penerimaan_hpa = $this->request->getPost('mulai_penerimaan_hpa_date') . ' ' . $this->request->getPost('mulai_penerimaan_hpa_time');
        $selesai_penerimaan_hpa = $this->request->getPost('selesai_penerimaan_hpa_date') . ' ' . $this->request->getPost('selesai_penerimaan_hpa_time');

        $data = [
            'id_user_penerimaan_hpa' => $this->request->getPost('id_user_penerimaan_hpa'),
            'status_penerimaan_hpa'  => $this->request->getPost('status_penerimaan_hpa'),
            'mulai_penerimaan_hpa'   => $mulai_penerimaan_hpa,
            'selesai_penerimaan_hpa' => $selesai_penerimaan_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->Penerimaan_hpa->update($id_penerimaan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penerimaan/index_penerimaan'))->with('success', 'Data berhasil diperbarui.');
    }
}
