<?php

namespace App\Controllers\frs\Proses;

use App\Controllers\BaseController;
use App\Models\frs\frsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\frs\Proses\penerimaan_frs;
use App\Models\frs\Proses\Pembacaan_frs;
use App\Models\frs\Proses\Penulisan_frs;
use App\Models\frs\Proses\Pemverifikasi_frs;
use App\Models\frs\Proses\Authorized_frs;
use App\Models\frs\Proses\Pencetakan_frs;
use App\Models\frs\Mutu_frs;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

class Penerimaan extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $penerimaan_frs;
    protected $Pembacaan_frs;
    protected $Penulisan_frs;
    protected $Pemverifikasi_frs;
    protected $Authorized_frs;
    protected $Pencetakan_frs;
    protected $Mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penerimaan_frs = new penerimaan_frs();
        $this->Pembacaan_frs = new Pembacaan_frs();
        $this->Penulisan_frs = new Penulisan_frs();
        $this->Pemverifikasi_frs = new Pemverifikasi_frs();
        $this->Authorized_frs = new Authorized_frs();
        $this->Pencetakan_frs = new Pencetakan_frs();
        $this->Mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penerimaanData_frs = $this->penerimaan_frs->getpenerimaan_frs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penerimaanDatafrs' => $penerimaanData_frs,
        ];

        return view('frs/Proses/penerimaan', $data);
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
                list($id_penerimaan_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                // $indikator_1 = (string) ($this->request->getPost('indikator_1') ?? '0');
                // $indikator_2 = (string) ($this->request->getPost('indikator_2') ?? '0');
                // $total_nilai_mutu_frs = $this->request->getPost('total_nilai_mutu_frs');
                $this->processAction($action, $id_penerimaan_frs, $id_frs, $id_user, $id_mutu_frs);
            }

            return redirect()->to('penerimaan_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penerimaan_frs, $id_frs, $id_user, $id_mutu_frs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Penerimaan']);
                    $this->penerimaan_frs->update($id_penerimaan_frs, [
                        'id_user_penerimaan_frs' => $id_user,
                        'status_penerimaan_frs' => 'Proses Penerimaan',
                        'mulai_penerimaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->penerimaan_frs->update($id_penerimaan_frs, [
                        'id_user_penerimaan_frs' => $id_user,
                        'status_penerimaan_frs' => 'Selesai Penerimaan',
                        'selesai_penerimaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->penerimaan_frs->update($id_penerimaan_frs, [
                        'id_user_penerimaan_frs' => null,
                        'status_penerimaan_frs' => 'Belum Penerimaan',
                        'mulai_penerimaan_frs' => null,
                        'selesai_penerimaan_frs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Pembacaan']);
                    $pembacaanData = [
                        'id_frs'            => $id_frs,
                        'status_pembacaan_frs' => 'Belum Pembacaan',
                    ];
                    if (!$this->Pembacaan_frs->insert($pembacaanData)) {
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
        $id_penerimaan_frs = $this->request->getGet('id_penerimaan_frs');

        if ($id_penerimaan_frs) {
            $data = $this->penerimaan_frs->detailspenerimaan_frs($id_penerimaan_frs);

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
        $id_penerimaan_frs = $this->request->getGet('id_penerimaan_frs');

        if (!$id_penerimaan_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penerimaan tidak ditemukan.');
        }

        // Ambil data penerimaan
        $penerimaanData = $this->penerimaan_frs->find($id_penerimaan_frs);

        if (!$penerimaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penerimaan tidak ditemukan.');
        }

        // Ambil data user
        $users = $this->userModel->findAll();

        $data = [
            'penerimaanData' => $penerimaanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        
        return view('frs/edit_proses/edit_penerimaan', $data);
    }

    public function update()
    {
        $id_penerimaan_frs = $this->request->getPost('id_penerimaan_frs');

        if (!$id_penerimaan_frs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_penerimaan_frs = $this->request->getPost('mulai_penerimaan_frs_date') . ' ' . $this->request->getPost('mulai_penerimaan_frs_time');
        $selesai_penerimaan_frs = $this->request->getPost('selesai_penerimaan_frs_date') . ' ' . $this->request->getPost('selesai_penerimaan_frs_time');

        $id_user = $this->request->getPost('id_user_penerimaan_frs');

        $data = [
            'id_user_penerimaan_frs' => $id_user === '' ? null : $id_user,
            'status_penerimaan_frs'  => $this->request->getPost('status_penerimaan_frs'),
            'mulai_penerimaan_frs'   => $mulai_penerimaan_frs,
            'selesai_penerimaan_frs' => $selesai_penerimaan_frs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->penerimaan_frs->update($id_penerimaan_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penerimaan_frs/edit?id_penerimaan_frs=' . $id_penerimaan_frs))
            ->with('success', 'Data berhasil diperbarui.');
    }
}
