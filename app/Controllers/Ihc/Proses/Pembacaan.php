<?php

namespace App\Controllers\Ihc\Proses;

use App\Controllers\BaseController;
use App\Models\Ihc\ihcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Ihc\Proses\Pembacaan_ihc;
use App\Models\Ihc\Proses\Penulisan_ihc;
use App\Models\Ihc\Mutu_ihc;
use Exception;

class Pembacaan extends BaseController
{
    protected $ihcModel;
    protected $userModel;
    protected $patientModel;
    protected $pembacaan_ihc;
    protected $penulisan_ihc;
    protected $mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pembacaan_ihc = new Pembacaan_ihc();
        $this->penulisan_ihc = new Penulisan_ihc();
        $this->mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pembacaanData_ihc = $this->pembacaan_ihc->getpembacaan_ihc();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pembacaanDataihc' => $pembacaanData_ihc,
        ];
        
        return view('ihc/Proses/pembacaan', $data);
    }

    public function proses_pembacaan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pembacaan_ihc, $id_ihc, $id_mutu_ihc) = explode(':', $id);
                // $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                // $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                // $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                // $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                // $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                // $total_nilai_mutu_ihc = (string) ($this->request->getPost('total_nilai_mutu_ihc') ?? '0');
                $this->processAction($action, $id_pembacaan_ihc, $id_ihc, $id_user, $id_mutu_ihc);
            }

            return redirect()->to('pembacaan_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pembacaan_ihc, $id_ihc, $id_user, $id_mutu_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pembacaan_ihc->update($id_pembacaan_ihc, [
                        'id_user_pembacaan_ihc' => $id_user,
                        'status_pembacaan_ihc' => 'Proses Pembacaan',
                        'mulai_pembacaan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pembacaan_ihc->update($id_pembacaan_ihc, [
                        'id_user_pembacaan_ihc' => $id_user,
                        'status_pembacaan_ihc' => 'Selesai Pembacaan',
                        'selesai_pembacaan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pembacaan_ihc->update($id_pembacaan_ihc, [
                        'id_user_pembacaan_ihc' => null,
                        'status_pembacaan_ihc' => 'Belum Pembacaan',
                        'mulai_pembacaan_ihc' => null,
                        'selesai_pembacaan_ihc' => null,
                    ]);
                    $this->mutu_ihc->update($id_mutu_ihc, [
                        'indikator_4' => '0',
                        'indikator_5' => '0',
                        'indikator_6' => '0',
                        'indikator_7' => '0',
                        'indikator_8' => '0',
                        'total_nilai_mutu_ihc' => '30',
                    ]);
                    break;
                case 'lanjut':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Penulisan']);
                    $penulisanData = [
                        'id_ihc'            => $id_ihc,
                        'status_penulisan_ihc' => 'Belum Penulisan',
                    ];
                    if (!$this->penulisan_ihc->insert($penulisanData)) {
                        throw new Exception('Gagal menyimpan data penulisan.');
                    }
                    break;
                case 'kembalikan':
                    $this->pembacaan_ihc->delete($id_pembacaan_ihc);
                    $this->ihcModel->update($id_ihc, [
                        'status_ihc' => 'Penerimaan',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pembacaan_details()
    {
        // Ambil id_pembacaan_ihc dari parameter GET
        $id_pembacaan_ihc = $this->request->getGet('id_pembacaan_ihc');

        if ($id_pembacaan_ihc) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pembacaan_ihc->select(
                'pembacaan.*, 
            ihc.*, 
            patient.*, 
            users.nama_user AS nama_user_pembacaan'
            )
                ->join('ihc', 'pembacaan.id_ihc = ihc.id_ihc', 'left')
                ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pembacaan.id_user_pembacaan_ihc = users.id_user', 'left')
                ->where('pembacaan.id_pembacaan_ihc', $id_pembacaan_ihc)
                ->first();

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID pembacaan tidak ditemukan.']);
        }
    }

    public function edit_pembacaan()
    {
        $id_pembacaan_ihc = $this->request->getGet('id_pembacaan_ihc');

        if (!$id_pembacaan_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pembacaan tidak ditemukan.');
        }

        // Ambil data pembacaan
        $pembacaanData = $this->pembacaan_ihc->find($id_pembacaan_ihc);

        if (!$pembacaanData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pembacaan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pembacaanData' => $pembacaanData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pembacaan', $data);
    }

    public function update_pembacaan()
    {
        $id_pembacaan_ihc = $this->request->getPost('id_pembacaan_ihc');

        // Gabungkan input tanggal dan waktu
        $mulai_pembacaan_ihc = $this->request->getPost('mulai_pembacaan_ihc_date') . ' ' . $this->request->getPost('mulai_pembacaan_ihc_time');
        $selesai_pembacaan_ihc = $this->request->getPost('selesai_pembacaan_ihc_date') . ' ' . $this->request->getPost('selesai_pembacaan_ihc_time');

        $data = [
            'id_user_pembacaan_ihc' => $this->request->getPost('id_user_pembacaan_ihc'),
            'status_pembacaan_ihc'  => $this->request->getPost('status_pembacaan_ihc'),
            'mulai_pembacaan_ihc'   => $mulai_pembacaan_ihc,
            'selesai_pembacaan_ihc' => $selesai_pembacaan_ihc,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pembacaan_ihc->update($id_pembacaan_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pembacaan/index_pembacaan'))->with('success', 'Data berhasil diperbarui.');
    }
}
