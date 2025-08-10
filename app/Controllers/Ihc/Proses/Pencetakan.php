<?php

namespace App\Controllers\Ihc\Proses;

use App\Controllers\BaseController;
use App\Models\Ihc\ihcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Ihc\Proses\Pencetakan_ihc;
use App\Models\Ihc\Mutu_ihc;
use Exception;

class Pencetakan extends BaseController
{
    protected $ihcModel;
    protected $userModel;
    protected $patientModel;
    protected $pencetakan_ihc;
    protected $mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pencetakan_ihc = new Pencetakan_ihc();
        $this->mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pencetakanData_ihc = $this->pencetakan_ihc->getpencetakan_ihc();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pencetakanDataihc' => $pencetakanData_ihc,
        ];

        return view('ihc/Proses/pencetakan', $data);
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
                list($id_pencetakan_ihc, $id_ihc, $id_mutu_ihc) = explode(':', $id);
                $this->processAction($action, $id_pencetakan_ihc, $id_ihc, $id_user, $id_mutu_ihc);
            }

            return redirect()->to('pencetakan_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pencetakan_ihc, $id_ihc, $id_user, $id_mutu_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pencetakan_ihc->update($id_pencetakan_ihc, [
                        'id_user_pencetakan_ihc' => $id_user,
                        'status_pencetakan_ihc' => 'Proses Pencetakan',
                        'mulai_pencetakan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pencetakan_ihc->update($id_pencetakan_ihc, [
                        'id_user_pencetakan_ihc' => $id_user,
                        'status_pencetakan_ihc' => 'Selesai Pencetakan',
                        'selesai_pencetakan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pencetakan_ihc->update($id_pencetakan_ihc, [
                        'id_user_pencetakan_ihc' => null,
                        'status_pencetakan_ihc' => 'Belum Pencetakan',
                        'mulai_pencetakan_ihc' => null,
                        'selesai_pencetakan_ihc' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Selesai']);
                    break;
                case 'kembalikan':
                    $this->pencetakan_ihc->delete($id_pencetakan_ihc);
                    $this->ihcModel->update($id_ihc, [
                        'status_ihc' => 'Authorized',
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
        $id_pencetakan_ihc = $this->request->getGet('id_pencetakan_ihc');

        if ($id_pencetakan_ihc) {
            $data = $this->pencetakan_ihc->detailspencetakan_ihc($id_pencetakan_ihc);

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
        $id_pencetakan_ihc = $this->request->getGet('id_pencetakan_ihc');

        if (!$id_pencetakan_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pencetakan tidak ditemukan.');
        }

        // Ambil data pencetakan
        $pencetakanData = $this->pencetakan_ihc->find($id_pencetakan_ihc);

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

        return view('ihc/edit_proses/edit_pencetakan', $data);
    }

    public function update()
    {
        $id_pencetakan_ihc = $this->request->getPost('id_pencetakan_ihc');

        if (!$id_pencetakan_ihc) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pencetakan_ihc = $this->request->getPost('mulai_pencetakan_ihc_date') . ' ' . $this->request->getPost('mulai_pencetakan_ihc_time');
        $selesai_pencetakan_ihc = $this->request->getPost('selesai_pencetakan_ihc_date') . ' ' . $this->request->getPost('selesai_pencetakan_ihc_time');

        $id_user = $this->request->getPost('id_user_pencetakan_ihc');

        $data = [
            'id_user_pencetakan_ihc' => $id_user === '' ? null : $id_user,
            'status_pencetakan_ihc'  => $this->request->getPost('status_pencetakan_ihc'),
            'mulai_pencetakan_ihc'   => $mulai_pencetakan_ihc,
            'selesai_pencetakan_ihc' => $selesai_pencetakan_ihc,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pencetakan_ihc->update($id_pencetakan_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pencetakan_ihc/edit?id_pencetakan_ihc=' . $id_pencetakan_ihc))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pencetakan = $this->request->getPost('id_pencetakan');
            $id_ihc = $this->request->getPost('id_ihc');
            if (!$id_pencetakan || !$id_ihc) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pencetakan
            if ($this->pencetakan_ihc->delete($id_pencetakan)) {
                // Update status_ihc ke tahap sebelumnya 
                $this->ihcModel->update($id_ihc, [
                    'status_ihc' => 'Authorized',
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
