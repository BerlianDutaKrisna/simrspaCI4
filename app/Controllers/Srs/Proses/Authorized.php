<?php

namespace App\Controllers\Srs\Proses;

use App\Controllers\BaseController;
use App\Models\Srs\SrsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\Authorized_srs;
use App\Models\Srs\Proses\Pencetakan_srs;
use App\Models\Srs\Mutu_srs;
use CodeIgniter\I18n\Time;
use Exception;

class Authorized extends BaseController
{
    protected $srsModel;
    protected $userModel;
    protected $patientModel;
    protected $authorized_srs;
    protected $pencetakan_srs;
    protected $mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->authorized_srs = new Authorized_srs();
        $this->pencetakan_srs = new Pencetakan_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $namaUser = $this->session->get('nama_user');

        // Jika user adalah salah satu dokter, filter data sesuai nama dokter
        if (in_array($namaUser, ["dr. Vinna Chrisdianti, Sp.PA", "dr. Ayu Tyasmara Pratiwi, Sp.PA"])) {
            $authorizedData_srs = $this->authorized_srs->getauthorized_srs_by_dokter($namaUser);
        } else {
            $authorizedData_srs = $this->authorized_srs->getauthorized_srs();
        }

        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $namaUser,
            'counts' => $this->getCounts(),
            'authorizedDatasrs' => $authorizedData_srs,
        ];

        return view('srs/Proses/authorized', $data);
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
                list($id_authorized_srs, $id_srs, $id_mutu_srs) = explode(':', $id);
                $this->processAction($action, $id_authorized_srs, $id_srs, $id_user, $id_mutu_srs);
            }

            return redirect()->to('authorized_srs/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_authorized_srs, $id_srs, $id_user, $id_mutu_srs)
    {
        $now = Time::now('Asia/Jakarta', 'id_ID')->toDateTimeString();

        try {
            switch ($action) {
                case 'mulai':
                    $this->authorized_srs->update($id_authorized_srs, [
                        'id_user_authorized_srs'        => $id_user,
                        'id_user_dokter_authorized_srs' => $id_user,
                        'status_authorized_srs'         => 'Proses Authorized',
                        'mulai_authorized_srs'          => $now,
                    ]);

                    $this->kirimKeSimrs($id_srs, null);
                    break;

                case 'selesai':
                    $this->authorized_srs->update($id_authorized_srs, [
                        'id_user_authorized_srs'        => $id_user,
                        'id_user_dokter_authorized_srs' => $id_user,
                        'status_authorized_srs'         => 'Selesai Authorized',
                        'selesai_authorized_srs'        => $now,
                    ]);

                    $this->kirimKeSimrs($id_srs, $now);
                    break;

                case 'reset':
                    $this->authorized_srs->update($id_authorized_srs, [
                        'id_user_authorized_srs'        => null,
                        'id_user_dokter_authorized_srs' => null,
                        'status_authorized_srs'         => 'Belum Authorized',
                        'mulai_authorized_srs'          => null,
                        'selesai_authorized_srs'        => null,
                    ]);
                    break;

                case 'lanjut':
                    $this->srsModel->update($id_srs, ['status_srs' => 'Pencetakan']);

                    // Cek dulu agar tidak insert ganda
                    $exists = $this->pencetakan_srs->where('id_srs', $id_srs)->first();
                    if (!$exists) {
                        $pencetakanData = [
                            'id_srs'                 => $id_srs,
                            'status_pencetakan_srs'  => 'Belum Pencetakan',
                        ];
                        if (!$this->pencetakan_srs->insert($pencetakanData)) {
                            throw new Exception('Gagal menyimpan data pencetakan.');
                        }
                    }

                    $this->kirimKeSimrs($id_srs, $now);
                    break;

                case 'kembalikan':
                    $this->authorized_srs->delete($id_authorized_srs);
                    $this->srsModel->update($id_srs, [
                        'status_srs' => 'Pemverifikasi',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    private function kirimKeSimrs($id_srs, $selesaiAuthorized = null)
    {
        $srsTerbaru = $this->srsModel->getsrsWithRelationsProses($id_srs);
        if (!$srsTerbaru) {
            log_message('error', '[PENGIRIMAN SIMRS] Data srs tidak ditemukan untuk ID: ' . $id_srs);
            return;
        }

        $data = $srsTerbaru;
        
        // --- HITUNG RESPONSETIME ---
        $responsetime = null;
        if (!empty($data['mulai_penerimaan_srs']) && !empty($data['selesai_penulisan_srs'])) {
            try {
                $start = new \DateTime($data['mulai_penerimaan_srs']);
                $end   = new \DateTime($data['selesai_penulisan_srs']);
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

        $idDokterPembacaan = $data['id_user_dokter_pembacaan_srs'] ?? null;

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
            'noregister'       => $data['kode_srs'] ?? '',
            'idpasien'         => $data['id_pasien'] ?? '',
            'norm'             => $data['norm_pasien'] ?? '',
            'nama'             => $data['nama_pasien'] ?? '',
            'datang'           => $data['mulai_penerimaan_srs'] ?? '',
            'periksa'          => $data['mulai_penerimaan_srs'] ?? '',
            'selesai'          => $data['selesai_penulisan_srs'] ?? '',
            'diambil' => $selesaiAuthorized
                ?? ($data['selesai_authorized_srs'] ?? $data['selesai_penulisan_srs'] ?? ''),
            'iddokterpa' => $iddokterpa,
            'dokterpa'   => $dokterpa,
            'statuslokasi'     => $data['lokasi_spesimen'] ?? '',
            'diagnosaklinik'   => $data['diagnosa_klinik'] ?? '',
            'diagnosapatologi' => $data['hasil_srs'] ?? '',
            'mutusediaan'      => $data['total_nilai_mutu_srs'] ?? '',
            'responsetime'     => $responsetime ?? '',
            'hasil'            => $data['print_srs'] ?? '',
            'status'           => !empty($data['id_transaksi'])
                ? ($data['status_srs'] ?? 'Belum Terkirim')
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
        $id_authorized_srs = $this->request->getGet('id_authorized_srs');

        if ($id_authorized_srs) {
            $data = $this->authorized_srs->detailsauthorized_srs($id_authorized_srs);

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
        $id_authorized_srs = $this->request->getGet('id_authorized_srs');

        if (!$id_authorized_srs) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID authorized tidak ditemukan.');
        }

        // Ambil data authorized
        $authorizedData = $this->authorized_srs->find($id_authorized_srs);

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

        return view('srs/edit_proses/edit_authorized', $data);
    }

    public function update()
    {
        $id_authorized_srs = $this->request->getPost('id_authorized_srs');

        if (!$id_authorized_srs) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_authorized_srs = $this->request->getPost('mulai_authorized_srs_date') . ' ' . $this->request->getPost('mulai_authorized_srs_time');
        $selesai_authorized_srs = $this->request->getPost('selesai_authorized_srs_date') . ' ' . $this->request->getPost('selesai_authorized_srs_time');

        $id_user = $this->request->getPost('id_user_dokter_authorized_srs');

        $data = [
            'id_user_dokter_authorized_srs' => $id_user === '' ? null : $id_user,
            'status_authorized_srs'  => $this->request->getPost('status_authorized_srs'),
            'mulai_authorized_srs'   => $mulai_authorized_srs,
            'selesai_authorized_srs' => $selesai_authorized_srs,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->authorized_srs->update($id_authorized_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('authorized_srs/edit?id_authorized_srs=' . $id_authorized_srs))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_authorized = $this->request->getPost('id_authorized');
            $id_srs = $this->request->getPost('id_srs');
            if (!$id_authorized || !$id_srs) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data authorized
            if ($this->authorized_srs->delete($id_authorized)) {
                // Update status_srs ke tahap sebelumnya
                $this->srsModel->update($id_srs, [
                    'status_srs' => 'Pemverifikasi',
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
