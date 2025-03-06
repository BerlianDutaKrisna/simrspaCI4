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

class Pengirisan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $penerimaan_hpa;
    protected $pengirisan_hpa;
    protected $pemotongan_hpa;
    protected $pembacaan_hpa;
    protected $penulisan_hpa;
    protected $pemverifikasi_hpa;
    protected $authorized_hpa;
    protected $pencetakan_hpa;
    protected $Mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penerimaan_hpa = new Penerimaan_hpa();
        $this->pengirisan_hpa = new Pengirisan_hpa();
        $this->pemotongan_hpa = new Pemotongan_hpa();
        $this->pembacaan_hpa = new Pembacaan_hpa();
        $this->penulisan_hpa = new Penulisan_hpa();
        $this->pemverifikasi_hpa = new Pemverifikasi_hpa();
        $this->authorized_hpa = new Authorized_hpa();
        $this->pencetakan_hpa = new Pencetakan_hpa();
        $this->Mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pengirisanData_hpa = $this->pengirisan_hpa->getPengirisan_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pengirisanDatahpa' => $pengirisanData_hpa,
        ];
        return view('Hpa/Proses/pengirisan', $data);
    }

    public function proses_pengirisan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pengirisan_hpa, $id_hpa, $id_mutu) = explode(':', $id);
                $indikator_1 = (string) ($this->request->getPost('indikator_1') ?? '0');
                $indikator_2 = (string) ($this->request->getPost('indikator_2') ?? '0');
                $total_nilai_mutu_hpa = $this->request->getPost('total_nilai_mutu_hpa');
                $this->ProcessAction($action, $id_pengirisan_hpa, $id_hpa, $id_user, $id_mutu, $indikator_1, $indikator_2, $total_nilai_mutu_hpa);
            }

            return redirect()->to('pengirisan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pengirisan_hpa, $id_hpa, $id_user, $id_mutu, $indikator_1, $indikator_2, $total_nilai_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pengirisan']);
                    $this->pengirisan_hpa->update($id_pengirisan_hpa, [
                        'id_user_pengirisan_hpa' => $id_user,
                        'status_pengirisan_hpa' => 'Proses Pengirisan',
                        'mulai_pengirisan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pengirisan_hpa->update($id_pengirisan_hpa, [
                        'id_user_pengirisan_hpa' => $id_user,
                        'status_pengirisan_hpa' => 'Selesai Pengirisan',
                        'selesai_pengirisan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pengirisan_hpa->update($id_pengirisan_hpa, [
                        'id_user_pengirisan_hpa' => null,
                        'status_pengirisan_hpa' => 'Belum Pengirisan',
                        'mulai_pengirisan_hpa' => null,
                        'selesai_pengirisan_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pemotongan']);
                    $pemotonganData = [
                        'id_hpa'            => $id_hpa,
                        'status_pemotongan_hpa' => 'Belum Pemotongan',
                    ];
                    if (!$this->pemotongan_hpa->insert($pemotonganData)) {
                        throw new Exception('Gagal menyimpan data pemotongan.');
                    }
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pengirisan_details()
    {
        // Ambil id_pengirisan_hpa dari parameter GET
        $id_pengirisan_hpa = $this->request->getGet('id_pengirisan_hpa');

        if ($id_pengirisan_hpa) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pengirisan_hpa->select(
                'pengirisan.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_pengirisan'
            )
                ->join('hpa', 'pengirisan.id_hpa = hpa.id_hpa', 'left')
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pengirisan.id_user_pengirisan_hpa = users.id_user', 'left')
                ->where('pengirisan.id_pengirisan_hpa', $id_pengirisan_hpa)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pengirisan tidak ditemukan.']);
        }
    }

    public function edit_pengirisan()
    {
        $id_pengirisan_hpa = $this->request->getGet('id_pengirisan_hpa');

        if (!$id_pengirisan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pengirisan tidak ditemukan.');
        }

        // Ambil data pengirisan
        $pengirisanData = $this->pengirisan_hpa->find($id_pengirisan_hpa);

        if (!$pengirisanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pengirisan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pengirisanData' => $pengirisanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pengirisan', $data);
    }

    public function update_pengirisan()
    {
        $id_pengirisan_hpa = $this->request->getPost('id_pengirisan_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_pengirisan_hpa = $this->request->getPost('mulai_pengirisan_hpa_date') . ' ' . $this->request->getPost('mulai_pengirisan_hpa_time');
        $selesai_pengirisan_hpa = $this->request->getPost('selesai_pengirisan_hpa_date') . ' ' . $this->request->getPost('selesai_pengirisan_hpa_time');

        $data = [
            'id_user_pengirisan_hpa' => $this->request->getPost('id_user_pengirisan_hpa'),
            'status_pengirisan_hpa'  => $this->request->getPost('status_pengirisan_hpa'),
            'mulai_pengirisan_hpa'   => $mulai_pengirisan_hpa,
            'selesai_pengirisan_hpa' => $selesai_pengirisan_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pengirisan_hpa->update($id_pengirisan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pengirisan/index_pengirisan'))->with('success', 'Data berhasil diperbarui.');
    }
}
