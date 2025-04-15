<?php

namespace App\Controllers\ihc\Proses;

use App\Controllers\BaseController;
use App\Models\ihc\ihcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\ihc\Proses\Pemverifikasi_ihc;
use App\Models\ihc\Proses\Authorized_ihc;
use App\Models\ihc\Mutu_ihc;
use Exception;

class Pemverifikasi extends BaseController
{
    protected $ihcModel;
    protected $userModel;
    protected $patientModel;
    protected $pemverifikasi_ihc;
    protected $authorized_ihc;
    protected $mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pemverifikasi_ihc = new Pemverifikasi_ihc();
        $this->authorized_ihc = new Authorized_ihc();
        $this->mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pemverifikasiData_ihc = $this->pemverifikasi_ihc->getpemverifikasi_ihc();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pemverifikasiDataihc' => $pemverifikasiData_ihc,
        ];
        
        return view('ihc/Proses/pemverifikasi', $data);
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
                list($id_pemverifikasi_ihc, $id_ihc, $id_mutu_ihc) = explode(':', $id);
                $this->processAction($action, $id_pemverifikasi_ihc, $id_ihc, $id_user, $id_mutu_ihc);
            }

            return redirect()->to('pemverifikasi_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pemverifikasi_ihc, $id_ihc, $id_user, $id_mutu_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pemverifikasi_ihc->update($id_pemverifikasi_ihc, [
                        'id_user_pemverifikasi_ihc' => $id_user,
                        'status_pemverifikasi_ihc' => 'Proses Pemverifikasi',
                        'mulai_pemverifikasi_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pemverifikasi_ihc->update($id_pemverifikasi_ihc, [
                        'id_user_pemverifikasi_ihc' => $id_user,
                        'status_pemverifikasi_ihc' => 'Selesai Pemverifikasi',
                        'selesai_pemverifikasi_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pemverifikasi_ihc->update($id_pemverifikasi_ihc, [
                        'id_user_pemverifikasi_ihc' => null,
                        'status_pemverifikasi_ihc' => 'Belum Pemverifikasi',
                        'mulai_pemverifikasi_ihc' => null,
                        'selesai_pemverifikasi_ihc' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Authorized']);
                    $authorizedData = [
                        'id_ihc'            => $id_ihc,
                        'status_authorized_ihc' => 'Belum Authorized',
                    ];
                    if (!$this->authorized_ihc->insert($authorizedData)) {
                        throw new Exception('Gagal menyimpan data authorized.');
                    }
                    break;
                case 'kembalikan':
                    $this->pemverifikasi_ihc->delete($id_pemverifikasi_ihc);
                    $this->ihcModel->update($id_ihc, [
                        'status_ihc' => 'Penulisan',
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
        $id_pemverifikasi_ihc = $this->request->getGet('id_pemverifikasi_ihc');

        if ($id_pemverifikasi_ihc) {
            $data = $this->pemverifikasi_ihc->detailspemverifikasi_ihc($id_pemverifikasi_ihc);

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
        $id_pemverifikasi_ihc = $this->request->getGet('id_pemverifikasi_ihc');

        if (!$id_pemverifikasi_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemverifikasi tidak ditemukan.');
        }

        // Ambil data pemverifikasi
        $pemverifikasiData = $this->pemverifikasi_ihc->find($id_pemverifikasi_ihc);

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

        return view('ihc/edit_proses/edit_pemverifikasi', $data);
    }

    public function update()
    {
        $id_pemverifikasi_ihc = $this->request->getPost('id_pemverifikasi_ihc');

        if (!$id_pemverifikasi_ihc) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pemverifikasi_ihc = $this->request->getPost('mulai_pemverifikasi_ihc_date') . ' ' . $this->request->getPost('mulai_pemverifikasi_ihc_time');
        $selesai_pemverifikasi_ihc = $this->request->getPost('selesai_pemverifikasi_ihc_date') . ' ' . $this->request->getPost('selesai_pemverifikasi_ihc_time');

        $id_user = $this->request->getPost('id_user_pemverifikasi_ihc');

        $data = [
            'id_user_pemverifikasi_ihc' => $id_user === '' ? null : $id_user,
            'status_pemverifikasi_ihc'  => $this->request->getPost('status_pemverifikasi_ihc'),
            'mulai_pemverifikasi_ihc'   => $mulai_pemverifikasi_ihc,
            'selesai_pemverifikasi_ihc' => $selesai_pemverifikasi_ihc,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemverifikasi_ihc->update($id_pemverifikasi_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pemverifikasi_ihc/edit?id_pemverifikasi_ihc=' . $id_pemverifikasi_ihc))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pemverifikasi = $this->request->getPost('id_pemverifikasi');
            $id_ihc = $this->request->getPost('id_ihc');
            if (!$id_pemverifikasi || !$id_ihc) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pemverifikasi
            if ($this->pemverifikasi_ihc->delete($id_pemverifikasi)) {
                // Update status_ihc ke tahap sebelumnya 
                $this->ihcModel->update($id_ihc, [
                    'status_ihc' => 'Penulisan',
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
