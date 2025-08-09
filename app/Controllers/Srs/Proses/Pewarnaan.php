<?php

namespace App\Controllers\Srs\Proses;

use App\Controllers\BaseController;
use App\Models\Srs\SrsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\pewarnaan_srs;
use App\Models\Srs\Proses\Pembacaan_srs;
use App\Models\Srs\Mutu_srs;
use Exception;

class Pewarnaan extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $pewarnaan_srs;
    protected $pembacaan_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pewarnaan_srs = new pewarnaan_srs();
        $this->pembacaan_srs = new Pembacaan_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pewarnaanData_srs = $this->pewarnaan_srs->getpewarnaan_srs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pewarnaanDatasrs' => $pewarnaanData_srs,
        ];

        return view('srs/Proses/pewarnaan', $data);
    }

    public function proses_pewarnaan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pewarnaan_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                $this->processAction($action, $id_pewarnaan_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('pewarnaan_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pewarnaan_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pewarnaan_srs->update($id_pewarnaan_srs, [
                        'id_user_pewarnaan_srs' => $id_user,
                        'status_pewarnaan_srs' => 'Proses Pewarnaan',
                        'mulai_pewarnaan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pewarnaan_srs->update($id_pewarnaan_srs, [
                        'id_user_pewarnaan_srs' => $id_user,
                        'status_pewarnaan_srs' => 'Selesai Pewarnaan',
                        'selesai_pewarnaan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pewarnaan_srs->update($id_pewarnaan_srs, [
                        'id_user_pewarnaan_srs' => null,
                        'status_pewarnaan_srs' => 'Belum Pewarnaan',
                        'mulai_pewarnaan_srs' => null,
                        'selesai_pewarnaan_srs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Pembacaan']);
                    $pembacaanData = [
                        'id_srs'            => $id_srs,
                        'status_pembacaan_srs' => 'Belum Pembacaan',
                    ];
                    if (!$this->pembacaan_srs->insert($pembacaanData)) {
                        throw new Exception('Gagal menyimpan data pembacaan.');
                    }
                    break;
                case 'kembalikan':
                    $this->pewarnaan_srs->delete($id_pewarnaan_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Pemotongan Tipis',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pewarnaan_details()
    {
        $id_pewarnaan_srs = $this->request->getGet('id_pewarnaan_srs');

        if ($id_pewarnaan_srs) {
            $data = $this->pewarnaan_srs->detailspewarnaan_srs($id_pewarnaan_srs);

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
        $id_pewarnaan_srs = $this->request->getGet('id_pewarnaan_srs');

        if (!$id_pewarnaan_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pewarnaan tidak ditemukan.');
        }

        // Ambil data pewarnaan
        $pewarnaanData = $this->pewarnaan_srs->find($id_pewarnaan_srs);

        if (!$pewarnaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pewarnaan tidak ditemukan.');
        }

        // Ambil data user
        $users = $this->userModel->findAll();

        $data = [
            'pewarnaanData' => $pewarnaanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('srs/edit_proses/edit_pewarnaan', $data);
    }

    public function update()
    {
        $id_pewarnaan_srs = $this->request->getPost('id_pewarnaan_srs');

        if (!$id_pewarnaan_srs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pewarnaan_srs = $this->request->getPost('mulai_pewarnaan_srs_date') . ' ' . $this->request->getPost('mulai_pewarnaan_srs_time');
        $selesai_pewarnaan_srs = $this->request->getPost('selesai_pewarnaan_srs_date') . ' ' . $this->request->getPost('selesai_pewarnaan_srs_time');

        $id_user = $this->request->getPost('id_user_pewarnaan_srs');

        $data = [
            'id_user_pewarnaan_srs' => $id_user === '' ? null : $id_user,
            'status_pewarnaan_srs'  => $this->request->getPost('status_pewarnaan_srs'),
            'mulai_pewarnaan_srs'   => $mulai_pewarnaan_srs,
            'selesai_pewarnaan_srs' => $selesai_pewarnaan_srs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pewarnaan_srs->update($id_pewarnaan_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pewarnaan_srs/edit?id_pewarnaan_srs=' . $id_pewarnaan_srs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pewarnaan = $this->request->getPost('id_pewarnaan');
            $id_srs = $this->request->getPost('id_srs');
            if (!$id_pewarnaan || !$id_srs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pewarnaan
            if ($this->pewarnaan_srs->delete($id_pewarnaan)) {
                // Update status_srs ke tahap sebelumnya 
                $this->srsModel->update($id_srs, [
                    'status_srs' => 'Pemotongan Tipis',
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
