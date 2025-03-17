<?php

namespace App\Controllers\ihc\Proses;

use App\Controllers\BaseController;
use App\Models\ihc\ihcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\ihc\Proses\Penulisan_ihc;
use App\Models\ihc\Proses\Pemverifikasi_ihc;
use App\Models\ihc\Mutu_ihc;
use Exception;

class Penulisan extends BaseController
{
    protected $ihcModel;
    protected $userModel;
    protected $patientModel;
    protected $penulisan_ihc;
    protected $pemverifikasi_ihc;
    protected $mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penulisan_ihc = new Penulisan_ihc();
        $this->pemverifikasi_ihc = new Pemverifikasi_ihc();
        $this->mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $penulisanData_ihc = $this->penulisan_ihc->getpenulisan_ihc();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'penulisanDataihc' => $penulisanData_ihc,
        ];
        
        return view('ihc/Proses/penulisan', $data);
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
                list($id_penulisan_ihc, $id_ihc, $id_mutu_ihc) = explode(':', $id);
                $this->processAction($action, $id_penulisan_ihc, $id_ihc, $id_user, $id_mutu_ihc);
            }

            return redirect()->to('penulisan_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_penulisan_ihc, $id_ihc, $id_user, $id_mutu_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->penulisan_ihc->update($id_penulisan_ihc, [
                        'id_user_penulisan_ihc' => $id_user,
                        'status_penulisan_ihc' => 'Proses Penulisan',
                        'mulai_penulisan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->penulisan_ihc->update($id_penulisan_ihc, [
                        'id_user_penulisan_ihc' => $id_user,
                        'status_penulisan_ihc' => 'Selesai Penulisan',
                        'selesai_penulisan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->penulisan_ihc->update($id_penulisan_ihc, [
                        'id_user_penulisan_ihc' => null,
                        'status_penulisan_ihc' => 'Belum Penulisan',
                        'mulai_penulisan_ihc' => null,
                        'selesai_penulisan_ihc' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Pemverifikasi']);
                    $pemverifikasiData = [
                        'id_ihc'            => $id_ihc,
                        'status_pemverifikasi_ihc' => 'Belum Pemverifikasi',
                    ];
                    if (!$this->pemverifikasi_ihc->insert($pemverifikasiData)) {
                        throw new Exception('Gagal menyimpan data pemverifikasi.');
                    }
                    break;
                case 'kembalikan':
                    $this->penulisan_ihc->delete($id_penulisan_ihc);
                    $this->ihcModel->update($id_ihc, [
                        'status_ihc' => 'Pembacaan',
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
        // Ambil id_penulisan_ihc dari parameter GET
        $id_penulisan_ihc = $this->request->getGet('id_penulisan_ihc');

        if ($id_penulisan_ihc) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->penulisan_ihc->select(
                'penulisan.*, 
            ihc.*, 
            patient.*, 
            users.nama_user AS nama_user_penulisan'
            )
                ->join('ihc', 'penulisan.id_ihc = ihc.id_ihc', 'left')
                ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'penulisan.id_user_penulisan_ihc = users.id_user', 'left')
                ->where('penulisan.id_penulisan_ihc', $id_penulisan_ihc)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID penulisan tidak ditemukan.']);
        }
    }

    public function edit_penulisan()
    {
        $id_penulisan_ihc = $this->request->getGet('id_penulisan_ihc');

        if (!$id_penulisan_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID penulisan tidak ditemukan.');
        }

        // Ambil data penulisan
        $penulisanData = $this->penulisan_ihc->find($id_penulisan_ihc);

        if (!$penulisanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penulisan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'penulisanData' => $penulisanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_penulisan', $data);
    }

    public function update_penulisan()
    {
        $id_penulisan_ihc = $this->request->getPost('id_penulisan_ihc');

        // Gabungkan input tanggal dan waktu
        $mulai_penulisan_ihc = $this->request->getPost('mulai_penulisan_ihc_date') . ' ' . $this->request->getPost('mulai_penulisan_ihc_time');
        $selesai_penulisan_ihc = $this->request->getPost('selesai_penulisan_ihc_date') . ' ' . $this->request->getPost('selesai_penulisan_ihc_time');

        $data = [
            'id_user_penulisan_ihc' => $this->request->getPost('id_user_penulisan_ihc'),
            'status_penulisan_ihc'  => $this->request->getPost('status_penulisan_ihc'),
            'mulai_penulisan_ihc'   => $mulai_penulisan_ihc,
            'selesai_penulisan_ihc' => $selesai_penulisan_ihc,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->penulisan_ihc->update($id_penulisan_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('penulisan/index_penulisan'))->with('success', 'Data berhasil diperbarui.');
    }
}
