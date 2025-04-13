<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Pemverifikasi_hpa;
use App\Models\Hpa\Proses\Authorized_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Pemverifikasi extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $pemverifikasi_hpa;
    protected $authorized_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pemverifikasi_hpa = new Pemverifikasi_hpa();
        $this->authorized_hpa = new Authorized_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pemverifikasiData_hpa = $this->pemverifikasi_hpa->getpemverifikasi_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pemverifikasiDatahpa' => $pemverifikasiData_hpa,
        ];

        return view('Hpa/Proses/pemverifikasi', $data);
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
                list($id_pemverifikasi_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $this->processAction($action, $id_pemverifikasi_hpa, $id_hpa, $id_user, $id_mutu_hpa);
            }

            return redirect()->to('pemverifikasi_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pemverifikasi_hpa, $id_hpa, $id_user, $id_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pemverifikasi_hpa->update($id_pemverifikasi_hpa, [
                        'id_user_pemverifikasi_hpa' => $id_user,
                        'status_pemverifikasi_hpa' => 'Proses Pemverifikasi',
                        'mulai_pemverifikasi_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pemverifikasi_hpa->update($id_pemverifikasi_hpa, [
                        'id_user_pemverifikasi_hpa' => $id_user,
                        'status_pemverifikasi_hpa' => 'Selesai Pemverifikasi',
                        'selesai_pemverifikasi_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pemverifikasi_hpa->update($id_pemverifikasi_hpa, [
                        'id_user_pemverifikasi_hpa' => null,
                        'status_pemverifikasi_hpa' => 'Belum Pemverifikasi',
                        'mulai_pemverifikasi_hpa' => null,
                        'selesai_pemverifikasi_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Authorized']);
                    $authorizedData = [
                        'id_hpa'            => $id_hpa,
                        'status_authorized_hpa' => 'Belum Authorized',
                    ];
                    if (!$this->authorized_hpa->insert($authorizedData)) {
                        throw new Exception('Gagal menyimpan data authorized.');
                    }
                    break;
                case 'kembalikan':
                    $this->pemverifikasi_hpa->delete($id_pemverifikasi_hpa);
                    $this->hpaModel->update($id_hpa, [
                        'status_hpa' => 'Penulisan',
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
        $id_pemverifikasi_hpa = $this->request->getGet('id_pemverifikasi_hpa');

        if ($id_pemverifikasi_hpa) {
            $data = $this->pemverifikasi_hpa->detailspemverifikasi_hpa($id_pemverifikasi_hpa);

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'Coba ulangi kembali..']);
        }
    }

    public function edit()
    {
        $id_pemverifikasi_hpa = $this->request->getGet('id_pemverifikasi_hpa');

        if (!$id_pemverifikasi_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemverifikasi tidak ditemukan.');
        }

        // Ambil data pemverifikasi
        $pemverifikasiData = $this->pemverifikasi_hpa->find($id_pemverifikasi_hpa);

        if (!$pemverifikasiData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pemverifikasi tidak ditemukan.');
        }

        // Ambil data user
        $users = $this->userModel->findAll();

        $data = [
            'pemverifikasiData' => $pemverifikasiData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('Hpa/edit_proses/edit_pemverifikasi', $data);
    }

    public function update()
    {
        $id_pemverifikasi_hpa = $this->request->getPost('id_pemverifikasi_hpa');

        if (!$id_pemverifikasi_hpa) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pemverifikasi_hpa = $this->request->getPost('mulai_pemverifikasi_hpa_date') . ' ' . $this->request->getPost('mulai_pemverifikasi_hpa_time');
        $selesai_pemverifikasi_hpa = $this->request->getPost('selesai_pemverifikasi_hpa_date') . ' ' . $this->request->getPost('selesai_pemverifikasi_hpa_time');

        $id_user = $this->request->getPost('id_user_pemverifikasi_hpa');

        $data = [
            'id_user_pemverifikasi_hpa' => $id_user === '' ? null : $id_user,
            'status_pemverifikasi_hpa'  => $this->request->getPost('status_pemverifikasi_hpa'),
            'mulai_pemverifikasi_hpa'   => $mulai_pemverifikasi_hpa,
            'selesai_pemverifikasi_hpa' => $selesai_pemverifikasi_hpa,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemverifikasi_hpa->update($id_pemverifikasi_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pemverifikasi_hpa/edit?id_pemverifikasi_hpa=' . $id_pemverifikasi_hpa))
            ->with('success', 'Data berhasil diperbarui.');
    }
}
