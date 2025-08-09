<?php

namespace App\Controllers\Srs\Proses;

use App\Controllers\BaseController;
use App\Models\Srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\Pencetakan_srs;
use App\Models\Srs\Mutu_srs;
use Exception;

class Pencetakan extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $pencetakan_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pencetakan_srs = new Pencetakan_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pencetakanData_srs = $this->pencetakan_srs->getpencetakan_srs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pencetakanDatasrs' => $pencetakanData_srs,
        ];

        return view('srs/Proses/pencetakan', $data);
    }

    public function proses_pencetakan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pencetakan_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                $this->processAction($action, $id_pencetakan_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('pencetakan_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pencetakan_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pencetakan_srs->update($id_pencetakan_srs, [
                        'id_user_pencetakan_srs' => $id_user,
                        'status_pencetakan_srs' => 'Proses Pencetakan',
                        'mulai_pencetakan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pencetakan_srs->update($id_pencetakan_srs, [
                        'id_user_pencetakan_srs' => $id_user,
                        'status_pencetakan_srs' => 'Selesai Pencetakan',
                        'selesai_pencetakan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pencetakan_srs->update($id_pencetakan_srs, [
                        'id_user_pencetakan_srs' => null,
                        'status_pencetakan_srs' => 'Belum Pencetakan',
                        'mulai_pencetakan_srs' => null,
                        'selesai_pencetakan_srs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Selesai']);
                    break;
                case 'kembalikan':
                    $this->pencetakan_srs->delete($id_pencetakan_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Authorized',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pencetakan_details()
    {
        $id_pencetakan_srs = $this->request->getGet('id_pencetakan_srs');

        if ($id_pencetakan_srs) {
            $data = $this->pencetakan_srs->detailspencetakan_srs($id_pencetakan_srs);

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
        $id_pencetakan_srs = $this->request->getGet('id_pencetakan_srs');

        if (!$id_pencetakan_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pencetakan tidak ditemukan.');
        }

        // Ambil data pencetakan
        $pencetakanData = $this->pencetakan_srs->find($id_pencetakan_srs);

        if (!$pencetakanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pencetakan tidak ditemukan.');
        }

        // Ambil data user
        $users = $this->userModel->findAll();

        $data = [
            'pencetakanData' => $pencetakanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('srs/edit_proses/edit_pencetakan', $data);
    }

    public function update()
    {
        $id_pencetakan_srs = $this->request->getPost('id_pencetakan_srs');

        if (!$id_pencetakan_srs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pencetakan_srs = $this->request->getPost('mulai_pencetakan_srs_date') . ' ' . $this->request->getPost('mulai_pencetakan_srs_time');
        $selesai_pencetakan_srs = $this->request->getPost('selesai_pencetakan_srs_date') . ' ' . $this->request->getPost('selesai_pencetakan_srs_time');

        $id_user = $this->request->getPost('id_user_pencetakan_srs');

        $data = [
            'id_user_pencetakan_srs' => $id_user === '' ? null : $id_user,
            'status_pencetakan_srs'  => $this->request->getPost('status_pencetakan_srs'),
            'mulai_pencetakan_srs'   => $mulai_pencetakan_srs,
            'selesai_pencetakan_srs' => $selesai_pencetakan_srs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pencetakan_srs->update($id_pencetakan_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pencetakan_srs/edit?id_pencetakan_srs=' . $id_pencetakan_srs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pencetakan = $this->request->getPost('id_pencetakan');
            $id_srs = $this->request->getPost('id_srs');
            if (!$id_pencetakan || !$id_srs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pencetakan
            if ($this->pencetakan_srs->delete($id_pencetakan)) {
                // Update status_srs ke tahap sebelumnya 
                $this->srsModel->update($id_srs, [
                    'status_srs' => 'Authorized',
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
