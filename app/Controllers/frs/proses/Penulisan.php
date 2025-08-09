<?php

namespace App\Controllers\Frs\Proses;

use App\Controllers\BaseController;
use App\Models\Frs\frsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Frs\Proses\Penulisan_frs;
use App\Models\Frs\Proses\Pemverifikasi_frs;
use App\Models\Frs\Mutu_frs;
use Exception;

class Penulisan extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $penulisan_frs;
    protected $pemverifikasi_frs;
    protected $mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penulisan_frs = new Penulisan_frs();
        $this->pemverifikasi_frs = new Pemverifikasi_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penulisanData_frs = $this->penulisan_frs->getpenulisan_frs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penulisanDatafrs' => $penulisanData_frs,
        ];
        
        return view('frs/Proses/penulisan', $data);
    }

    public function proses_penulisan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_penulisan_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                $this->processAction($action, $id_penulisan_frs, $id_frs, $id_user, $id_mutu_frs);
            }

            return redirect()->to('penulisan_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penulisan_frs, $id_frs, $id_user, $id_mutu_frs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->penulisan_frs->update($id_penulisan_frs, [
                        'id_user_penulisan_frs' => $id_user,
                        'status_penulisan_frs' => 'Proses Penulisan',
                        'mulai_penulisan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->penulisan_frs->update($id_penulisan_frs, [
                        'id_user_penulisan_frs' => $id_user,
                        'status_penulisan_frs' => 'Selesai Penulisan',
                        'selesai_penulisan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->penulisan_frs->update($id_penulisan_frs, [
                        'id_user_penulisan_frs' => null,
                        'status_penulisan_frs' => 'Belum Penulisan',
                        'mulai_penulisan_frs' => null,
                        'selesai_penulisan_frs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Pemverifikasi']);
                    $pemverifikasiData = [
                        'id_frs'            => $id_frs,
                        'status_pemverifikasi_frs' => 'Belum Pemverifikasi',
                    ];
                    if (!$this->pemverifikasi_frs->insert($pemverifikasiData)) {
                        throw new Exception('Gagal menyimpan data pemverifikasi.');
                    }
                    break;
                case 'kembalikan':
                    $this->penulisan_frs->delete($id_penulisan_frs);
                    $this->frsModel->update($id_frs, [
                        'status_frs' => 'Pembacaan',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function penulisan_details()
    {
        $id_penulisan_frs = $this->request->getGet('id_penulisan_frs');

        if ($id_penulisan_frs) {
            $data = $this->penulisan_frs->detailspenulisan_frs($id_penulisan_frs);

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
        $id_penulisan_frs = $this->request->getGet('id_penulisan_frs');

        if (!$id_penulisan_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penulisan tidak ditemukan.');
        }

        // Ambil data penulisan
        $penulisanData = $this->penulisan_frs->find($id_penulisan_frs);

        if (!$penulisanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penulisan tidak ditemukan.');
        }

        // Ambil data user
        $users = $this->userModel->findAll();

        $data = [
            'penulisanData' => $penulisanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('frs/edit_proses/edit_penulisan', $data);
    }

    public function update()
    {
        $id_penulisan_frs = $this->request->getPost('id_penulisan_frs');

        if (!$id_penulisan_frs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_penulisan_frs = $this->request->getPost('mulai_penulisan_frs_date') . ' ' . $this->request->getPost('mulai_penulisan_frs_time');
        $selesai_penulisan_frs = $this->request->getPost('selesai_penulisan_frs_date') . ' ' . $this->request->getPost('selesai_penulisan_frs_time');

        $id_user = $this->request->getPost('id_user_penulisan_frs');

        $data = [
            'id_user_penulisan_frs' => $id_user === '' ? null : $id_user,
            'status_penulisan_frs'  => $this->request->getPost('status_penulisan_frs'),
            'mulai_penulisan_frs'   => $mulai_penulisan_frs,
            'selesai_penulisan_frs' => $selesai_penulisan_frs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->penulisan_frs->update($id_penulisan_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penulisan_frs/edit?id_penulisan_frs=' . $id_penulisan_frs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_penulisan = $this->request->getPost('id_penulisan');
            $id_frs = $this->request->getPost('id_frs');
            if (!$id_penulisan || !$id_frs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data penulisan
            if ($this->penulisan_frs->delete($id_penulisan)) {
                // Update status_frs ke tahap sebelumnya 
                $this->frsModel->update($id_frs, [
                    'status_frs' => 'Pembacaan',
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
