<?php

namespace App\Controllers\Hpa\Proses;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Authorized_hpa;
use App\Models\Hpa\Proses\Pencetakan_hpa;
use App\Models\Hpa\Mutu_hpa;
use CodeIgniter\I18n\Time;
use Exception;

class Authorized extends BaseController
{
    protected $hpaModel;
    protected $userModel;
    protected $patientModel;
    protected $authorized_hpa;
    protected $pencetakan_hpa;
    protected $mutu_hpa;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->hpaModel        = new HpaModel();
        $this->userModel       = new UsersModel();
        $this->patientModel    = new PatientModel();
        $this->authorized_hpa  = new Authorized_hpa();
        $this->pencetakan_hpa  = new Pencetakan_hpa();
        $this->mutu_hpa        = new Mutu_hpa();
        $this->validation      = \Config\Services::validation();
        $this->session         = \Config\Services::session();
    }

    public function index()
    {
        $namaUser = $this->session->get('nama_user');

<<<<<<< HEAD
        // Jika user adalah salah satu dokter, filter data sesuai nama dokter
=======
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
        if (in_array($namaUser, ["dr. Vinna Chrisdianti, Sp.PA", "dr. Ayu Tyasmara Pratiwi, Sp.PA"])) {
            $authorizedData_hpa = $this->authorized_hpa->getauthorized_hpa_by_dokter($namaUser);
        } else {
            $authorizedData_hpa = $this->authorized_hpa->getauthorized_hpa();
        }

        $data = [
<<<<<<< HEAD
            'id_user' => session()->get('id_user'),
            'nama_user' => $namaUser,
            'counts' => $this->getCounts(),
            'authorizedDatahpa' => $authorizedData_hpa,
=======
            'id_user'            => session()->get('id_user'),
            'nama_user'          => $namaUser,
            'counts'             => $this->getCounts(),
            'authorizedDatahpa'  => $authorizedData_hpa,
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
        ];

        return view('Hpa/Proses/authorized', $data);
    }


    public function proses_authorized()
    {
        $id_user = $this->session->get('id_user');

        try {
            $selectedIds = $this->request->getPost('id_proses');
            $action      = $this->request->getPost('action');

            if (empty($selectedIds)) {
                return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
            }

            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds];
            }

            foreach ($selectedIds as $id) {
                $parts = explode(':', $id);
                if (count($parts) !== 3) {
                    throw new Exception("Format ID tidak valid: $id");
                }

                list($id_authorized_hpa, $id_hpa, $id_mutu_hpa) = $parts;
                $this->processAction($action, $id_authorized_hpa, $id_hpa, $id_user, $id_mutu_hpa);
            }

            return redirect()->to('authorized_hpa/index')->with('success', 'Aksi berhasil diproses.');
        } catch (Exception $e) {
            log_message('error', 'Error proses_authorized: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_authorized_hpa, $id_hpa, $id_user, $id_mutu_hpa)
    {
        $now = Time::now('Asia/Jakarta', 'id_ID')->toDateTimeString();

        try {
            switch ($action) {
                case 'mulai':
                    $this->authorized_hpa->update($id_authorized_hpa, [
<<<<<<< HEAD
                        'id_user_authorized_hpa' => $id_user,
                        'id_user_dokter_authorized_hpa' => $id_user,
                        'status_authorized_hpa' => 'Proses Authorized',
                        'mulai_authorized_hpa' => date('Y-m-d H:i:s'),
=======
                        'id_user_authorized_hpa'        => $id_user,
                        'id_user_dokter_authorized_hpa' => $id_user,
                        'status_authorized_hpa'         => 'Proses Authorized',
                        'mulai_authorized_hpa'          => $now,
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
                    ]);

                    $this->kirimKeSimrs($id_hpa, null);
                    break;

                case 'selesai':
                    $this->authorized_hpa->update($id_authorized_hpa, [
<<<<<<< HEAD
                        'id_user_authorized_hpa' => $id_user,
                        'id_user_dokter_authorized_hpa' => $id_user,
                        'status_authorized_hpa' => 'Selesai Authorized',
                        'selesai_authorized_hpa' => date('Y-m-d H:i:s'),
=======
                        'id_user_authorized_hpa'        => $id_user,
                        'id_user_dokter_authorized_hpa' => $id_user,
                        'status_authorized_hpa'         => 'Selesai Authorized',
                        'selesai_authorized_hpa'        => $now,
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
                    ]);

                    $this->kirimKeSimrs($id_hpa, $now);
                    break;

                case 'reset':
                    $this->authorized_hpa->update($id_authorized_hpa, [
<<<<<<< HEAD
                        'id_user_authorized_hpa' => null,
                        'id_user_dokter_authorized_hpa' => null,
                        'status_authorized_hpa' => 'Belum Authorized',
                        'mulai_authorized_hpa' => null,
                        'selesai_authorized_hpa' => null,
=======
                        'id_user_authorized_hpa'        => null,
                        'id_user_dokter_authorized_hpa' => null,
                        'status_authorized_hpa'         => 'Belum Authorized',
                        'mulai_authorized_hpa'          => null,
                        'selesai_authorized_hpa'        => null,
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
                    ]);
                    break;

                case 'lanjut':
                    $this->hpaModel->update($id_hpa, ['status_hpa' => 'Pencetakan']);

                    // Cek dulu agar tidak insert ganda
                    $exists = $this->pencetakan_hpa->where('id_hpa', $id_hpa)->first();
                    if (!$exists) {
                        $pencetakanData = [
                            'id_hpa'                 => $id_hpa,
                            'status_pencetakan_hpa'  => 'Belum Pencetakan',
                        ];
                        if (!$this->pencetakan_hpa->insert($pencetakanData)) {
                            throw new Exception('Gagal menyimpan data pencetakan.');
                        }
                    }

                    $this->kirimKeSimrs($id_hpa, $now);
                    break;

                case 'kembalikan':
                    $this->authorized_hpa->delete($id_authorized_hpa);
                    $this->hpaModel->update($id_hpa, [
                        'status_hpa' => 'Pemverifikasi',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    private function kirimKeSimrs($id_hpa, $selesaiAuthorized = null)
    {
<<<<<<< HEAD
        $id_authorized_hpa = $this->request->getGet('id_authorized_hpa');

        if ($id_authorized_hpa) {
            $data = $this->authorized_hpa->detailsauthorized_hpa($id_authorized_hpa);

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'Coba ulangi kembali..']);
        }
    }

=======
        $hpaTerbaru = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        if (!$hpaTerbaru) {
            log_message('error', '[PENGIRIMAN SIMRS] Data HPA tidak ditemukan untuk ID: ' . $id_hpa);
            return;
        }

        $data = $hpaTerbaru;
        
        // --- HITUNG RESPONSETIME ---
        $responsetime = null;
        if (!empty($data['mulai_penerimaan_hpa']) && !empty($data['selesai_penulisan_hpa'])) {
            try {
                $start = new \DateTime($data['mulai_penerimaan_hpa']);
                $end   = new \DateTime($data['selesai_penulisan_hpa']);
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

        $idDokterPembacaan = $data['id_user_dokter_pembacaan_hpa'] ?? null;

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
            'noregister'       => $data['kode_hpa'] ?? '',
            'idpasien'         => $data['id_pasien'] ?? '',
            'norm'             => $data['norm_pasien'] ?? '',
            'nama'             => $data['nama_pasien'] ?? '',
            'datang'           => $data['mulai_penerimaan_hpa'] ?? '',
            'periksa'          => $data['mulai_penerimaan_hpa'] ?? '',
            'selesai'          => $data['selesai_penulisan_hpa'] ?? '',
            'diambil' => $selesaiAuthorized
                ?? ($data['selesai_authorized_hpa'] ?? $data['selesai_penulisan_hpa'] ?? ''),
            'iddokterpa' => $iddokterpa,
            'dokterpa'   => $dokterpa,
            'statuslokasi'     => $data['lokasi_spesimen'] ?? '',
            'diagnosaklinik'   => $data['diagnosa_klinik'] ?? '',
            'diagnosapatologi' => $data['hasil_hpa'] ?? '',
            'mutusediaan'      => $data['total_nilai_mutu_hpa'] ?? '',
            'responsetime'     => $responsetime ?? '',
            'hasil'            => $data['print_hpa'] ?? '',
            'status'           => !empty($data['id_transaksi'])
                ? ($data['status_hpa'] ?? 'Belum Terkirim')
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
        $id_authorized_hpa = $this->request->getGet('id_authorized_hpa');

        if ($id_authorized_hpa) {
            $data = $this->authorized_hpa->detailsauthorized_hpa($id_authorized_hpa);

            return $data
                ? $this->response->setJSON($data)
                : $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
        }

        return $this->response->setJSON(['error' => 'Coba ulangi kembali..']);
    }

>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
    public function edit()
    {
        $id_authorized_hpa = $this->request->getGet('id_authorized_hpa');

        if (!$id_authorized_hpa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID authorized tidak ditemukan.');
        }

        $authorizedData = $this->authorized_hpa->find($id_authorized_hpa);

        if (!$authorizedData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data authorized tidak ditemukan.');
        }

<<<<<<< HEAD
        // Ambil data user
=======
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
        $users = $this->userModel->findAll();

        $data = [
            'authorizedData' => $authorizedData,
            'users'          => $users,
            'id_user'        => session()->get('id_user'),
            'nama_user'      => session()->get('nama_user'),
        ];

        return view('Hpa/edit_proses/edit_authorized', $data);
    }

    public function update()
    {
        $id_authorized_hpa = $this->request->getPost('id_authorized_hpa');

        if (!$id_authorized_hpa) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

<<<<<<< HEAD
        // Gabungkan input tanggal dan waktu
        $mulai_authorized_hpa = $this->request->getPost('mulai_authorized_hpa_date') . ' ' . $this->request->getPost('mulai_authorized_hpa_time');
=======
        $mulai_authorized_hpa   = $this->request->getPost('mulai_authorized_hpa_date') . ' ' . $this->request->getPost('mulai_authorized_hpa_time');
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
        $selesai_authorized_hpa = $this->request->getPost('selesai_authorized_hpa_date') . ' ' . $this->request->getPost('selesai_authorized_hpa_time');

        $id_user = $this->request->getPost('id_user_dokter_authorized_hpa');

        $data = [
            'id_user_dokter_authorized_hpa' => $id_user === '' ? null : $id_user,
<<<<<<< HEAD
            'status_authorized_hpa'  => $this->request->getPost('status_authorized_hpa'),
            'mulai_authorized_hpa'   => $mulai_authorized_hpa,
            'selesai_authorized_hpa' => $selesai_authorized_hpa,
            'updated_at'             => date('Y-m-d H:i:s'),
=======
            'status_authorized_hpa'         => $this->request->getPost('status_authorized_hpa'),
            'mulai_authorized_hpa'          => $mulai_authorized_hpa,
            'selesai_authorized_hpa'        => $selesai_authorized_hpa,
            'updated_at'                    => date('Y-m-d H:i:s'),
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
        ];

        if (!$this->authorized_hpa->update($id_authorized_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('authorized_hpa/edit?id_authorized_hpa=' . $id_authorized_hpa))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_authorized = $this->request->getPost('id_authorized');
<<<<<<< HEAD
            $id_hpa = $this->request->getPost('id_hpa');
            if (!$id_authorized || !$id_hpa) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data authorized
            if ($this->authorized_hpa->delete($id_authorized)) {
                // Update status_hpa ke tahap sebelumnya
=======
            $id_hpa        = $this->request->getPost('id_hpa');

            if (!$id_authorized || !$id_hpa) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }

            if ($this->authorized_hpa->delete($id_authorized)) {
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
                $this->hpaModel->update($id_hpa, [
                    'status_hpa' => 'Pemverifikasi',
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
