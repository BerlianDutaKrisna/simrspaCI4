<?php

namespace App\Controllers\Srs\Proses;

use App\Controllers\BaseController;
use App\Models\Srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\Penulisan_srs;
use App\Models\Srs\Proses\Pemverifikasi_srs;
use App\Models\Srs\Mutu_srs;
use Exception;

class Penulisan extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $penulisan_srs;
    protected $pemverifikasi_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penulisan_srs = new Penulisan_srs();
        $this->pemverifikasi_srs = new Pemverifikasi_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penulisanData_srs = $this->penulisan_srs->getpenulisan_srs();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penulisanDatasrs' => $penulisanData_srs,
        ];
        
        return view('srs/Proses/penulisan', $data);
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
                list($id_penulisan_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                $this->processAction($action, $id_penulisan_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('penulisan_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penulisan_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->penulisan_srs->update($id_penulisan_srs, [
                        'id_user_penulisan_srs' => $id_user,
                        'status_penulisan_srs' => 'Proses Penulisan',
                        'mulai_penulisan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->penulisan_srs->update($id_penulisan_srs, [
                        'id_user_penulisan_srs' => $id_user,
                        'status_penulisan_srs' => 'Selesai Penulisan',
                        'selesai_penulisan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->penulisan_srs->update($id_penulisan_srs, [
                        'id_user_penulisan_srs' => null,
                        'status_penulisan_srs' => 'Belum Penulisan',
                        'mulai_penulisan_srs' => null,
                        'selesai_penulisan_srs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Pemverifikasi']);
                    $pemverifikasiData = [
                        'id_srs'            => $id_srs,
                        'status_pemverifikasi_srs' => 'Belum Pemverifikasi',
                    ];
                    if (!$this->pemverifikasi_srs->insert($pemverifikasiData)) {
                        throw new Exception('Gagal menyimpan data pemverifikasi.');
                    }
                    break;
                case 'kembalikan':
                    $this->penulisan_srs->delete($id_penulisan_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Pembacaan',
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
        $id_penulisan_srs = $this->request->getGet('id_penulisan_srs');

        if ($id_penulisan_srs) {
            $data = $this->penulisan_srs->detailspenulisan_srs($id_penulisan_srs);

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
        $id_penulisan_srs = $this->request->getGet('id_penulisan_srs');

        if (!$id_penulisan_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penulisan tidak ditemukan.');
        }

        // Ambil data penulisan
        $penulisanData = $this->penulisan_srs->find($id_penulisan_srs);

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

        return view('srs/edit_proses/edit_penulisan', $data);
    }

    public function update()
    {
        $id_penulisan_srs = $this->request->getPost('id_penulisan_srs');

        if (!$id_penulisan_srs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_penulisan_srs = $this->request->getPost('mulai_penulisan_srs_date') . ' ' . $this->request->getPost('mulai_penulisan_srs_time');
        $selesai_penulisan_srs = $this->request->getPost('selesai_penulisan_srs_date') . ' ' . $this->request->getPost('selesai_penulisan_srs_time');

        $id_user = $this->request->getPost('id_user_penulisan_srs');

        $data = [
            'id_user_penulisan_srs' => $id_user === '' ? null : $id_user,
            'status_penulisan_srs'  => $this->request->getPost('status_penulisan_srs'),
            'mulai_penulisan_srs'   => $mulai_penulisan_srs,
            'selesai_penulisan_srs' => $selesai_penulisan_srs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->penulisan_srs->update($id_penulisan_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penulisan_srs/edit?id_penulisan_srs=' . $id_penulisan_srs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_penulisan = $this->request->getPost('id_penulisan');
            $id_srs = $this->request->getPost('id_srs');
            if (!$id_penulisan || !$id_srs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data penulisan
            if ($this->penulisan_srs->delete($id_penulisan)) {
                // Update status_srs ke tahap sebelumnya 
                $this->srsModel->update($id_srs, [
                    'status_srs' => 'Pembacaan',
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
