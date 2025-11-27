<?php

namespace App\Controllers\Frs\Proses;

use App\Controllers\BaseController;
use App\Models\Frs\FrsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Frs\Proses\Authorized_frs;
use App\Models\Frs\Proses\Pencetakan_frs;
use App\Models\Frs\Mutu_frs;
use CodeIgniter\I18n\Time;
use Exception;

class Authorized extends BaseController
{
    protected $frsModel;
    protected $userModel;
    protected $patientModel;
    protected $authorized_frs;
    protected $pencetakan_frs;
    protected $mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->authorized_frs = new Authorized_frs();
        $this->pencetakan_frs = new Pencetakan_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $namaUser = $this->session->get('nama_user');

        // Jika user adalah salah satu dokter, filter data sesuai nama dokter
        if (in_array($namaUser, ["dr. Vinna Chrisdianti, Sp.PA", "dr. Ayu Tyasmara Pratiwi, Sp.PA"])) {
            $authorizedData_frs = $this->authorized_frs->getauthorized_frs_by_dokter($namaUser);
        } else {
            $authorizedData_frs = $this->authorized_frs->getauthorized_frs();
        }

        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $namaUser,
            'counts' => $this->getCounts(),
            'authorizedDatafrs' => $authorizedData_frs,
        ];

        return view('frs/Proses/authorized', $data);
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
                list($id_authorized_frs, $id_frs, $id_mutu_frs) = explode(':', $id);
                $this->processAction($action, $id_authorized_frs, $id_frs, $id_user, $id_mutu_frs);
            }

            return redirect()->to('authorized_frs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_authorized_frs, $id_frs, $id_user, $id_mutu_frs)
    {
        $now = Time::now('Asia/Jakarta', 'id_ID')->toDateTimeString();

        try {
            switch ($action) {
                case 'mulai':
                    $this->authorized_frs->update($id_authorized_frs, [
<<<<<<< HEAD
                        'id_user_authorized_frs' => $id_user,
                        'id_user_dokter_authorized_frs' => $id_user,
                        'status_authorized_frs' => 'Proses Authorized',
                        'mulai_authorized_frs' => date('Y-m-d H:i:s'),
=======
                        'id_user_authorized_frs'        => $id_user,
                        'id_user_dokter_authorized_frs' => $id_user,
                        'status_authorized_frs'         => 'Proses Authorized',
                        'mulai_authorized_frs'          => $now,
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
                    ]);

                    $this->kirimKeSimrs($id_frs, null);
                    break;

                case 'selesai':
                    $this->authorized_frs->update($id_authorized_frs, [
<<<<<<< HEAD
                        'id_user_authorized_frs' => $id_user,
                        'id_user_dokter_authorized_frs' => $id_user,
                        'status_authorized_frs' => 'Selesai Authorized',
                        'selesai_authorized_frs' => date('Y-m-d H:i:s'),
=======
                        'id_user_authorized_frs'        => $id_user,
                        'id_user_dokter_authorized_frs' => $id_user,
                        'status_authorized_frs'         => 'Selesai Authorized',
                        'selesai_authorized_frs'        => $now,
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
                    ]);

                    $this->kirimKeSimrs($id_frs, $now);
                    break;

                case 'reset':
                    $this->authorized_frs->update($id_authorized_frs, [
<<<<<<< HEAD
                        'id_user_authorized_frs' => null,
                        'id_user_dokter_authorized_frs' => null,
                        'status_authorized_frs' => 'Belum Authorized',
                        'mulai_authorized_frs' => null,
                        'selesai_authorized_frs' => null,
=======
                        'id_user_authorized_frs'        => null,
                        'id_user_dokter_authorized_frs' => null,
                        'status_authorized_frs'         => 'Belum Authorized',
                        'mulai_authorized_frs'          => null,
                        'selesai_authorized_frs'        => null,
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
                    ]);
                    break;

                case 'lanjut':
                    $this->frsModel->update($id_frs, ['status_frs' => 'Pencetakan']);

                    // Cek dulu agar tidak insert ganda
                    $exists = $this->pencetakan_frs->where('id_frs', $id_frs)->first();
                    if (!$exists) {
                        $pencetakanData = [
                            'id_frs'                 => $id_frs,
                            'status_pencetakan_frs'  => 'Belum Pencetakan',
                        ];
                        if (!$this->pencetakan_frs->insert($pencetakanData)) {
                            throw new Exception('Gagal menyimpan data pencetakan.');
                        }
                    }

                    $this->kirimKeSimrs($id_frs, $now);
                    break;

                case 'kembalikan':
                    $this->authorized_frs->delete($id_authorized_frs);
                    $this->frsModel->update($id_frs, [
                        'status_frs' => 'Pemverifikasi',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    private function kirimKeSimrs($id_frs, $selesaiAuthorized = null)
    {
        $frsTerbaru = $this->frsModel->getfrsWithRelationsProses($id_frs);
        if (!$frsTerbaru) {
            log_message('error', '[PENGIRIMAN SIMRS] Data frs tidak ditemukan untuk ID: ' . $id_frs);
            return;
        }

        $data = $frsTerbaru;
        
        // --- HITUNG RESPONSETIME ---
        $responsetime = null;
        if (!empty($data['mulai_penerimaan_frs']) && !empty($data['selesai_penulisan_frs'])) {
            try {
                $start = new \DateTime($data['mulai_penerimaan_frs']);
                $end   = new \DateTime($data['selesai_penulisan_frs']);
                $diff  = $start->diff($end);
                $responsetime = sprintf(
                    "%d hari %d jam %d menit %d detik",
                    $diff->days,
                    $diff->h,
                    $diff->i,
                    $diff->s
                );
            } catch (\Exception $e) {
                log_message('error', '[PENGIRIMAN SIMRS] Error hitung responsetime: ' . $e->getMessage());
            }
        }

        // --- TENTUKAN ID DOKTER PA ---
        $iddokterpa = null;
        $dokterpa   = null;

        $idDokterPembacaan = $data['id_user_dokter_pembacaan_frs'] ?? null;

        if (!empty($idDokterPembacaan)) {
            switch ($idDokterPembacaan) {
                case '1':
                    $iddokterpa = 1179;
                    $dokterpa   = "dr. Vinna Chrisdianti, Sp.PA";
                    break;
                case '2':
                    $iddokterpa = 328;
                    $dokterpa   = "dr. Ayu Tyasmara Pratiwi, Sp.PA";
                    break;
                default:
                    $iddokterpa = null;
                    $dokterpa   = $data['dokterpa'] ?? null;
                    break;
            }
        }

        // --- PERSIAPAN PAYLOAD ---
        $payload = [
            'idtransaksi'      => $data['id_transaksi'] ?? '',
            'tanggal'          => $data['tanggal_transaksi'] ?? '',
            'register'         => $data['no_register'] ?? '',
            'noregister'       => $data['kode_frs'] ?? '',
            'idpasien'         => $data['id_pasien'] ?? '',
            'norm'             => $data['norm_pasien'] ?? '',
            'nama'             => $data['nama_pasien'] ?? '',
            'datang'           => $data['mulai_penerimaan_frs'] ?? '',
            'periksa'          => $data['mulai_penerimaan_frs'] ?? '',
            'selesai'          => $data['selesai_penulisan_frs'] ?? '',
            'diambil' => $selesaiAuthorized
                ?? ($data['selesai_authorized_frs'] ?? $data['selesai_penulisan_frs'] ?? ''),
            'iddokterpa' => $iddokterpa,
            'dokterpa'   => $dokterpa,
            'statuslokasi'     => $data['lokasi_spesimen'] ?? '',
            'diagnosaklinik'   => $data['diagnosa_klinik'] ?? '',
            'diagnosapatologi' => $data['hasil_frs'] ?? '',
            'mutusediaan'      => $data['total_nilai_mutu_frs'] ?? '',
            'responsetime'     => $responsetime ?? '',
            'hasil'            => $data['print_frs'] ?? '',
            'status'           => !empty($data['id_transaksi'])
                ? ($data['status_frs'] ?? 'Belum Terkirim')
                : 'Belum Terdaftar',
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        log_message('debug', '[PENGIRIMAN SIMRS] Payload siap dikirim: ' . json_encode($payload, JSON_PRETTY_PRINT));

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->post(
                'http://172.20.29.240/apibdrs/apibdrs/postPemeriksaan',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body'    => json_encode($payload)
                ]
            );

            $responseBody = $response->getBody();
            log_message('info', '[PENGIRIMAN SIMRS] Response: ' . $responseBody);

            session()->setFlashdata('simrs_payload', json_encode($payload));
            session()->setFlashdata('simrs_response', $responseBody);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            log_message('error', '[PENGIRIMAN SIMRS] Gagal kirim: ' . $errorMessage);

            session()->setFlashdata('simrs_error', $errorMessage);
        }
    }

    public function authorized_details()
    {
        $id_authorized_frs = $this->request->getGet('id_authorized_frs');

        if ($id_authorized_frs) {
            $data = $this->authorized_frs->detailsauthorized_frs($id_authorized_frs);

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
        $id_authorized_frs = $this->request->getGet('id_authorized_frs');

        if (!$id_authorized_frs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID authorized tidak ditemukan.');
        }

        // Ambil data authorized
        $authorizedData = $this->authorized_frs->find($id_authorized_frs);

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

        return view('frs/edit_proses/edit_authorized', $data);
    }

    public function update()
    {
        $id_authorized_frs = $this->request->getPost('id_authorized_frs');

        if (!$id_authorized_frs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_authorized_frs = $this->request->getPost('mulai_authorized_frs_date') . ' ' . $this->request->getPost('mulai_authorized_frs_time');
        $selesai_authorized_frs = $this->request->getPost('selesai_authorized_frs_date') . ' ' . $this->request->getPost('selesai_authorized_frs_time');

        $id_user = $this->request->getPost('id_user_dokter_authorized_frs');

        $data = [
            'id_user_dokter_authorized_frs' => $id_user === '' ? null : $id_user,
            'status_authorized_frs'  => $this->request->getPost('status_authorized_frs'),
            'mulai_authorized_frs'   => $mulai_authorized_frs,
            'selesai_authorized_frs' => $selesai_authorized_frs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->authorized_frs->update($id_authorized_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('authorized_frs/edit?id_authorized_frs=' . $id_authorized_frs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_authorized = $this->request->getPost('id_authorized'); 
            $id_frs = $this->request->getPost('id_frs');
            if (!$id_authorized || !$id_frs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data authorized
            if ($this->authorized_frs->delete($id_authorized)) {
                // Update status_frs ke tahap sebelumnya
                $this->frsModel->update($id_frs, [
                    'status_frs' => 'Pemverifikasi',
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
