<?php

namespace App\Controllers\Ihc\Proses;

use App\Controllers\BaseController;
use App\Models\Ihc\IhcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Ihc\Proses\Authorized_ihc;
use App\Models\Ihc\Proses\Pencetakan_ihc;
use App\Models\Ihc\Mutu_ihc;
use CodeIgniter\I18n\Time;
use Exception;

class Authorized extends BaseController
{
    protected $ihcModel;
    protected $userModel;
    protected $patientModel;
    protected $authorized_ihc;
    protected $pencetakan_ihc;
    protected $mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->authorized_ihc = new Authorized_ihc();
        $this->pencetakan_ihc = new Pencetakan_ihc();
        $this->mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $namaUser = $this->session->get('nama_user');

        // Jika user adalah salah satu dokter, filter data sesuai nama dokter
        if (in_array($namaUser, ["dr. Vinna Chrisdianti, Sp.PA", "dr. Ayu Tyasmara Pratiwi, Sp.PA"])) {
            $authorizedData_ihc = $this->authorized_ihc->getauthorized_ihc_by_dokter($namaUser);
        } else {
            $authorizedData_ihc = $this->authorized_ihc->getauthorized_ihc();
        }

        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => $namaUser,
            'counts' => $this->getCounts(),
            'authorizedDataihc' => $authorizedData_ihc,
        ];

        return view('ihc/Proses/authorized', $data);
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
                list($id_authorized_ihc, $id_ihc, $id_mutu_ihc) = explode(':', $id);
                $this->processAction($action, $id_authorized_ihc, $id_ihc, $id_user, $id_mutu_ihc);
            }

            return redirect()->to('authorized_ihc/index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function processAction($action, $id_authorized_ihc, $id_ihc, $id_user, $id_mutu_ihc)
    {
        $now = Time::now('Asia/Jakarta', 'id_ID')->toDateTimeString();

        try {
            switch ($action) {
                case 'mulai':
                    $this->authorized_ihc->update($id_authorized_ihc, [
                        'id_user_authorized_ihc'        => $id_user,
                        'id_user_dokter_authorized_ihc' => $id_user,
                        'status_authorized_ihc'         => 'Proses Authorized',
                        'mulai_authorized_ihc'          => $now,
                    ]);

                    $this->kirimKeSimrs($id_ihc, null);
                    break;

                case 'selesai':
                    $this->authorized_ihc->update($id_authorized_ihc, [
                        'id_user_authorized_ihc'        => $id_user,
                        'id_user_dokter_authorized_ihc' => $id_user,
                        'status_authorized_ihc'         => 'Selesai Authorized',
                        'selesai_authorized_ihc'        => $now,
                    ]);

                    $this->kirimKeSimrs($id_ihc, $now);
                    break;

                case 'reset':
                    $this->authorized_ihc->update($id_authorized_ihc, [
                        'id_user_authorized_ihc'        => null,
                        'id_user_dokter_authorized_ihc' => null,
                        'status_authorized_ihc'         => 'Belum Authorized',
                        'mulai_authorized_ihc'          => null,
                        'selesai_authorized_ihc'        => null,
                    ]);
                    break;

                case 'lanjut':
                    $this->ihcModel->update($id_ihc, ['status_ihc' => 'Pencetakan']);

                    // Cek dulu agar tidak insert ganda
                    $exists = $this->pencetakan_ihc->where('id_ihc', $id_ihc)->first();
                    if (!$exists) {
                        $pencetakanData = [
                            'id_ihc'                 => $id_ihc,
                            'status_pencetakan_ihc'  => 'Belum Pencetakan',
                        ];
                        if (!$this->pencetakan_ihc->insert($pencetakanData)) {
                            throw new Exception('Gagal menyimpan data pencetakan.');
                        }
                    }

                    $this->kirimKeSimrs($id_ihc, $now);
                    break;

                case 'kembalikan':
                    $this->authorized_ihc->delete($id_authorized_ihc);
                    $this->ihcModel->update($id_ihc, [
                        'status_ihc' => 'Pemverifikasi',
                    ]);
                    break;
            }
        } catch (Exception $e) {
            log_message('error', 'Error in processAction: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan saat memproses aksi: ' . $e->getMessage());
        }
    }

    private function kirimKeSimrs($id_ihc, $selesaiAuthorized = null)
    {
        $ihcTerbaru = $this->ihcModel->getihcWithRelationsProses($id_ihc);
        if (!$ihcTerbaru) {
            log_message('error', '[PENGIRIMAN SIMRS] Data ihc tidak ditemukan untuk ID: ' . $id_ihc);
            return;
        }

        $data = $ihcTerbaru;
        
        // --- HITUNG RESPONSETIME ---
        $responsetime = null;
        if (!empty($data['mulai_penerimaan_ihc']) && !empty($data['selesai_penulisan_ihc'])) {
            try {
                $start = new \DateTime($data['mulai_penerimaan_ihc']);
                $end   = new \DateTime($data['selesai_penulisan_ihc']);
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

        $idDokterPembacaan = $data['id_user_dokter_pembacaan_ihc'] ?? null;

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
            'noregister'       => $data['kode_ihc'] ?? '',
            'idpasien'         => $data['id_pasien'] ?? '',
            'norm'             => $data['norm_pasien'] ?? '',
            'nama'             => $data['nama_pasien'] ?? '',
            'datang'           => $data['mulai_penerimaan_ihc'] ?? '',
            'periksa'          => $data['mulai_penerimaan_ihc'] ?? '',
            'selesai'          => $data['selesai_penulisan_ihc'] ?? '',
            'diambil' => $selesaiAuthorized
                ?? ($data['selesai_authorized_ihc'] ?? $data['selesai_penulisan_ihc'] ?? ''),
            'iddokterpa' => $iddokterpa,
            'dokterpa'   => $dokterpa,
            'statuslokasi'     => $data['unit_asal'] ?? '',
            'diagnosaklinik'   => $data['diagnosa_klinik'] ?? '',
            'diagnosapatologi' => $data['hasil_ihc'] ?? '',
            'mutusediaan'      => $data['total_nilai_mutu_ihc'] ?? '',
            'responsetime'     => $responsetime ?? '',
            'hasil'            => $data['print_ihc'] ?? '',
            'status'           => !empty($data['id_transaksi'])
                ? ($data['status_ihc'] ?? 'Belum Terkirim')
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
        $id_authorized_ihc = $this->request->getGet('id_authorized_ihc');

        if ($id_authorized_ihc) {
            $data = $this->authorized_ihc->detailsauthorized_ihc($id_authorized_ihc);

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
        $id_authorized_ihc = $this->request->getGet('id_authorized_ihc');

        if (!$id_authorized_ihc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID authorized tidak ditemukan.');
        }

        // Ambil data authorized
        $authorizedData = $this->authorized_ihc->find($id_authorized_ihc);

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

        return view('ihc/edit_proses/edit_authorized', $data);
    }

    public function update()
    {
        $id_authorized_ihc = $this->request->getPost('id_authorized_ihc');

        if (!$id_authorized_ihc) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.')->withInput();
        }

        // Gabungkan input tanggal dan waktu
        $mulai_authorized_ihc = $this->request->getPost('mulai_authorized_ihc_date') . ' ' . $this->request->getPost('mulai_authorized_ihc_time');
        $selesai_authorized_ihc = $this->request->getPost('selesai_authorized_ihc_date') . ' ' . $this->request->getPost('selesai_authorized_ihc_time');

        $id_user = $this->request->getPost('id_user_dokter_authorized_ihc');

        $data = [
            'id_user_dokter_authorized_ihc' => $id_user === '' ? null : $id_user,
            'status_authorized_ihc'  => $this->request->getPost('status_authorized_ihc'),
            'mulai_authorized_ihc'   => $mulai_authorized_ihc,
            'selesai_authorized_ihc' => $selesai_authorized_ihc,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        if (!$this->authorized_ihc->update($id_authorized_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('authorized_ihc/edit?id_authorized_ihc=' . $id_authorized_ihc))
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function delete()
    {
        try {
            $id_authorized = $this->request->getPost('id_authorized');
            $id_ihc = $this->request->getPost('id_ihc');
            if (!$id_authorized || !$id_ihc) {
                throw new \Exception('ID tidak lengkap. Gagal menghapus data.');
            }
            // Hapus data authorized
            if ($this->authorized_ihc->delete($id_authorized)) {
                // Update status_ihc ke tahap sebelumnya
                $this->ihcModel->update($id_ihc, [
                    'status_ihc' => 'Pemverifikasi',
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
