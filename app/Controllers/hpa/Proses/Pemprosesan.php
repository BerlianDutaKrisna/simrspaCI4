<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Pemprosesan_hpa;
use App\Models\Hpa\Proses\Penanaman_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Pemprosesan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $pemprosesan_hpa;
    protected $penanaman_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pemprosesan_hpa = new Pemprosesan_hpa();
        $this->penanaman_hpa = new Penanaman_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pemprosesanData_hpa = $this->pemprosesan_hpa->getpemprosesan_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pemprosesanDatahpa' => $pemprosesanData_hpa,
        ];

        return view('Hpa/Proses/pemprosesan', $data);
    }

    public function proses_pemprosesan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pemprosesan_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $this->processAction($action, $id_pemprosesan_hpa, $id_hpa, $id_user, $id_mutu_hpa);
            }

            return redirect()->to('pemprosesan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pemprosesan_hpa, $id_hpa, $id_user, $id_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pemprosesan_hpa->update($id_pemprosesan_hpa, [
                        'id_user_pemprosesan_hpa' => $id_user,
                        'status_pemprosesan_hpa' => 'Proses Pemprosesan',
                        'mulai_pemprosesan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pemprosesan_hpa->update($id_pemprosesan_hpa, [
                        'id_user_pemprosesan_hpa' => $id_user,
                        'status_pemprosesan_hpa' => 'Selesai Pemprosesan',
                        'selesai_pemprosesan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pemprosesan_hpa->update($id_pemprosesan_hpa, [
                        'id_user_pemprosesan_hpa' => null,
                        'status_pemprosesan_hpa' => 'Belum Pemprosesan',
                        'mulai_pemprosesan_hpa' => null,
                        'selesai_pemprosesan_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Penanaman']);
                    $penanamanData = [
                        'id_hpa'            => $id_hpa,
                        'status_penanaman_hpa' => 'Belum Penanaman',
                    ];
                    if (!$this->penanaman_hpa->insert($penanamanData)) {
                        throw new Exception('Gagal menyimpan data penanaman.');
                    }
                    break;
                case 'kembalikan':
                    $this->pemprosesan_hpa->delete($id_pemprosesan_hpa);
                    $this->hpaModel->update($id_hpa, [
                        'status_hpa' => 'Pemotongan',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pemprosesan_details()
    {
        // Ambil id_pemprosesan_hpa dari parameter GET
        $id_pemprosesan_hpa = $this->request->getGet('id_pemprosesan_hpa');

        if ($id_pemprosesan_hpa) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pemprosesan_hpa->select(
                'pemprosesan.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_pemprosesan'
            )
                ->join('hpa', 'pemprosesan.id_hpa = hpa.id_hpa', 'left')
                ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pemprosesan.id_user_pemprosesan_hpa = users.id_user', 'left')
                ->where('pemprosesan.id_pemprosesan_hpa', $id_pemprosesan_hpa)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pemprosesan tidak ditemukan.']);
        }
    }

    public function edit_pemprosesan()
    {
        $id_pemprosesan_hpa = $this->request->getGet('id_pemprosesan_hpa');

        if (!$id_pemprosesan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemprosesan tidak ditemukan.');
        }

        // Ambil data pemprosesan
        $pemprosesanData = $this->pemprosesan_hpa->find($id_pemprosesan_hpa);

        if (!$pemprosesanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pemprosesan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pemprosesanData' => $pemprosesanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pemprosesan', $data);
    }

    public function update_pemprosesan()
    {
        $id_pemprosesan_hpa = $this->request->getPost('id_pemprosesan_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_pemprosesan_hpa = $this->request->getPost('mulai_pemprosesan_hpa_date') . ' ' . $this->request->getPost('mulai_pemprosesan_hpa_time');
        $selesai_pemprosesan_hpa = $this->request->getPost('selesai_pemprosesan_hpa_date') . ' ' . $this->request->getPost('selesai_pemprosesan_hpa_time');

        $data = [
            'id_user_pemprosesan_hpa' => $this->request->getPost('id_user_pemprosesan_hpa'),
            'status_pemprosesan_hpa'  => $this->request->getPost('status_pemprosesan_hpa'),
            'mulai_pemprosesan_hpa'   => $mulai_pemprosesan_hpa,
            'selesai_pemprosesan_hpa' => $selesai_pemprosesan_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemprosesan_hpa->update($id_pemprosesan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pemprosesan/index_pemprosesan'))->with('success', 'Data berhasil diperbarui.');
    }
}
