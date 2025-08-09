<?php

namespace App\Controllers\Frs\Proses;

use App\Controllers\BaseController;
use App\Models\Frs\FrsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Frs\Proses\pewarnaan_frs;
use App\Models\Frs\Proses\Pembacaan_frs;
use App\Models\Frs\Mutu_frs;
use Exception;

class Pewarnaan extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $pewarnaan_frs;
    protected $pembacaan_frs;
    protected $mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pewarnaan_frs = new pewarnaan_frs();
        $this->pembacaan_frs = new Pembacaan_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pewarnaanData_frs = $this->pewarnaan_frs->getpewarnaan_frs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pewarnaanDatafrs' => $pewarnaanData_frs,
        ];
        
        return view('frs/Proses/pewarnaan', $data);
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
                list($id_pewarnaan_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                $this->processAction($action, $id_pewarnaan_frs, $id_frs, $id_user, $id_mutu_frs);
            }

            return redirect()->to('pewarnaan_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pewarnaan_frs, $id_frs, $id_user, $id_mutu_frs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pewarnaan_frs->update($id_pewarnaan_frs, [
                        'id_user_pewarnaan_frs' => $id_user,
                        'status_pewarnaan_frs' => 'Proses Pewarnaan',
                        'mulai_pewarnaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pewarnaan_frs->update($id_pewarnaan_frs, [
                        'id_user_pewarnaan_frs' => $id_user,
                        'status_pewarnaan_frs' => 'Selesai Pewarnaan',
                        'selesai_pewarnaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pewarnaan_frs->update($id_pewarnaan_frs, [
                        'id_user_pewarnaan_frs' => null,
                        'status_pewarnaan_frs' => 'Belum Pewarnaan',
                        'mulai_pewarnaan_frs' => null,
                        'selesai_pewarnaan_frs' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Pembacaan']);
                    $pembacaanData = [
                        'id_frs'            => $id_frs,
                        'status_pembacaan_frs' => 'Belum Pembacaan',
                    ];
                    if (!$this->pembacaan_frs->insert($pembacaanData)) {
                        throw new Exception('Gagal menyimpan data pembacaan.');
                    }
                    break;
                case 'kembalikan':
                    $this->pewarnaan_frs->delete($id_pewarnaan_frs);
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

    public function pewarnaan_details()
    {
        $id_pewarnaan_frs = $this->request->getGet('id_pewarnaan_frs');

        if ($id_pewarnaan_frs) {
            $data = $this->pewarnaan_frs->detailspewarnaan_frs($id_pewarnaan_frs);

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
        $id_pewarnaan_frs = $this->request->getGet('id_pewarnaan_frs');

        if (!$id_pewarnaan_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pewarnaan tidak ditemukan.');
        }

        // Ambil data pewarnaan
        $pewarnaanData = $this->pewarnaan_frs->find($id_pewarnaan_frs);

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

        return view('frs/edit_proses/edit_pewarnaan', $data);
    }

    public function update()
    {
        $id_pewarnaan_frs = $this->request->getPost('id_pewarnaan_frs');

        if (!$id_pewarnaan_frs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_pewarnaan_frs = $this->request->getPost('mulai_pewarnaan_frs_date') . ' ' . $this->request->getPost('mulai_pewarnaan_frs_time');
        $selesai_pewarnaan_frs = $this->request->getPost('selesai_pewarnaan_frs_date') . ' ' . $this->request->getPost('selesai_pewarnaan_frs_time');

        $id_user = $this->request->getPost('id_user_pewarnaan_frs');

        $data = [
            'id_user_pewarnaan_frs' => $id_user === '' ? null : $id_user,
            'status_pewarnaan_frs'  => $this->request->getPost('status_pewarnaan_frs'),
            'mulai_pewarnaan_frs'   => $mulai_pewarnaan_frs,
            'selesai_pewarnaan_frs' => $selesai_pewarnaan_frs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->pewarnaan_frs->update($id_pewarnaan_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pewarnaan_frs/edit?id_pewarnaan_frs=' . $id_pewarnaan_frs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_pewarnaan = $this->request->getPost('id_pewarnaan');
            $id_frs = $this->request->getPost('id_frs');
            if (!$id_pewarnaan || !$id_frs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data pewarnaan
            if ($this->pewarnaan_frs->delete($id_pewarnaan)) {
                // Update status_frs ke tahap sebelumnya 
                $this->frsModel->update($id_frs, [
                    'status_frs' => 'Pemotongan Tipis',
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
