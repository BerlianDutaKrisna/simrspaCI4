<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Penanaman_hpa;
use App\Models\Hpa\Proses\Pemotongan_tipis_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Penanaman extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $penanaman_hpa;
    protected $pemotongan_tipis_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penanaman_hpa = new Penanaman_hpa();
        $this->pemotongan_tipis_hpa = new Pemotongan_tipis_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penanamanData_hpa = $this->penanaman_hpa->getpenanaman_hpa();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penanamanDatahpa' => $penanamanData_hpa,
        ];

        return view('Hpa/Proses/penanaman', $data);
    }

    public function proses_penanaman()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_penanaman_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $indikator_3 = (string) ($this->request->getPost('indikator_3') ?? '0');
                $total_nilai_mutu_hpa = (string) ($this->request->getPost('total_nilai_mutu_hpa') ?? '0');
                $this->processAction($action, $id_penanaman_hpa, $id_hpa, $id_user, $id_mutu_hpa, $indikator_3, $total_nilai_mutu_hpa);
            }

            return redirect()->to('penanaman_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penanaman_hpa, $id_hpa, $id_user, $id_mutu_hpa, $indikator_3, $total_nilai_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->penanaman_hpa->update($id_penanaman_hpa, [
                        'id_user_penanaman_hpa' => $id_user,
                        'status_penanaman_hpa' => 'Proses Penanaman',
                        'mulai_penanaman_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->penanaman_hpa->update($id_penanaman_hpa, [
                        'id_user_penanaman_hpa' => $id_user,
                        'status_penanaman_hpa' => 'Selesai Penanaman',
                        'selesai_penanaman_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    $this->mutu_hpa->update($id_mutu_hpa, [
                        'indikator_3' => $indikator_3,
                        'total_nilai_mutu_hpa' => $total_nilai_mutu_hpa + $indikator_3,
                    ]);
                    break;
                case 'reset':
                    $this->penanaman_hpa->update($id_penanaman_hpa, [
                        'id_user_penanaman_hpa' => null,
                        'status_penanaman_hpa' => 'Belum Penanaman',
                        'mulai_penanaman_hpa' => null,
                        'selesai_penanaman_hpa' => null,
                    ]);
                    break;
                    $this->mutu_hpa->update($id_mutu_hpa, [
                        'indikator_3' => '0',
                        'total_nilai_mutu_hpa' => '20',
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pemotongan Tipis']);
                    $pemotongan_tipisData = [
                        'id_hpa'            => $id_hpa,
                        'status_pemotongan_tipis_hpa' => 'Belum Pemotongan Tipis',
                    ];
                    if (!$this->pemotongan_tipis_hpa->insert($pemotongan_tipisData)) {
                        throw new Exception('Gagal menyimpan data pemotongan_tipis.');
                    }
                    break;
                case 'kembalikan':
                    $this->penanaman_hpa->delete($id_penanaman_hpa);
                    $this->hpaModel->update($id_hpa, [
                        'status_hpa' => 'Pemprosesan',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function penanaman_details()
    {
        $id_penanaman_hpa = $this->request->getGet('id_penanaman_hpa');

        if ($id_penanaman_hpa) {
            $data = $this->penanaman_hpa->detailspenanaman_hpa($id_penanaman_hpa);

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
        $id_penanaman_hpa = $this->request->getGet('id_penanaman_hpa');

        if (!$id_penanaman_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penanaman tidak ditemukan.');
        }

        // Ambil data penanaman
        $penanamanData = $this->penanaman_hpa->find($id_penanaman_hpa);

        if (!$penanamanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penanaman tidak ditemukan.');
        }

        // Ambil data user
        $users = $this->userModel->findAll();

        $data = [
            'penanamanData' => $penanamanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('Hpa/edit_proses/edit_penanaman', $data);
    }

    public function update()
    {
        $id_penanaman_hpa = $this->request->getPost('id_penanaman_hpa');

        if (!$id_penanaman_hpa) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_penanaman_hpa = $this->request->getPost('mulai_penanaman_hpa_date') . ' ' . $this->request->getPost('mulai_penanaman_hpa_time');
        $selesai_penanaman_hpa = $this->request->getPost('selesai_penanaman_hpa_date') . ' ' . $this->request->getPost('selesai_penanaman_hpa_time');

        $id_user = $this->request->getPost('id_user_penanaman_hpa');

        $data = [
            'id_user_penanaman_hpa' => $id_user === '' ? null : $id_user,
            'status_penanaman_hpa'  => $this->request->getPost('status_penanaman_hpa'),
            'mulai_penanaman_hpa'   => $mulai_penanaman_hpa,
            'selesai_penanaman_hpa' => $selesai_penanaman_hpa,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->penanaman_hpa->update($id_penanaman_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penanaman_hpa/edit?id_penanaman_hpa=' . $id_penanaman_hpa))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_penanaman = $this->request->getPost('id_penanaman');
            $id_hpa = $this->request->getPost('id_hpa');
            if (!$id_penanaman || !$id_hpa) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data penanaman
            if ($this->penanaman_hpa->delete($id_penanaman)) {
                // Update status_hpa ke tahap sebelumnya 
                $this->hpaModel->update($id_hpa, [
                    'status_hpa' => 'Pemprosesan',
                ]);
                return $this->response->setJSON(['success' => true]);
            } else {
                throw new \Exception('Gagal menghapus data.');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
