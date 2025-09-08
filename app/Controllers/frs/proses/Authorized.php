<?php

namespace App\Controllers\Frs\Proses;

use App\Controllers\BaseController;
use App\Models\Frs\frsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Frs\Proses\Authorized_frs;
use App\Models\Frs\Proses\Pencetakan_frs;
use App\Models\Frs\Mutu_frs;
use Exception;

class Authorized extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $authorized_frs;
    protected $pencetakan_frs;
    protected $mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->authorized_frs = new Authorized_frs();
        $this->pencetakan_frs = new Pencetakan_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $namaUser = $this->session->get('nama_user');

        // Jika user adalah salah satu dokter, filter data sesuai nama dokter
        if (in_array($namaUser, ["dr. Vinna Chrisdianti, Sp.PA", "dr. Ayu Tyasmara Pratiwi, Sp.PA"])) {
            $authorizedData_frs = $this->authorized_frs->getauthorized_frs_by_dokter($namaUser);
        } else {
            $authorizedData_frs = $this->authorized_frs->getauthorized_frs();
        }

        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $namaUser,
            'counts' => $this->getCounts(),
            'authorizedDatafrs' => $authorizedData_frs,
        ];

        return view('frs/Proses/authorized', $data);
    }

    public function proses_authorized()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_authorized_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                $this->processAction($action, $id_authorized_frs, $id_frs, $id_user, $id_mutu_frs);
            }

            return redirect()->to('authorized_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_authorized_frs, $id_frs, $id_user, $id_mutu_frs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->authorized_frs->update($id_authorized_frs, [
                        'id_user_authorized_frs' => $id_user,
                        'id_user_dokter_authorized_frs' => $id_user,
                        'status_authorized_frs' => 'Proses Authorized',
                        'mulai_authorized_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->authorized_frs->update($id_authorized_frs, [
                        'id_user_authorized_frs' => $id_user,
                        'id_user_dokter_authorized_frs' => $id_user,
                        'status_authorized_frs' => 'Selesai Authorized',
                        'selesai_authorized_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->authorized_frs->update($id_authorized_frs, [
                        'id_user_authorized_frs' => null,
                        'id_user_dokter_authorized_frs' => null,
                        'status_authorized_frs' => 'Belum Authorized',
                        'mulai_authorized_frs' => null,
                        'selesai_authorized_frs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Pencetakan']);
                    $pencetakanData = [
                        'id_frs'            => $id_frs,
                        'status_pencetakan_frs' => 'Belum Pencetakan',
                    ];
                    if (!$this->pencetakan_frs->insert($pencetakanData)) {
                        throw new Exception('Gagal menyimpan data pencetakan.');
                    }
                    break;
                case 'kembalikan':
                    $this->authorized_frs->delete($id_authorized_frs);
                    $this->frsModel->update($id_frs, [
                        'status_frs' => 'Pemverifikasi',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function authorized_details()
    {
        $id_authorized_frs = $this->request->getGet('id_authorized_frs');

        if ($id_authorized_frs) {
            $data = $this->authorized_frs->detailsauthorized_frs($id_authorized_frs);

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
        $id_authorized_frs = $this->request->getGet('id_authorized_frs');

        if (!$id_authorized_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID authorized tidak ditemukan.');
        }

        // Ambil data authorized
        $authorizedData = $this->authorized_frs->find($id_authorized_frs);

        if (!$authorizedData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data authorized tidak ditemukan.');
        }

        // Ambil data user
        $users = $this->userModel->findAll();

        $data = [
            'authorizedData' => $authorizedData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('frs/edit_proses/edit_authorized', $data);
    }

    public function update()
    {
        $id_authorized_frs = $this->request->getPost('id_authorized_frs');

        if (!$id_authorized_frs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_authorized_frs = $this->request->getPost('mulai_authorized_frs_date') . ' ' . $this->request->getPost('mulai_authorized_frs_time');
        $selesai_authorized_frs = $this->request->getPost('selesai_authorized_frs_date') . ' ' . $this->request->getPost('selesai_authorized_frs_time');

        $id_user = $this->request->getPost('id_user_dokter_authorized_frs');

        $data = [
            'id_user_dokter_authorized_frs' => $id_user === '' ? null : $id_user,
            'status_authorized_frs'  => $this->request->getPost('status_authorized_frs'),
            'mulai_authorized_frs'   => $mulai_authorized_frs,
            'selesai_authorized_frs' => $selesai_authorized_frs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->authorized_frs->update($id_authorized_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('authorized_frs/edit?id_authorized_frs=' . $id_authorized_frs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_authorized = $this->request->getPost('id_authorized'); 
            $id_frs = $this->request->getPost('id_frs');
            if (!$id_authorized || !$id_frs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data authorized
            if ($this->authorized_frs->delete($id_authorized)) {
                // Update status_frs ke tahap sebelumnya
                $this->frsModel->update($id_frs, [
                    'status_frs' => 'Pemverifikasi',
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
