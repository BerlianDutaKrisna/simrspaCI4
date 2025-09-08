<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Pembacaan_hpa;
use App\Models\Hpa\Proses\Penulisan_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Pembacaan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $pembacaan_hpa;
    protected $penulisan_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pembacaan_hpa = new Pembacaan_hpa();
        $this->penulisan_hpa = new Penulisan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        // Ambil nama_user dari session
        $namaUser = $this->session->get('nama_user');

        // Tentukan query berdasarkan nama_user
        if ($namaUser === "dr. Vinna Chrisdianti, Sp.PA") {
            $pembacaanData_hpa = $this->pembacaan_hpa->getpembacaan_hpa_vinna();
        } elseif ($namaUser === "dr. Ayu Tyasmara Pratiwi, Sp.PA") {
            $pembacaanData_hpa = $this->pembacaan_hpa->getpembacaan_hpa_ayu();
        } else {
            $pembacaanData_hpa = $this->pembacaan_hpa->getpembacaan_hpa();
        }

        // Kirim data ke view
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $namaUser,
            'counts' => $this->getCounts(),
            'pembacaanDatahpa' => $pembacaanData_hpa,
        ];

        return view('Hpa/Proses/pembacaan', $data);
    }

    public function proses_pembacaan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pembacaan_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                $total_nilai_mutu_hpa = (string) ($this->request->getPost('total_nilai_mutu_hpa') ?? '0');
                $this->processAction($action, $id_pembacaan_hpa, $id_hpa, $id_user, $id_mutu_hpa, $indikator_4, $indikator_5, $indikator_6, $indikator_7, $indikator_8, $total_nilai_mutu_hpa);
            }

            return redirect()->to('pembacaan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pembacaan_hpa, $id_hpa, $id_user, $id_mutu_hpa, $indikator_4, $indikator_5, $indikator_6, $indikator_7, $indikator_8, $total_nilai_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pembacaan_hpa->update($id_pembacaan_hpa, [
                        'id_user_pembacaan_hpa' => $id_user,
                        'status_pembacaan_hpa' => 'Proses Pembacaan',
                        'mulai_pembacaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pembacaan_hpa->update($id_pembacaan_hpa, [
                        'id_user_pembacaan_hpa' => $id_user,
                        'status_pembacaan_hpa' => 'Selesai Pembacaan',
                        'selesai_pembacaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    $this->mutu_hpa->update($id_mutu_hpa, [
                        'indikator_4' => $indikator_4,
                        'indikator_5' => $indikator_5,
                        'indikator_6' => $indikator_6,
                        'indikator_7' => $indikator_7,
                        'indikator_8' => $indikator_8,
                        'total_nilai_mutu_hpa' => $total_nilai_mutu_hpa + $indikator_4 + $indikator_5 + $indikator_6 + $indikator_7 + $indikator_8,
                    ]);
                    break;
                case 'reset':
                    $this->pembacaan_hpa->update($id_pembacaan_hpa, [
                        'id_user_pembacaan_hpa' => null,
                        'status_pembacaan_hpa' => 'Belum Pembacaan',
                        'mulai_pembacaan_hpa' => null,
                        'selesai_pembacaan_hpa' => null,
                    ]);
                    $this->mutu_hpa->update($id_mutu_hpa, [
                        'indikator_4' => '0',
                        'indikator_5' => '0',
                        'indikator_6' => '0',
                        'indikator_7' => '0',
                        'indikator_8' => '0',
                        'total_nilai_mutu_hpa' => '30',
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Penulisan']);
                    $penulisanData = [
                        'id_hpa'            => $id_hpa,
                        'status_penulisan_hpa' => 'Belum Penulisan',
                    ];
                    if (!$this->penulisan_hpa->insert($penulisanData)) {
                        throw new Exception('Gagal menyimpan data penulisan.');
                    }
                    break;
                case 'kembalikan':
                    $this->pembacaan_hpa->delete($id_pembacaan_hpa);
                    $this->hpaModel->update($id_hpa, [
                        'status_hpa' => 'Pewarnaan',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pembacaan_details()
    {
        $id_pembacaan_hpa = $this->request->getGet('id_pembacaan_hpa');

        if ($id_pembacaan_hpa) {
            $data = $this->pembacaan_hpa->detailspembacaan_hpa($id_pembacaan_hpa);

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
        $id_pembacaan_hpa = $this->request->getGet('id_pembacaan_hpa');

        if (!$id_pembacaan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pembacaan tidak ditemukan.');
        }

        // Ambil data pembacaan
        $pembacaanData = $this->pembacaan_hpa->find($id_pembacaan_hpa);

        if (!$pembacaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pembacaan tidak ditemukan.');
        }

        // Ambil data user
        $users = $this->userModel->findAll();

        $data = [
            'pembacaanData' => $pembacaanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('Hpa/edit_proses/edit_pembacaan', $data);
    }

    public function update()
    {
        $id_pembacaan_hpa = $this->request->getPost('id_pembacaan_hpa');

        if (!$id_pembacaan_hpa) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pembacaan_hpa = $this->request->getPost('mulai_pembacaan_hpa_date') . ' ' . $this->request->getPost('mulai_pembacaan_hpa_time');
        $selesai_pembacaan_hpa = $this->request->getPost('selesai_pembacaan_hpa_date') . ' ' . $this->request->getPost('selesai_pembacaan_hpa_time');

        $id_user = $this->request->getPost('id_user_dokter_pembacaan_hpa');
        $data = [
            'id_user_dokter_pembacaan_hpa' => $id_user === '' ? null : $id_user,
            'status_pembacaan_hpa'  => $this->request->getPost('status_pembacaan_hpa'),
            'mulai_pembacaan_hpa'   => $mulai_pembacaan_hpa,
            'selesai_pembacaan_hpa' => $selesai_pembacaan_hpa,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pembacaan_hpa->update($id_pembacaan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pembacaan_hpa/edit?id_pembacaan_hpa=' . $id_pembacaan_hpa))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pembacaan = $this->request->getPost('id_pembacaan');
            $id_hpa = $this->request->getPost('id_hpa');
            if (!$id_pembacaan || !$id_hpa) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pembacaan
            if ($this->pembacaan_hpa->delete($id_pembacaan)) {
                // Update status_hpa ke tahap sebelumnya 
                $this->hpaModel->update($id_hpa, [
                    'status_hpa' => 'Pewarnaan',
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
