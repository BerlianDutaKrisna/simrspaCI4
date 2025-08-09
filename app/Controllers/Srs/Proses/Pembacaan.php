<?php

namespace App\Controllers\Srs\Proses;

use App\Controllers\BaseController;
use App\Models\Srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\Pembacaan_srs;
use App\Models\Srs\Proses\Penulisan_srs;
use App\Models\Srs\Mutu_srs;
use Exception;

class Pembacaan extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $pembacaan_srs;
    protected $penulisan_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pembacaan_srs = new Pembacaan_srs();
        $this->penulisan_srs = new Penulisan_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pembacaanData_srs = $this->pembacaan_srs->getpembacaan_srs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pembacaanDatasrs' => $pembacaanData_srs,
        ];
        
        return view('srs/Proses/pembacaan', $data);
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
                list($id_pembacaan_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                // $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                // $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                // $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                // $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                // $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                // $total_nilai_mutu_srs = (string) ($this->request->getPost('total_nilai_mutu_srs') ?? '0');
                $this->processAction($action, $id_pembacaan_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('pembacaan_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pembacaan_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pembacaan_srs->update($id_pembacaan_srs, [
                        'id_user_pembacaan_srs' => $id_user,
                        'status_pembacaan_srs' => 'Proses Pembacaan',
                        'mulai_pembacaan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pembacaan_srs->update($id_pembacaan_srs, [
                        'id_user_pembacaan_srs' => $id_user,
                        'status_pembacaan_srs' => 'Selesai Pembacaan',
                        'selesai_pembacaan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pembacaan_srs->update($id_pembacaan_srs, [
                        'id_user_pembacaan_srs' => null,
                        'status_pembacaan_srs' => 'Belum Pembacaan',
                        'mulai_pembacaan_srs' => null,
                        'selesai_pembacaan_srs' => null,
                    ]);
                    $this->mutu_srs->update($id_mutu_srs, [
                        'indikator_4' => '0',
                        'indikator_5' => '0',
                        'indikator_6' => '0',
                        'indikator_7' => '0',
                        'indikator_8' => '0',
                        'total_nilai_mutu_srs' => '30',
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Penulisan']);
                    $penulisanData = [
                        'id_srs'            => $id_srs,
                        'status_penulisan_srs' => 'Belum Penulisan',
                    ];
                    if (!$this->penulisan_srs->insert($penulisanData)) {
                        throw new Exception('Gagal menyimpan data penulisan.');
                    }
                    break;
                case 'kembalikan':
                    $this->pembacaan_srs->delete($id_pembacaan_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Pewarnaan',
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
        $id_pembacaan_srs = $this->request->getGet('id_pembacaan_srs');

        if ($id_pembacaan_srs) {
            $data = $this->pembacaan_srs->detailspembacaan_srs($id_pembacaan_srs);

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
        $id_pembacaan_srs = $this->request->getGet('id_pembacaan_srs');

        if (!$id_pembacaan_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pembacaan tidak ditemukan.');
        }

        // Ambil data pembacaan
        $pembacaanData = $this->pembacaan_srs->find($id_pembacaan_srs);

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

        return view('srs/edit_proses/edit_pembacaan', $data);
    }

    public function update()
    {
        $id_pembacaan_srs = $this->request->getPost('id_pembacaan_srs');

        if (!$id_pembacaan_srs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pembacaan_srs = $this->request->getPost('mulai_pembacaan_srs_date') . ' ' . $this->request->getPost('mulai_pembacaan_srs_time');
        $selesai_pembacaan_srs = $this->request->getPost('selesai_pembacaan_srs_date') . ' ' . $this->request->getPost('selesai_pembacaan_srs_time');

        $id_user = $this->request->getPost('id_user_dokter_pembacaan_srs');
        $data = [
            'id_user_dokter_pembacaan_srs' => $id_user === '' ? null : $id_user,
            'status_pembacaan_srs'  => $this->request->getPost('status_pembacaan_srs'),
            'mulai_pembacaan_srs'   => $mulai_pembacaan_srs,
            'selesai_pembacaan_srs' => $selesai_pembacaan_srs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pembacaan_srs->update($id_pembacaan_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pembacaan_srs/edit?id_pembacaan_srs=' . $id_pembacaan_srs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pembacaan = $this->request->getPost('id_pembacaan');
            $id_srs = $this->request->getPost('id_srs');
            if (!$id_pembacaan || !$id_srs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pembacaan
            if ($this->pembacaan_srs->delete($id_pembacaan)) {
                // Update status_srs ke tahap sebelumnya 
                $this->srsModel->update($id_srs, [
                    'status_srs' => 'Pewarnaan',
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
