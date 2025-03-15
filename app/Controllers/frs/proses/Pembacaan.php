<?php

namespace App\Controllers\frs\Proses;

use App\Controllers\BaseController;
use App\Models\Frs\frsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Frs\Proses\Pembacaan_frs;
use App\Models\Frs\Proses\Penulisan_frs;
use App\Models\Frs\Mutu_frs;
use Exception;

class Pembacaan extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $pembacaan_frs;
    protected $penulisan_frs;
    protected $mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pembacaan_frs = new Pembacaan_frs();
        $this->penulisan_frs = new Penulisan_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pembacaanData_frs = $this->pembacaan_frs->getpembacaan_frs();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pembacaanDatafrs' => $pembacaanData_frs,
        ];

        return view('frs/Proses/pembacaan', $data);
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
                list($id_pembacaan_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                $total_nilai_mutu_frs = (string) ($this->request->getPost('total_nilai_mutu_frs') ?? '0');
                $this->processAction($action, $id_pembacaan_frs, $id_frs, $id_user, $id_mutu_frs, $indikator_4, $indikator_5, $indikator_6, $indikator_7, $indikator_8, $total_nilai_mutu_frs);
            }

            return redirect()->to('pembacaan_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pembacaan_frs, $id_frs, $id_user, $id_mutu_frs, $indikator_4, $indikator_5, $indikator_6, $indikator_7, $indikator_8, $total_nilai_mutu_frs)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pembacaan_frs->update($id_pembacaan_frs, [
                        'id_user_pembacaan_frs' => $id_user,
                        'status_pembacaan_frs' => 'Proses Pembacaan',
                        'mulai_pembacaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pembacaan_frs->update($id_pembacaan_frs, [
                        'id_user_pembacaan_frs' => $id_user,
                        'status_pembacaan_frs' => 'Selesai Pembacaan',
                        'selesai_pembacaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    $this->mutu_frs->update($id_mutu_frs, [
                        'indikator_4' => $indikator_4,
                        'indikator_5' => $indikator_5,
                        'indikator_6' => $indikator_6,
                        'indikator_7' => $indikator_7,
                        'indikator_8' => $indikator_8,
                        'total_nilai_mutu_frs' => $total_nilai_mutu_frs + $indikator_4 + $indikator_5 + $indikator_6 + $indikator_7 + $indikator_8,
                    ]);
                    break;
                case 'reset':
                    $this->pembacaan_frs->update($id_pembacaan_frs, [
                        'id_user_pembacaan_frs' => null,
                        'status_pembacaan_frs' => 'Belum Pembacaan',
                        'mulai_pembacaan_frs' => null,
                        'selesai_pembacaan_frs' => null,
                    ]);
                    $this->mutu_frs->update($id_mutu_frs, [
                        'indikator_4' => '0',
                        'indikator_5' => '0',
                        'indikator_6' => '0',
                        'indikator_7' => '0',
                        'indikator_8' => '0',
                        'total_nilai_mutu_frs' => '30',
                    ]);
                    break;
                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Penulisan']);
                    $penulisanData = [
                        'id_frs'            => $id_frs,
                        'status_penulisan_frs' => 'Belum Penulisan',
                    ];
                    if (!$this->penulisan_frs->insert($penulisanData)) {
                        throw new Exception('Gagal menyimpan data penulisan.');
                    }
                    break;
                case 'kembalikan':
                    $this->pembacaan_frs->delete($id_pembacaan_frs);
                    $this->frsModel->update($id_frs, [
                        'status_frs' => 'Pewarnaan',
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
        // Ambil id_pembacaan_frs dari parameter GET
        $id_pembacaan_frs = $this->request->getGet('id_pembacaan_frs');

        if ($id_pembacaan_frs) {
            // Gunakan model yang sudah diinisialisasi di constructor
            $data = $this->pembacaan_frs->select(
                'pembacaan.*, 
            frs.*, 
            patient.*, 
            users.nama_user AS nama_user_pembacaan'
            )
                ->join('frs', 'pembacaan.id_frs = frs.id_frs', 'left')
                ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
                ->join('users', 'pembacaan.id_user_pembacaan_frs = users.id_user', 'left')
                ->where('pembacaan.id_pembacaan_frs', $id_pembacaan_frs)
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
        $id_pembacaan_frs = $this->request->getGet('id_pembacaan_frs');

        if (!$id_pembacaan_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pembacaan tidak ditemukan.');
        }

        // Ambil data pembacaan
        $pembacaanData = $this->pembacaan_frs->find($id_pembacaan_frs);

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
        $id_pembacaan_frs = $this->request->getPost('id_pembacaan_frs');

        // Gabungkan input tanggal dan waktu
        $mulai_pembacaan_frs = $this->request->getPost('mulai_pembacaan_frs_date') . ' ' . $this->request->getPost('mulai_pembacaan_frs_time');
        $selesai_pembacaan_frs = $this->request->getPost('selesai_pembacaan_frs_date') . ' ' . $this->request->getPost('selesai_pembacaan_frs_time');

        $data = [
            'id_user_pembacaan_frs' => $this->request->getPost('id_user_pembacaan_frs'),
            'status_pembacaan_frs'  => $this->request->getPost('status_pembacaan_frs'),
            'mulai_pembacaan_frs'   => $mulai_pembacaan_frs,
            'selesai_pembacaan_frs' => $selesai_pembacaan_frs,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pembacaan_frs->update($id_pembacaan_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pembacaan/index_pembacaan'))->with('success', 'Data berhasil diperbarui.');
    }
}
