<?php

namespace App\Controllers\Ihc\Proses;

use App\Controllers\BaseController;
use App\Models\Ihc\ihcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Ihc\Proses\Authorized_ihc;
use App\Models\Ihc\Proses\Pencetakan_ihc;
use App\Models\Ihc\Mutu_ihc;
use Exception;

class Authorized extends BaseController
{
    protected $ihcModel;
    protected $userModel;
    protected $patientModel;
    protected $authorized_ihc;
    protected $pencetakan_ihc;
    protected $mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->authorized_ihc = new Authorized_ihc();
        $this->pencetakan_ihc = new Pencetakan_ihc();
        $this->mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $authorizedData_ihc = $this->authorized_ihc->getauthorized_ihc();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'authorizedDataihc' => $authorizedData_ihc,
        ];
        
        return view('ihc/Proses/authorized', $data);
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
                list($id_authorized_ihc, $id_ihc, $id_mutu_ihc) = explode(':', $id);
                $this->processAction($action, $id_authorized_ihc, $id_ihc, $id_user, $id_mutu_ihc);
            }

            return redirect()->to('authorized_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_authorized_ihc, $id_ihc, $id_user, $id_mutu_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->authorized_ihc->update($id_authorized_ihc, [
                        'id_user_authorized_ihc' => $id_user,
                        'id_user_dokter_authorized_ihc' => $id_user,
                        'status_authorized_ihc' => 'Proses Authorized',
                        'mulai_authorized_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->authorized_ihc->update($id_authorized_ihc, [
                        'id_user_authorized_ihc' => $id_user,
                        'id_user_dokter_authorized_ihc' => $id_user,
                        'status_authorized_ihc' => 'Selesai Authorized',
                        'selesai_authorized_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->authorized_ihc->update($id_authorized_ihc, [
                        'id_user_authorized_ihc' => null,
                        'id_user_dokter_authorized_ihc' => null,
                        'status_authorized_ihc' => 'Belum Authorized',
                        'mulai_authorized_ihc' => null,
                        'selesai_authorized_ihc' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Pencetakan']);
                    $pencetakanData = [
                        'id_ihc'            => $id_ihc,
                        'status_pencetakan_ihc' => 'Belum Pencetakan',
                    ];
                    if (!$this->pencetakan_ihc->insert($pencetakanData)) {
                        throw new Exception('Gagal menyimpan data pencetakan.');
                    }
                    break;
                case 'kembalikan':
                    $this->authorized_ihc->delete($id_authorized_ihc);
                    $this->ihcModel->update($id_ihc, [
                        'status_ihc' => 'Pemverifikasi',
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
        $id_authorized_ihc = $this->request->getGet('id_authorized_ihc');

        if ($id_authorized_ihc) {
            $data = $this->authorized_ihc->detailsauthorized_ihc($id_authorized_ihc);

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
        $id_authorized_ihc = $this->request->getGet('id_authorized_ihc');

        if (!$id_authorized_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID authorized tidak ditemukan.');
        }

        // Ambil data authorized
        $authorizedData = $this->authorized_ihc->find($id_authorized_ihc);

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

        return view('ihc/edit_proses/edit_authorized', $data);
    }

    public function update()
    {
        $id_authorized_ihc = $this->request->getPost('id_authorized_ihc');

        if (!$id_authorized_ihc) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_authorized_ihc = $this->request->getPost('mulai_authorized_ihc_date') . ' ' . $this->request->getPost('mulai_authorized_ihc_time');
        $selesai_authorized_ihc = $this->request->getPost('selesai_authorized_ihc_date') . ' ' . $this->request->getPost('selesai_authorized_ihc_time');

        $id_user = $this->request->getPost('id_user_dokter_authorized_ihc');

        $data = [
            'id_user_dokter_authorized_ihc' => $id_user === '' ? null : $id_user,
            'status_authorized_ihc'  => $this->request->getPost('status_authorized_ihc'),
            'mulai_authorized_ihc'   => $mulai_authorized_ihc,
            'selesai_authorized_ihc' => $selesai_authorized_ihc,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->authorized_ihc->update($id_authorized_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('authorized_ihc/edit?id_authorized_ihc=' . $id_authorized_ihc))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_authorized = $this->request->getPost('id_authorized');
            $id_ihc = $this->request->getPost('id_ihc');
            if (!$id_authorized || !$id_ihc) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data authorized
            if ($this->authorized_ihc->delete($id_authorized)) {
                // Update status_ihc ke tahap sebelumnya
                $this->ihcModel->update($id_ihc, [
                    'status_ihc' => 'Pemverifikasi',
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
