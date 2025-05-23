<?php

namespace App\Controllers\frs\Proses;

use App\Controllers\BaseController;
use App\Models\Frs\frsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Frs\Proses\Pembacaan_frs;
use App\Models\Frs\Proses\Penulisan_frs;
use App\Models\Frs\Mutu_frs;
use Exception;

class Pembacaan extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $pembacaan_frs;
    protected $penulisan_frs;
    protected $mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pembacaan_frs = new Pembacaan_frs();
        $this->penulisan_frs = new Penulisan_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pembacaanData_frs = $this->pembacaan_frs->getpembacaan_frs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pembacaanDatafrs' => $pembacaanData_frs,
        ];
        
        return view('frs/Proses/pembacaan', $data);
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
                list($id_pembacaan_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                // $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                // $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                // $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                // $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                // $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                // $total_nilai_mutu_frs = (string) ($this->request->getPost('total_nilai_mutu_frs') ?? '0');
                $this->processAction($action, $id_pembacaan_frs, $id_frs, $id_user, $id_mutu_frs);
            }

            return redirect()->to('pembacaan_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pembacaan_frs, $id_frs, $id_user, $id_mutu_frs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pembacaan_frs->update($id_pembacaan_frs, [
                        'id_user_pembacaan_frs' => $id_user,
                        'status_pembacaan_frs' => 'Proses Pembacaan',
                        'mulai_pembacaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pembacaan_frs->update($id_pembacaan_frs, [
                        'id_user_pembacaan_frs' => $id_user,
                        'status_pembacaan_frs' => 'Selesai Pembacaan',
                        'selesai_pembacaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pembacaan_frs->update($id_pembacaan_frs, [
                        'id_user_pembacaan_frs' => null,
                        'status_pembacaan_frs' => 'Belum Pembacaan',
                        'mulai_pembacaan_frs' => null,
                        'selesai_pembacaan_frs' => null,
                    ]);
                    $this->mutu_frs->update($id_mutu_frs, [
                        'indikator_4' => '0',
                        'indikator_5' => '0',
                        'indikator_6' => '0',
                        'indikator_7' => '0',
                        'indikator_8' => '0',
                        'total_nilai_mutu_frs' => '30',
                    ]);
                    break;
                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Penulisan']);
                    $penulisanData = [
                        'id_frs'            => $id_frs,
                        'status_penulisan_frs' => 'Belum Penulisan',
                    ];
                    if (!$this->penulisan_frs->insert($penulisanData)) {
                        throw new Exception('Gagal menyimpan data penulisan.');
                    }
                    break;
                case 'kembalikan':
                    $this->pembacaan_frs->delete($id_pembacaan_frs);
                    $this->frsModel->update($id_frs, [
                        'status_frs' => 'Penerimaan',
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
        $id_pembacaan_frs = $this->request->getGet('id_pembacaan_frs');

        if ($id_pembacaan_frs) {
            $data = $this->pembacaan_frs->detailspembacaan_frs($id_pembacaan_frs);

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
        $id_pembacaan_frs = $this->request->getGet('id_pembacaan_frs');

        if (!$id_pembacaan_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pembacaan tidak ditemukan.');
        }

        // Ambil data pembacaan
        $pembacaanData = $this->pembacaan_frs->find($id_pembacaan_frs);

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

        return view('frs/edit_proses/edit_pembacaan', $data);
    }

    public function update()
    {
        $id_pembacaan_frs = $this->request->getPost('id_pembacaan_frs');

        if (!$id_pembacaan_frs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pembacaan_frs = $this->request->getPost('mulai_pembacaan_frs_date') . ' ' . $this->request->getPost('mulai_pembacaan_frs_time');
        $selesai_pembacaan_frs = $this->request->getPost('selesai_pembacaan_frs_date') . ' ' . $this->request->getPost('selesai_pembacaan_frs_time');

        $id_user = $this->request->getPost('id_user_dokter_pembacaan_frs');
        $data = [
            'id_user_dokter_pembacaan_frs' => $id_user === '' ? null : $id_user,
            'status_pembacaan_frs'  => $this->request->getPost('status_pembacaan_frs'),
            'mulai_pembacaan_frs'   => $mulai_pembacaan_frs,
            'selesai_pembacaan_frs' => $selesai_pembacaan_frs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pembacaan_frs->update($id_pembacaan_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pembacaan_frs/edit?id_pembacaan_frs=' . $id_pembacaan_frs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pembacaan = $this->request->getPost('id_pembacaan');
            $id_frs = $this->request->getPost('id_frs');
            if (!$id_pembacaan || !$id_frs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pembacaan
            if ($this->pembacaan_frs->delete($id_pembacaan)) {
                // Update status_frs ke tahap sebelumnya 
                $this->frsModel->update($id_frs, [
                    'status_frs' => 'Pewarnaan',
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
