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
    protected $penerimaan_ihc;
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
        $this->penerimaan_ihc = new Penerimaan_ihc();
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
        $penerimaanData_ihc = $this->penerimaan_ihc->getPenerimaan_ihc();
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
                $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                $total_nilai_mutu_ihc = (string) ($this->request->getPost('total_nilai_mutu_ihc') ?? '0');
                $this->processAction($action, $id_penerimaan_ihc, $id_ihc, $id_user, $id_mutu_ihc, $indikator_1, $indikator_2, $indikator_3, $indikator_4, $total_nilai_mutu_ihc);
            }

            return redirect()->to('penerimaan_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penerimaan_ihc, $id_ihc, $id_user, $id_mutu_ihc, $indikator_1, $indikator_2, $indikator_3, $indikator_4, $total_nilai_mutu_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Penerimaan']);
                    $this->penerimaan_ihc->update($id_penerimaan_ihc, [
                        'id_user_penerimaan_ihc' => $id_user,
                        'status_penerimaan_ihc' => 'Proses Penerimaan',
                        'mulai_penerimaan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->penerimaan_ihc->update($id_penerimaan_ihc, [
                        'id_user_penerimaan_ihc' => $id_user,
                        'status_penerimaan_ihc' => 'Selesai Penerimaan',
                        'selesai_penerimaan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    $this->Mutu_ihc->update($id_mutu_ihc, [
                        'indikator_1' => $indikator_1,
                        'indikator_2' => $indikator_2,
                        'indikator_3' => $indikator_3,
                        'indikator_4' => $indikator_4,
                        'total_nilai_mutu_ihc' => $total_nilai_mutu_ihc + $indikator_1 + $indikator_2 + $indikator_3 + $indikator_4,
                    ]);
                    break;
                case 'reset':
                    $this->penerimaan_ihc->update($id_penerimaan_ihc, [
                        'id_user_penerimaan_ihc' => null,
                        'status_penerimaan_ihc' => 'Belum Penerimaan',
                        'mulai_penerimaan_ihc' => null,
                        'selesai_penerimaan_ihc' => null,
                    ]);
                    $this->Mutu_ihc->update($id_mutu_ihc, [
                        'indikator_1' => '0',
                        'indikator_2' => '0',
                        'indikator_3' => '0',
                        'indikator_4' => '0',
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
        $id_penerimaan_ihc = $this->request->getGet('id_penerimaan_ihc');

        if ($id_penerimaan_ihc) {
            $data = $this->penerimaan_ihc->detailspenerimaan_ihc($id_penerimaan_ihc);

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
        $id_penerimaan_ihc = $this->request->getGet('id_penerimaan_ihc');

        if (!$id_penerimaan_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penerimaan tidak ditemukan.');
        }

        // Ambil data penerimaan
        $penerimaanData = $this->penerimaan_ihc->find($id_penerimaan_ihc);

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

        return view('ihc/edit_proses/edit_penerimaan', $data);
    }

    public function update()
    {
        $id_penerimaan_ihc = $this->request->getPost('id_penerimaan_ihc');

        if (!$id_penerimaan_ihc) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_penerimaan_ihc = $this->request->getPost('mulai_penerimaan_ihc_date') . ' ' . $this->request->getPost('mulai_penerimaan_ihc_time');
        $selesai_penerimaan_ihc = $this->request->getPost('selesai_penerimaan_ihc_date') . ' ' . $this->request->getPost('selesai_penerimaan_ihc_time');

        $id_user = $this->request->getPost('id_user_penerimaan_ihc');

        $data = [
            'id_user_penerimaan_ihc' => $id_user === '' ? null : $id_user,
            'status_penerimaan_ihc'  => $this->request->getPost('status_penerimaan_ihc'),
            'mulai_penerimaan_ihc'   => $mulai_penerimaan_ihc,
            'selesai_penerimaan_ihc' => $selesai_penerimaan_ihc,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->penerimaan_ihc->update($id_penerimaan_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penerimaan_ihc/edit?id_penerimaan_ihc=' . $id_penerimaan_ihc))
            ->with('success', 'Data berhasil diperbarui.');
    }
}
