<?php

namespace App\Controllers\srs\Proses;

use App\Controllers\BaseController;
use App\Models\srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\srs\Proses\Pemverifikasi_srs;
use App\Models\srs\Proses\Authorized_srs;
use App\Models\srs\Mutu_srs;
use Exception;

class Pemverifikasi extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $pemverifikasi_srs;
    protected $authorized_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pemverifikasi_srs = new Pemverifikasi_srs();
        $this->authorized_srs = new Authorized_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pemverifikasiData_srs = $this->pemverifikasi_srs->getpemverifikasi_srs();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pemverifikasiDatasrs' => $pemverifikasiData_srs,
        ];
        
        return view('srs/Proses/pemverifikasi', $data);
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
                list($id_pemverifikasi_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                $this->processAction($action, $id_pemverifikasi_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('pemverifikasi_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pemverifikasi_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pemverifikasi_srs->update($id_pemverifikasi_srs, [
                        'id_user_pemverifikasi_srs' => $id_user,
                        'status_pemverifikasi_srs' => 'Proses Pemverifikasi',
                        'mulai_pemverifikasi_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pemverifikasi_srs->update($id_pemverifikasi_srs, [
                        'id_user_pemverifikasi_srs' => $id_user,
                        'status_pemverifikasi_srs' => 'Selesai Pemverifikasi',
                        'selesai_pemverifikasi_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pemverifikasi_srs->update($id_pemverifikasi_srs, [
                        'id_user_pemverifikasi_srs' => null,
                        'status_pemverifikasi_srs' => 'Belum Pemverifikasi',
                        'mulai_pemverifikasi_srs' => null,
                        'selesai_pemverifikasi_srs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Authorized']);
                    $authorizedData = [
                        'id_srs'            => $id_srs,
                        'status_authorized_srs' => 'Belum Authorized',
                    ];
                    if (!$this->authorized_srs->insert($authorizedData)) {
                        throw new Exception('Gagal menyimpan data authorized.');
                    }
                    break;
                case 'kembalikan':
                    $this->pemverifikasi_srs->delete($id_pemverifikasi_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Penulisan',
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
        $id_pemverifikasi_srs = $this->request->getGet('id_pemverifikasi_srs');

        if ($id_pemverifikasi_srs) {
            $data = $this->pemverifikasi_srs->detailspemverifikasi_srs($id_pemverifikasi_srs);

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
        $id_pemverifikasi_srs = $this->request->getGet('id_pemverifikasi_srs');

        if (!$id_pemverifikasi_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemverifikasi tidak ditemukan.');
        }

        // Ambil data pemverifikasi
        $pemverifikasiData = $this->pemverifikasi_srs->find($id_pemverifikasi_srs);

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

        return view('srs/edit_proses/edit_pemverifikasi', $data);
    }

    public function update()
    {
        $id_pemverifikasi_srs = $this->request->getPost('id_pemverifikasi_srs');

        if (!$id_pemverifikasi_srs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pemverifikasi_srs = $this->request->getPost('mulai_pemverifikasi_srs_date') . ' ' . $this->request->getPost('mulai_pemverifikasi_srs_time');
        $selesai_pemverifikasi_srs = $this->request->getPost('selesai_pemverifikasi_srs_date') . ' ' . $this->request->getPost('selesai_pemverifikasi_srs_time');

        $id_user = $this->request->getPost('id_user_pemverifikasi_srs');

        $data = [
            'id_user_pemverifikasi_srs' => $id_user === '' ? null : $id_user,
            'status_pemverifikasi_srs'  => $this->request->getPost('status_pemverifikasi_srs'),
            'mulai_pemverifikasi_srs'   => $mulai_pemverifikasi_srs,
            'selesai_pemverifikasi_srs' => $selesai_pemverifikasi_srs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemverifikasi_srs->update($id_pemverifikasi_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pemverifikasi_srs/edit?id_pemverifikasi_srs=' . $id_pemverifikasi_srs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pemverifikasi = $this->request->getPost('id_pemverifikasi');
            $id_srs = $this->request->getPost('id_srs');
            if (!$id_pemverifikasi || !$id_srs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pemverifikasi
            if ($this->pemverifikasi_srs->delete($id_pemverifikasi)) {
                // Update status_srs ke tahap sebelumnya 
                $this->srsModel->update($id_srs, [
                    'status_srs' => 'Penulisan',
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
