<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Penerimaan_hpa;
use App\Models\Hpa\Proses\Pemotongan_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Penerimaan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $penerimaan_hpa;
    protected $pemotongan_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penerimaan_hpa = new Penerimaan_hpa();
        $this->pemotongan_hpa = new Pemotongan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penerimaanData_hpa = $this->penerimaan_hpa->getPenerimaan_hpa();
        $data = [
            'id_user' => session()->get('id_user'),
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
                list($id_penerimaan_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $indikator_1 = (string) ($this->request->getPost('indikator_1') ?? '0');
                $indikator_2 = (string) ($this->request->getPost('indikator_2') ?? '0');
                $total_nilai_mutu_hpa = (string) ($this->request->getPost('total_nilai_mutu_hpa') ?? '0');
                $this->processAction($action, $id_penerimaan_hpa, $id_hpa, $id_user, $id_mutu_hpa, $indikator_1, $indikator_2, $total_nilai_mutu_hpa);
            }

            return redirect()->to('penerimaan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penerimaan_hpa, $id_hpa, $id_user, $id_mutu_hpa, $indikator_1, $indikator_2, $total_nilai_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Penerimaan']);
                    $this->penerimaan_hpa->update($id_penerimaan_hpa, [
                        'id_user_penerimaan_hpa' => $id_user,
                        'status_penerimaan_hpa' => 'Proses Penerimaan',
                        'mulai_penerimaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->penerimaan_hpa->update($id_penerimaan_hpa, [
                        'id_user_penerimaan_hpa' => $id_user,
                        'status_penerimaan_hpa' => 'Selesai Penerimaan',
                        'selesai_penerimaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    $this->mutu_hpa->update($id_mutu_hpa, [
                        'indikator_1' => $indikator_1,
                        'indikator_2' => $indikator_2,
                        'total_nilai_mutu_hpa' => $total_nilai_mutu_hpa + $indikator_1 + $indikator_2,
                    ]);
                    break;
                case 'reset':
                    $this->penerimaan_hpa->update($id_penerimaan_hpa, [
                        'id_user_penerimaan_hpa' => null,
                        'status_penerimaan_hpa' => 'Belum Penerimaan',
                        'mulai_penerimaan_hpa' => null,
                        'selesai_penerimaan_hpa' => null,
                    ]);
                    $this->mutu_hpa->update($id_mutu_hpa, [
                        'indikator_1' => '0',
                        'indikator_2' => '0',
                        'total_nilai_mutu_hpa' => '0',
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

    public function penerimaan_details()
    {
        $id_penerimaan_hpa = $this->request->getGet('id_penerimaan_hpa');

        if ($id_penerimaan_hpa) {
            $data = $this->penerimaan_hpa->detailspenerimaan_hpa($id_penerimaan_hpa);

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
        $id_penerimaan_hpa = $this->request->getGet('id_penerimaan_hpa');

        if (!$id_penerimaan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penerimaan tidak ditemukan.');
        }

        // Ambil data penerimaan
        $penerimaanData = $this->penerimaan_hpa->find($id_penerimaan_hpa);

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

        return view('Hpa/edit_proses/edit_penerimaan', $data);
    }

    public function update()
    {
        $id_penerimaan_hpa = $this->request->getPost('id_penerimaan_hpa');

        if (!$id_penerimaan_hpa) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_penerimaan_hpa = $this->request->getPost('mulai_penerimaan_hpa_date') . ' ' . $this->request->getPost('mulai_penerimaan_hpa_time');
        $selesai_penerimaan_hpa = $this->request->getPost('selesai_penerimaan_hpa_date') . ' ' . $this->request->getPost('selesai_penerimaan_hpa_time');

        $id_user = $this->request->getPost('id_user_penerimaan_hpa');

        $data = [
            'id_user_penerimaan_hpa' => $id_user === '' ? null : $id_user,
            'status_penerimaan_hpa'  => $this->request->getPost('status_penerimaan_hpa'),
            'mulai_penerimaan_hpa'   => $mulai_penerimaan_hpa,
            'selesai_penerimaan_hpa' => $selesai_penerimaan_hpa,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->penerimaan_hpa->update($id_penerimaan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penerimaan_hpa/edit?id_penerimaan_hpa=' . $id_penerimaan_hpa))
            ->with('success', 'Data berhasil diperbarui.');
    }
}
