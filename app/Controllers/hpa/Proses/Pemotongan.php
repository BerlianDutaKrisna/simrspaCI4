<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Pemotongan_hpa;
use App\Models\Hpa\Proses\Pemprosesan_hpa;
use App\Models\Hpa\Mutu_hpa;
use Exception;

class Pemotongan extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $pemotongan_hpa;
    protected $pemprosesan_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->pemotongan_hpa = new Pemotongan_hpa();
        $this->pemprosesan_hpa = new Pemprosesan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $pemotonganData_hpa = $this->pemotongan_hpa->getpemotongan_hpa();
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'pemotonganDatahpa' => $pemotonganData_hpa,
        ];

        return view('Hpa/Proses/pemotongan', $data);
    }

    public function proses_pemotongan()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action = $this->request->getPost('action');
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                list($id_pemotongan_hpa, $id_hpa, $id_mutu_hpa) = explode(':', $id);
                $this->processAction($action, $id_pemotongan_hpa, $id_hpa, $id_user, $id_mutu_hpa);
            }

            return redirect()->to('pemotongan_hpa/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_pemotongan_hpa, $id_hpa, $id_user, $id_mutu_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            switch ($action) {
                case 'mulai':
                    $this->pemotongan_hpa->update($id_pemotongan_hpa, [
                        'id_user_pemotongan_hpa' => $id_user,
                        'status_pemotongan_hpa' => 'Proses Pemotongan',
                        'mulai_pemotongan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'selesai':
                    $this->pemotongan_hpa->update($id_pemotongan_hpa, [
                        'id_user_pemotongan_hpa' => $id_user,
                        'status_pemotongan_hpa' => 'Selesai Pemotongan',
                        'selesai_pemotongan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    break;
                case 'reset':
                    $this->pemotongan_hpa->update($id_pemotongan_hpa, [
                        'id_user_pemotongan_hpa' => null,
                        'status_pemotongan_hpa' => 'Belum Pemotongan',
                        'mulai_pemotongan_hpa' => null,
                        'selesai_pemotongan_hpa' => null,
                    ]);
                    break;
                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pemprosesan']);
                    $pemprosesanData = [
                        'id_hpa'            => $id_hpa,
                        'status_pemprosesan_hpa' => 'Belum Pemprosesan',
                    ];
                    if (!$this->pemprosesan_hpa->insert($pemprosesanData)) {
                        throw new Exception('Gagal menyimpan data pemprosesan.');
                    }
                    break;
                case 'kembalikan':
                    $this->pemotongan_hpa->delete($id_pemotongan_hpa);
                    $this->hpaModel->update($id_hpa, [
                        'status_hpa' => 'Penerimaan',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    public function pemotongan_details()
    {
        $proses = 'pemotongan'; // atau ambil dari URL segmen
        $id_pemotongan_hpa = $this->request->getGet("id_{$proses}_hpa");

        if ($id_pemotongan_hpa) {
            $data = $this->pemotongan_hpa->detailspemotongan_hpa($id_pemotongan_hpa);

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'Coba ulangi kembali..']);
        }
    }

    public function edit_pemotongan()
    {
        $id_pemotongan_hpa = $this->request->getGet('id_pemotongan_hpa');

        if (!$id_pemotongan_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID pemotongan tidak ditemukan.');
        }

        // Ambil data pemotongan
        $pemotonganData = $this->pemotongan_hpa->find($id_pemotongan_hpa);

        if (!$pemotonganData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pemotongan tidak ditemukan.');
        }

        // Ambil data users dengan status_user = 'Analis'
        $users = $this->userModel->where('status_user', 'Analis')->findAll();

        $data = [
            'pemotonganData' => $pemotonganData,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('edit_proses/edit_pemotongan', $data);
    }

    public function update_pemotongan()
    {
        $id_pemotongan_hpa = $this->request->getPost('id_pemotongan_hpa');

        // Gabungkan input tanggal dan waktu
        $mulai_pemotongan_hpa = $this->request->getPost('mulai_pemotongan_hpa_date') . ' ' . $this->request->getPost('mulai_pemotongan_hpa_time');
        $selesai_pemotongan_hpa = $this->request->getPost('selesai_pemotongan_hpa_date') . ' ' . $this->request->getPost('selesai_pemotongan_hpa_time');

        $data = [
            'id_user_pemotongan_hpa' => $this->request->getPost('id_user_pemotongan_hpa'),
            'status_pemotongan_hpa'  => $this->request->getPost('status_pemotongan_hpa'),
            'mulai_pemotongan_hpa'   => $mulai_pemotongan_hpa,
            'selesai_pemotongan_hpa' => $selesai_pemotongan_hpa,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->pemotongan_hpa->update($id_pemotongan_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('pemotongan/index_pemotongan'))->with('success', 'Data berhasil diperbarui.');
    }
}
