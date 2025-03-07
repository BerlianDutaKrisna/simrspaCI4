<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Penerimaan_hpa;
use App\Models\Hpa\Proses\Pengirisan_hpa;
use App\Models\Hpa\Proses\Pemotongan_hpa;
use App\Models\Hpa\Proses\pewarnaan_hpa;
use App\Models\Hpa\Proses\Penanaman_hpa;
use App\Models\Hpa\Proses\Pembacaan_hpa;
use App\Models\Hpa\Proses\Penulisan_hpa;
use App\Models\Hpa\Proses\Pemverifikasi_hpa;
use App\Models\Hpa\Proses\Authorized_hpa;
use App\Models\Hpa\Proses\Pencetakan_hpa;
use App\Models\Hpa\Mutu_hpa;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

class Pewarnaan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $penerimaan_hpa;
    protected $pengirisan_hpa;
    protected $pemotongan_hpa;
    protected $pewarnaan_hpa;
    protected $penanaman_hpa;
    protected $pembacaan_hpa;
    protected $penulisan_hpa;
    protected $pemverifikasi_hpa;
    protected $authorized_hpa;
    protected $pencetakan_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penerimaan_hpa = new Penerimaan_hpa();
        $this->pengirisan_hpa = new Pengirisan_hpa();
        $this->pemotongan_hpa = new Pemotongan_hpa();
        $this->pewarnaan_hpa = new pewarnaan_hpa();
        $this->penanaman_hpa = new Penanaman_hpa();
        $this->pembacaan_hpa = new Pembacaan_hpa();
        $this->penulisan_hpa = new Penulisan_hpa();
        $this->pemverifikasi_hpa = new Pemverifikasi_hpa();
        $this->authorized_hpa = new Authorized_hpa();
        $this->pencetakan_hpa = new Pencetakan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pewarnaanData_hpa = $this->pewarnaan_hpa->getpewarnaan_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pewarnaanDatahpa' => $pewarnaanData_hpa,
        ];
        
        return view('Hpa/Proses/pewarnaan', $data);
    }

    public function proses_pewarnaan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pewarnaan_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $indikator_3 = (string) ($this->request->getPost('indikator_3') ?? '0');
                $total_nilai_mutu_hpa = $this->request->getPost('total_nilai_mutu_hpa');
                $this->processAction($action, $id_pewarnaan_hpa, $id_hpa, $id_user, $id_mutu_hpa, $indikator_3, $total_nilai_mutu_hpa);
            }

            return redirect()->to('pewarnaan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pewarnaan_hpa, $id_hpa, $id_user, $id_mutu_hpa, $indikator_3, $total_nilai_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pewarnaan_hpa->update($id_pewarnaan_hpa, [
                        'id_user_pewarnaan_hpa' => $id_user,
                        'status_pewarnaan_hpa' => 'Proses Pewarnaan',
                        'mulai_pewarnaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pewarnaan_hpa->update($id_pewarnaan_hpa, [
                        'id_user_pewarnaan_hpa' => $id_user,
                        'status_pewarnaan_hpa' => 'Selesai Pewarnaan',
                        'selesai_pewarnaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pewarnaan_hpa->update($id_pewarnaan_hpa, [
                        'id_user_pewarnaan_hpa' => null,
                        'status_pewarnaan_hpa' => 'Belum Pewarnaan',
                        'mulai_pewarnaan_hpa' => null,
                        'selesai_pewarnaan_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pembacaan']);
                    $pembacaanData = [
                        'id_hpa'            => $id_hpa,
                        'status_pembacaan_hpa' => 'Belum pembacaan',
                    ];
                    if (!$this->pembacaan_hpa->insert($pembacaanData)) {
                        throw new Exception('Gagal menyimpan data pembacaan.');
                    }
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pewarnaan_details()
    {
        // Ambil id_pewarnaan_hpa dari parameter GET
        $id_pewarnaan_hpa = $this->request->getGet('id_pewarnaan_hpa');

        if ($id_pewarnaan_hpa) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pewarnaan_hpa->select(
                'pewarnaan.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_pewarnaan'
            )
                ->join('hpa', 'pewarnaan.id_hpa = hpa.id_hpa', 'left')
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pewarnaan.id_user_pewarnaan_hpa = users.id_user', 'left')
                ->where('pewarnaan.id_pewarnaan_hpa', $id_pewarnaan_hpa)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pewarnaan tidak ditemukan.']);
        }
    }

    public function edit_pewarnaan()
    {
        $id_pewarnaan_hpa = $this->request->getGet('id_pewarnaan_hpa');

        if (!$id_pewarnaan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pewarnaan tidak ditemukan.');
        }

        // Ambil data pewarnaan
        $pewarnaanData = $this->pewarnaan_hpa->find($id_pewarnaan_hpa);

        if (!$pewarnaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pewarnaan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pewarnaanData' => $pewarnaanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pewarnaan', $data);
    }

    public function update_pewarnaan()
    {
        $id_pewarnaan_hpa = $this->request->getPost('id_pewarnaan_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_pewarnaan_hpa = $this->request->getPost('mulai_pewarnaan_hpa_date') . ' ' . $this->request->getPost('mulai_pewarnaan_hpa_time');
        $selesai_pewarnaan_hpa = $this->request->getPost('selesai_pewarnaan_hpa_date') . ' ' . $this->request->getPost('selesai_pewarnaan_hpa_time');

        $data = [
            'id_user_pewarnaan_hpa' => $this->request->getPost('id_user_pewarnaan_hpa'),
            'status_pewarnaan_hpa'  => $this->request->getPost('status_pewarnaan_hpa'),
            'mulai_pewarnaan_hpa'   => $mulai_pewarnaan_hpa,
            'selesai_pewarnaan_hpa' => $selesai_pewarnaan_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pewarnaan_hpa->update($id_pewarnaan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pewarnaan/index_pewarnaan'))->with('success', 'Data berhasil diperbarui.');
    }
}
