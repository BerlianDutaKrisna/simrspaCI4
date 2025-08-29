<?php

namespace App\Controllers\Ihc;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\Frs\FrsModel;
use App\Models\Srs\SrsModel;
use App\Models\Ihc\IhcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\SimrsModel;
use App\Models\KunjunganModel;
use App\Models\Ihc\Proses\Penerimaan_ihc;
use App\Models\Ihc\Proses\Pembacaan_ihc;
use App\Models\Ihc\Proses\Penulisan_ihc;
use App\Models\Ihc\Proses\Pemverifikasi_ihc;
use App\Models\Ihc\Proses\Authorized_ihc;
use App\Models\Ihc\Proses\Pencetakan_ihc;
use App\Models\Ihc\Mutu_ihc;
use Exception;

class ihcController extends BaseController
{
    protected $hpaModel;
    protected $frsModel;
    protected $srsModel;
    protected $ihcModel;
    protected $usersModel;
    protected $patientModel;
    protected $simrsModel;
    protected $kunjunganModel;
    protected $penerimaan_ihc;
    protected $pemotongan_ihc;
    protected $pembacaan_ihc;
    protected $penulisan_ihc;
    protected $pemverifikasi_ihc;
    protected $authorized_ihc;
    protected $pencetakan_ihc;
    protected $mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new hpaModel();
        $this->frsModel = new frsModel();
        $this->srsModel = new srsModel();
        $this->ihcModel = new ihcModel();
        $this->usersModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->simrsModel = new SimrsModel();
        $this->kunjunganModel = new KunjunganModel();
        $this->penerimaan_ihc = new Penerimaan_ihc();;
        $this->pembacaan_ihc = new Pembacaan_ihc();
        $this->penulisan_ihc = new Penulisan_ihc();
        $this->pemverifikasi_ihc = new Pemverifikasi_ihc();
        $this->authorized_ihc = new Authorized_ihc();
        $this->pencetakan_ihc = new Pencetakan_ihc();
        $this->mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $ihcData = $this->ihcModel->getihcWithPatient();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'ihcData' => $ihcData
        ];

        return view('Ihc/index', $data);
    }

    public function index_buku_penerima()
    {
        // Mengambil data ihc menggunakan properti yang sudah ada
        $ihcData = $this->ihcModel->getihcWithPatientDESC() ?? [];

        // Kirimkan data ke view
        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData' => $ihcData,
        ];

        return view('ihc/index_buku_penerima', $data);
    }

    public function register()
    {
        $lastihc = $this->ihcModel->getLastKodeihc();
        $currentYear = date('y');
        $nextNumber = 1;

        if ($lastihc) {
            $lastKode = $lastihc['kode_ihc'];
            $lastParts = explode('/', $lastKode);
            $lastYear = $lastParts[1];

            if ($lastYear == $currentYear) {
                $lastNumber = (int) explode('.', $lastParts[0])[1];
                $nextNumber = $lastNumber + 1;
            }
        }
        $kodeihc = sprintf('IHC.%02d/%s', $nextNumber, $currentYear);

        // --- Variabel awal ---
        $idtransaksi   = $this->request->getGet('idtransaksi');
        $normPasien    = $this->request->getGet('norm_pasien');
        $patient       = null;
        $riwayat_api   = [];
        $riwayat_hpa   = [];
        $riwayat_frs   = [];
        $riwayat_srs   = [];
        $riwayat_ihc   = [];

        // --- Skenario 1: dari idtransaksi ---
        if ($idtransaksi) {
            $kunjungan = $this->kunjunganModel->where('idtransaksi', $idtransaksi)->first();

            if ($kunjungan) {
                $id_pasien = isset($kunjungan['idpasien']) ? (int) $kunjungan['idpasien'] : null;

                $patient = [
                    'id_pasien'             => $id_pasien,
                    'norm_pasien'           => $kunjungan['norm'] ?? '',
                    'nama_pasien'           => $kunjungan['nama'] ?? '',
                    'alamat_pasien'         => $kunjungan['alamat'] ?? '',
                    'kota'                  => $kunjungan['kota'] ?? '',
                    'tanggal_lahir_pasien'  => $kunjungan['tgl_lhr'] ?? '',
                    'jenis_kelamin_pasien'  => $kunjungan['jeniskelamin'] ?? '',
                    'status_pasien'         => $kunjungan['jenispasien'] ?? '',
                    'unitasal'              => $kunjungan['unitasal'] ?? '',
                    'dokterperujuk'         => $kunjungan['dokterperujuk'] ?? '',
                    'pemeriksaan'           => $kunjungan['pemeriksaan'] ?? '',
                    'lokasi_spesimen'       => $kunjungan['statuslokasi'] ?? '',
                    'diagnosa_klinik'       => $kunjungan['diagnosaklinik'] ?? '',
                    'tindakan_spesimen'     => $kunjungan['pemeriksaan'] ?? '',
                    'id_transaksi'          => isset($kunjungan['idtransaksi']) ? (int) $kunjungan['idtransaksi'] : null,
                    'tanggal_transaksi'     => $kunjungan['tanggal'] ?? '',
                    'no_register'           => $kunjungan['register'] ?? ''
                ];

                if ($id_pasien) {
                    $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
                    $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
                    $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
                    $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
                }
            }
        }
        // --- Skenario 2: dari norm_pasien ---
        elseif ($normPasien) {
            $patient   = $this->patientModel->where('norm_pasien', $normPasien)->first();
            $id_pasien = $patient['id_pasien'] ?? null;

            if ($id_pasien) {
                $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
                $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
                $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
                $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
            }
        }

        // --- Panggil API di semua kondisi ---
        if (!empty($patient['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($patient['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }

        $data = [
            'id_user'       => session()->get('id_user'),
            'nama_user'     => session()->get('nama_user'),
            'kode_ihc'      => $kodeihc,
            'patient'       => $patient,
            'riwayat_api'   => $riwayat_api,
            'riwayat_hpa'   => $riwayat_hpa,
            'riwayat_frs'   => $riwayat_frs,
            'riwayat_srs'   => $riwayat_srs,
            'riwayat_ihc'   => $riwayat_ihc,
        ];

        return view('ihc/Register', $data);
    }

    public function insert()
    {
        try {
            // Set rules untuk validasi
            $this->validation->setRules([
                'kode_ihc' => [
                    'rules' => 'required|is_unique[ihc.kode_ihc]',
                    'errors' => [
                        'required' => 'Kode ihc harus diisi.',
                        'is_unique' => 'Kode ihc sudah terdaftar!',
                    ],
                ],
            ]);
            // Jalankan validasi
            $data = $this->request->getPost();
            if (!$this->validation->run($data)) {
                return redirect()->back()->withInput()->with('error', $this->validation->getErrors());
            }
            // Gabungkan unit_asal dan unit_asal_detail
            $unit_asal = $data['unit_asal'] . ' ' . ($data['unit_asal_detail'] ?? '');
            // Tentukan dokter_pengirim
            $dokter_pengirim = ($data['dokter_pengirim'] !== "lainnya")
                ? $data['dokter_pengirim']
                : $data['dokter_pengirim_custom'];
            // Tentukan tindakan_spesimen
            $tindakan_spesimen = !empty($data['tindakan_spesimen']) ? $data['tindakan_spesimen'] : $data['tindakan_spesimen_custom'];

            // ====== CEK PASIEN DI TABEL PATIENT ======
            $id_pasien   = $data['id_pasien'];
            $norm_pasien = $data['norm_pasien'] ?? '';

            // Cek apakah norm_pasien sudah ada di database
            $patient = $this->patientModel
                ->where('norm_pasien', $norm_pasien)
                ->first();

            // Data yang akan disimpan atau diperbarui
            $patientData = [
                'id_pasien'    => (int) $id_pasien,
                'norm_pasien'  => $norm_pasien,
                'nama_pasien'  => $data['nama_pasien'] ?? '',
                'alamat_pasien' => $data['alamat_pasien'] ?? '',
                'kota' => $data['kota'] ?? '',
                'tanggal_lahir_pasien' => $data['tanggal_lahir_pasien'] ?? null,
                'jenis_kelamin_pasien' => $data['jenis_kelamin_pasien'] ?? '',
                'status_pasien' => $data['status_pasien'] ?? '',
                // Tambahkan kolom lain jika ada
            ];

            if ($patient) {
                // ✅ Update data berdasarkan norm_pasien
                if ($this->patientModel->update($patient['id_pasien'], $patientData)) {
                    session()->setFlashdata('success', 'Data pasien diperbarui.');
                }
            } else {
                // ✅ Insert data baru jika norm_pasien belum ada
                if ($this->patientModel->insert($patientData)) {
                    session()->setFlashdata('success', 'Data pasien ditambahkan.');
                }
            }

            // Data yang akan disimpan
            $ihcData = [
                'kode_ihc' => $data['kode_ihc'],
                'id_pasien' => (int) $data['id_pasien'],
                'unit_asal' => $unit_asal,
                'dokter_pengirim' => $dokter_pengirim,
                'tanggal_permintaan' => $data['tanggal_permintaan'] ?: null,
                'tanggal_hasil' => $data['tanggal_hasil'] ?: null,
                'lokasi_spesimen' => $data['lokasi_spesimen'],
                'kode_block_ihc' => $data['kode_block_ihc'],
                'tindakan_spesimen' => $tindakan_spesimen,
                'diagnosa_klinik' => $data['diagnosa_klinik'],
                'no_tlp_ihc' => $data['no_tlp_ihc'],
                'no_bpjs_ihc' => $data['no_bpjs_ihc'],
                'no_ktp_ihc' => $data['no_ktp_ihc'],
                'id_transaksi' => ($data['id_transaksi'] ?? '') === '' ? null : (int) $data['id_transaksi'],
                'tanggal_transaksi' => $data['tanggal_transaksi'] ?: null,
                'no_register' => $data['no_register'] ?? '',
                'status_ihc' => 'Penerimaan',
            ];
            // Simpan data ihc
            if (!$this->ihcModel->insert($ihcData)) {
                throw new Exception('Gagal menyimpan data ihc: ' . $this->ihcModel->errors());
            }
            // Jika ada id_transaksi, update kunjungan
            $idtransaksi = $data['id_transaksi'] ?? null;
            if (!empty($idtransaksi)) {
                // Ambil register dari idtransaksi
                $kunjungan = $this->kunjunganModel
                    ->select('register')
                    ->where('idtransaksi', $idtransaksi)
                    ->first();
            
                if ($kunjungan && !empty($kunjungan['register'])) {
                    $register = $kunjungan['register'];
            
                    // Update semua hasil untuk register tersebut
                    $this->kunjunganModel
                        ->where('register', $register)
                        ->set(['status' => 'Terdaftar'])
                        ->update();
                }
            }            
            // Mendapatkan ID ihc yang baru diinsert
            $id_ihc = $this->ihcModel->getInsertID();
            // Data penerimaan
            $penerimaanData = [
                'id_ihc' => $id_ihc,
                'status_penerimaan_ihc' => 'Belum Penerimaan',
            ];
            // Simpan data penerimaan
            if (!$this->penerimaan_ihc->insert($penerimaanData)) {
                throw new Exception('Gagal menyimpan data penerimaan: ' . $this->penerimaan_ihc->errors());
            }
            // Data mutu
            $mutuData = [
                'id_ihc' => $id_ihc,
            ];
            if (!$this->mutu_ihc->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu: ' . $this->mutu_ihc->errors());
            }
            // Redirect dengan pesan sukses
            return redirect()->to('/dashboard')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            log_message('error', 'Error inserting data: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        $id_ihc = $this->request->getPost('id_ihc');
        if (!$id_ihc) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID ihc tidak valid.']);
        }
        if ($this->ihcModel->delete($id_ihc)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data.']);
        }
    }

    // Menampilkan form edit ihc
    public function edit($id_ihc)
    {
        // Ambil data ihc berdasarkan ID
        $ihc = $this->ihcModel->getihcWithRelationsProses($id_ihc);
        if (!$ihc) {
            return redirect()->back()->with('message', ['error' => 'ihc tidak ditemukan.']);
        }
        $id_penerimaan_ihc = $ihc['id_penerimaan_ihc'];
        $id_pembacaan_ihc = $ihc['id_pembacaan_ihc'];
        $id_pemnulisan_ihc = $ihc['id_penulisan_ihc'];
        $id_pasien = $ihc['id_pasien'];
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Ambil data pemotongan berdasarkan ID
        $penerima_ihc = $this->penerimaan_ihc->find($id_penerimaan_ihc);
        $pembacaan_ihc = $this->pembacaan_ihc->find($id_pembacaan_ihc);
        $penulisan_ihc = $this->penulisan_ihc->find($id_pemnulisan_ihc);
        $dokter_nama = null;
        $analis_nama = null;
        if ($pembacaan_ihc) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pembacaan_ihc['id_user_dokter_pembacaan_ihc'])) {
                $dokter = $this->usersModel->find($pembacaan_ihc['id_user_dokter_pembacaan_ihc']);
                $dokter_nama = $dokter ? $dokter['nama_user'] : null;
            }

            if (!empty($pembacaan_ihc['id_user_pembacaan'])) {
                $analis = $this->usersModel->find($pembacaan_ihc['id_user_pembacaan']);
                $analis_nama = $analis ? $analis['nama_user'] : null;
            }
            // Tambahkan ke array pembacaan
            $pembacaan_ihc['dokter_nama'] = $dokter_nama;
            $pembacaan_ihc['analis_nama'] = $analis_nama;
        }
        // Data yang dikirim ke view
        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihc'        => $ihc,
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'penerimaan_ihc' => $penerima_ihc,
            'pembacaan_ihc' => $pembacaan_ihc,
            'penulisan_ihc' => $penulisan_ihc,
            'users'      => $users,
        ];

        return view('ihc/edit', $data);
    }

    // Menampilkan form edit ihc mikroskopis
    public function edit_mikroskopis($id_ihc)
    {
        // Ambil data ihc berdasarkan ID
        $ihc = $this->ihcModel->getihcWithRelationsProses($id_ihc);
        $id_pasien = $ihc['id_pasien'];
        // --- Riwayat API ---
        if (!empty($ihc['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($ihc['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        if (!$ihc) {
            return redirect()->back()->with('message', ['error' => 'ihc tidak ditemukan.']);
        }
        // Jika field mulai_pembacaan_frs masih kosong dan ada id_pembacaan_frs
        if (!empty($ihc['id_pembacaan_ihc']) && empty($ihc['mulai_pembacaan_ihc'])) {
            $this->pembacaan_ihc->update($ihc['id_pembacaan_ihc'], [
                'mulai_pembacaan_ihc' => date('Y-m-d H:i:s'),
            ]);
            // Refresh data
            $ihc = $this->ihcModel->getihcWithRelationsProses($id_ihc);
        }
        // Ambil data pemotongan dan pembacaan_ihc berdasarkan ID
        $id_pembacaan_ihc = $ihc['id_pembacaan_ihc'];
        // Ambil data pembacaan_ihc jika tersedia
        $pembacaan_ihc = $id_pembacaan_ihc ? $this->pembacaan_ihc->find($id_pembacaan_ihc) : [];
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Persiapkan data yang akan dikirim ke view
        $data = [
            'ihc'             => $ihc,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan_ihc'   => $pembacaan_ihc,
            'users'           => $users,
            'id_user'         => session()->get('id_user'),
            'nama_user'       => session()->get('nama_user'),
        ];

        return view('ihc/edit_mikroskopis', $data);
    }

    public function edit_penulisan($id_ihc)
    {
        // Ambil data ihc berdasarkan ID
        $ihc = $this->ihcModel->getihcWithRelationsProses($id_ihc);
        $id_pasien = $ihc['id_pasien'];
        if (!$ihc) {
            return redirect()->back()->with('message', ['error' => 'ihc tidak ditemukan.']);
        }
        // Jika field mulai_penulisan_ihc masih kosong dan ada id_penulisan_ihc
        if (!empty($ihc['id_penulisan_ihc']) && empty($ihc['mulai_penulisan_ihc'])) {
            $this->penulisan_ihc->update($ihc['id_penulisan_ihc'], [
                'mulai_penulisan_ihc' => date('Y-m-d H:i:s'),
            ]);

            // Refresh data
            $ihc = $this->ihcModel->getihcWithRelationsProses($id_ihc);
        }
        // Inisialisasi array untuk pembacaan dan penulisan ihc
        $pembacaan_ihc = [];
        $penulisan_ihc = [];
        // Ambil data pembacaan ihc jika tersedia
        if (!empty($ihc['id_pembacaan_ihc'])) {
            $pembacaan_ihc = $this->pembacaan_ihc->find($ihc['id_pembacaan_ihc']) ?? [];
            // Ambil nama dokter dari pembacaan jika tersedia
            if (!empty($pembacaan_ihc['id_user_dokter_pembacaan_ihc'])) {
                $dokter = $this->usersModel->find($pembacaan_ihc['id_user_dokter_pembacaan_ihc']);
                $pembacaan_ihc['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
            } else {
                $pembacaan_ihc['dokter_nama'] = null;
            }
        }
        // Ambil data penulisan ihc jika tersedia
        if (!empty($ihc['id_penulisan_ihc'])) {
            $penulisan_ihc = $this->penulisan_ihc->find($ihc['id_penulisan_ihc']) ?? [];
        }
        // Ambil daftar user dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // --- Riwayat API ---
        if (!empty($ihc['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($ihc['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        // Data yang akan dikirim ke view
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'ihc' => $ihc,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan' => $pembacaan_ihc,
            'penulisan' => $penulisan_ihc,
            'users' => $users,
        ];

        return view('ihc/edit_penulisan', $data);
    }

    public function edit_print($id_ihc)
    {
        // Ambil data ihc berdasarkan ID
        $ihc = $this->ihcModel->getIhcWithRelationsProses($id_ihc);
        $id_pasien = $ihc['id_pasien'];
        // --- Riwayat API ---
        if (!empty($ihc['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($ihc['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);

        // Jika field mulai_pemverifikasi_ihc masih kosong dan ada id_pemverifikasi_ihc
        if (!empty($ihc['id_pemverifikasi_ihc']) && empty($ihc['mulai_pemverifikasi_ihc'])) {
            $this->pemverifikasi_ihc->update($ihc['id_pemverifikasi_ihc'], [
                'mulai_pemverifikasi_ihc' => date('Y-m-d H:i:s'),
            ]);

            // Refresh data
            $ihc = $this->ihcModel->getIhcWithRelationsProses($id_ihc);
        }

        // Jika field mulai_authorized_ihc masih kosong dan ada id_authorized_ihc
        if (!empty($ihc['id_authorized_ihc']) && empty($ihc['mulai_authorized_ihc'])) {
            $this->authorized_ihc->update($ihc['id_authorized_ihc'], [
                'mulai_authorized_ihc' => date('Y-m-d H:i:s'),
            ]);

            // Refresh data
            $ihc = $this->ihcModel->getIhcWithRelationsProses($id_ihc);
        }

        // Jika field mulai_pencetakan_ihc masih kosong dan ada id_pencetakan_ihc
        if (!empty($ihc['id_pencetakan_ihc']) && empty($ihc['mulai_pencetakan_ihc'])) {
            $this->pencetakan_ihc->update($ihc['id_pencetakan_ihc'], [
                'mulai_pencetakan_ihc' => date('Y-m-d H:i:s'),
            ]);

            // Refresh data
            $ihc = $this->ihcModel->getIhcWithRelationsProses($id_ihc);
        }

        // Ambil data pembacaan ihc jika tersedia
        $pembacaan_ihc = [];
        if (!empty($ihc['id_pembacaan_ihc'])) {
            $pembacaan_ihc = $this->pembacaan_ihc->find($ihc['id_pembacaan_ihc']) ?? [];
            if (!empty($pembacaan_ihc['id_user_dokter_pembacaan_ihc'])) {
                $dokter = $this->usersModel->find($pembacaan_ihc['id_user_dokter_pembacaan_ihc']);
                $pembacaan_ihc['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
            } else {
                $pembacaan_ihc['dokter_nama'] = null;
            }
        }

        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'ihc' => $ihc,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan_ihc' => $pembacaan_ihc,
        ];

        return view('ihc/edit_print', $data);
    }

    public function update($id_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');
        $ihc = $this->ihcModel->getihcWithRelationsProses($id_ihc);
        if (!$ihc) {
            return redirect()->back()->with('message', ['error' => 'ihc tidak ditemukan.']);
        }
        $id_pembacaan_ihc = $ihc['id_pembacaan_ihc'];
        // Validasi form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_ihc' => [
                'rules' => 'required|is_unique[ihc.kode_ihc,id_ihc,' . $id_ihc . ']',
                'errors' => [
                    'required' => 'Kode ihc harus diisi.',
                    'is_unique' => 'Kode ihc sudah terdaftar!',
                ],
            ],
        ]);
        if (!$this->validate($validation->getRules())) {
            session()->setFlashdata('errors', $validation->getErrors()); // Simpan error ke session
            return redirect()->back()->withInput(); // Redirect kembali ke halaman form
        }
        // Mengambil data dari form
        $data = $this->request->getPost();
        $page_source = $this->request->getPost('page_source');
        // Mengubah 'jumlah_slide' jika memilih 'lainnya'
        if ($this->request->getPost('jumlah_slide') === 'lainnya') {
            $data['jumlah_slide'] = $this->request->getPost('jumlah_slide') === 'lainnya'
                ? $this->request->getPost('jumlah_slide_custom')
                : $this->request->getPost('jumlah_slide');
        }
        // Proses update tabel ihc
        if ($this->ihcModel->update($id_ihc, $data)) {
            // Update data pembacaan jika id_user_dokter_pembacaan_ihc ada
            if (!empty($data['id_user_dokter_pembacaan_ihc'])) {
                $pembacaan = $this->pembacaan_ihc->where('id_pembacaan_ihc', $id_pembacaan_ihc)->first();

                if ($pembacaan) {
                    $this->pembacaan_ihc->update($pembacaan['id_pembacaan_ihc'], [
                        'id_user_dokter_pembacaan_ihc' => $data['id_user_dokter_pembacaan_ihc'],
                    ]);
                }
            }
            switch ($page_source) {
                case 'edit_mikroskopis':
                    $id_pembacaan_ihc = $this->request->getPost('id_pembacaan_ihc');
                    $this->pembacaan_ihc->update($id_pembacaan_ihc, [
                        'id_user_pembacaan_ihc' => $id_user,
                        'status_pembacaan_ihc' => 'Selesai Pembacaan',
                        'selesai_pembacaan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect()->to('ihc/edit_mikroskopis/' . $id_ihc)->with('success', 'Data mikroskopis berhasil diperbarui.');
                case 'edit_penulisan':
                    $id_penulisan_ihc = $this->request->getPost('id_penulisan_ihc');
                    $this->penulisan_ihc->update($id_penulisan_ihc, [
                        'id_user_penulisan_ihc' => $id_user,
                        'status_penulisan_ihc' => 'Selesai Penulisan',
                        'selesai_penulisan_ihc' => date('Y-m-d H:i:s'),
                    ]);
                    // Ambil data dari form
                    $lokasi_spesimen = $this->request->getPost('lokasi_spesimen');
                    $diagnosa_klinik = $this->request->getPost('diagnosa_klinik');
                    $makroskopis_ihc = $this->request->getPost('makroskopis_ihc');
                    $mikroskopis_ihc = $this->request->getPost('mikroskopis_ihc');
                    $tindakan_spesimen = $this->request->getPost('tindakan_spesimen');
                    $kode_block_ihc = $this->request->getPost('kode_block_ihc');
                    $ER    = $this->request->getPost('ER') ? 1 : 0;
                    $PR    = $this->request->getPost('PR') ? 1 : 0;
                    $HER2  = $this->request->getPost('HER2') ? 1 : 0;
                    $KI67  = $this->request->getPost('KI67') ? 1 : 0;
                    $hasil_ihc = $this->request->getPost('hasil_ihc');
                    // Simpan data lokasi, diagnosa, makroskopis, mikroskopis, hasil terlebih dahulu
                    $this->ihcModel->update($id_ihc, [
                        'lokasi_spesimen' => $lokasi_spesimen,
                        'diagnosa_klinik' => $diagnosa_klinik,
                        'makroskopis_ihc' => $makroskopis_ihc,
                        'mikroskopis_ihc' => $mikroskopis_ihc,
                        'kode_block_ihc' => $kode_block_ihc,
                        'ER'                => $ER,
                        'PR'                => $PR,
                        'HER2'              => $HER2,
                        'KI67'              => $KI67,
                        'hasil_ihc' => $hasil_ihc,
                    ]);
                    // Setelah semua data tersimpan, buat data print_ihc
                    $print_ihc = '
                    <table width="800pt" height="80">
                            <tbody>
                                <tr>
                                    <td style="border: none;" width="200pt">
                                        <font size="5" face="verdana"><b>LOKASI</b></font>
                                    </td>
                                    <td style="border: none;" width="10pt">
                                        <font size="5" face="verdana"><b>:</b><br></font>
                                    </td>
                                    <td style="border: none;" width="590pt">
                                        <font size="5" face="verdana">
                                            <b>' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $lokasi_spesimen))) . '<br></b>
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;" width="200pt">
                                        <font size="5" face="verdana"><b>DIAGNOSA KLINIK</b></font>
                                    </td>
                                    <td style="border: none;" width="10pt">
                                        <font size="5" face="verdana"><b>:</b><br></font>
                                    </td>
                                    <td style="border: none;" width="590pt">
                                        <font size="5" face="verdana"><b>' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $diagnosa_klinik))) . '<br></b></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;" width="200pt">
                                        <font size="5" face="verdana"><b>ICD</b></font>
                                    </td>
                                    <td style="border: none;" width="10pt">
                                        <font size="5" face="verdana"><b>:</b></font>
                                    </td>
                                    <td style="border: none;" width="590pt">
                                        <font size="5" face="verdana"><br></font>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                            <font size="5" face="verdana"><b>LAPORAN PEMERIKSAAN:</b></font>
                            <div>
                                <font size="5" face="verdana"><b>MAKROSKOPIK :</b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana">' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $makroskopis_ihc))) . '</font>
                            </div>
                            <br>
                            <div>
                                <font size="5" face="verdana"><b>MIKROSKOPIK :</b>
                                ' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>', '<br>'], '', $mikroskopis_ihc))) . '
                                </font>
                            </div>
                            <br>
                            <div>
                                <font size="5" face="verdana"><b>KESIMPULAN :</b> Blok Parafin No. ' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $kode_block_ihc))) . ', ' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $tindakan_spesimen))) . ':</b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><b>' . strtoupper(htmlspecialchars(str_replace(['<p>', '</p>'], '', $hasil_ihc))) . '</b></font>
                            </div>
                            <br>';
                    // Simpan print_ihc setelah semua data yang dibutuhkan telah ada
                    $this->ihcModel->update($id_ihc, [
                        'print_ihc' => $print_ihc,
                    ]);
                    return redirect()->to('ihc/edit_penulisan/' . $id_ihc)->with('success', 'Data penulisan berhasil diperbarui.');
                default:
                    return redirect()->back()->with('success', 'Data berhasil diperbarui.');
            }
        }
        return redirect()->back()->with('error', 'Gagal memperbarui data.');
    }

    public function update_buku_penerima()
    {
        // Set zona waktu Indonesia/Jakarta (opsional jika sudah diatur dalam konfigurasi)
        date_default_timezone_set('Asia/Jakarta');
        // Mendapatkan data dari request
        $id_ihc = $this->request->getPost('id_ihc');
        // Inisialisasi model
        $ihcModel = new ihcModel();

        // Mengambil data dari form
        $penerima_ihc = $this->request->getPost('penerima_ihc');

        // Data yang akan diupdate
        $data = [
            'penerima_ihc' => $penerima_ihc,
            'tanggal_penerima' => date('Y-m-d H:i:s'),
        ];

        // Update data penerima_ihc berdasarkan id_ihc
        $ihcModel->updatePenerima($id_ihc, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('ihc/index_buku_penerima')->with('success', 'Penerima berhasil disimpan.');
    }


    public function update_print($id_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');
        // Mendapatkan id_ihc dari POST
        if (!$id_ihc) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID ihc tidak ditemukan.');
        }
        // Mengambil data dari POST dan melakukan update
        $data = $this->request->getPost();
        $this->ihcModel->update($id_ihc, $data);
        $redirect = $this->request->getPost('redirect');
        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }
        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'index_pemverifikasi_ihc' && isset($_POST['id_pemverifikasi_ihc'])) {
            $id_pemverifikasi_ihc = $this->request->getPost('id_pemverifikasi_ihc');
            $this->pemverifikasi_ihc->update($id_pemverifikasi_ihc, [
                'id_user_pemverifikasi_ihc' => $id_user,
                'status_pemverifikasi_ihc' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi_ihc' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi_ihc/index')->with('success', 'Data berhasil diverifikasi.');
        }
        if ($redirect === 'index_authorized_ihc' && isset($_POST['id_authorized_ihc'])) {
            $id_authorized_ihc = $this->request->getPost('id_authorized_ihc');
            $this->authorized_ihc->update($id_authorized_ihc, [
                'id_user_authorized_ihc' => $id_user,
                'id_user_dokter_authorized_ihc' => $id_user,
                'status_authorized_ihc' => 'Selesai Authorized',
                'selesai_authorized_ihc' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('authorized_ihc/index')->with('success', 'Data berhasil diauthorized.');
        }
        if ($redirect === 'index_pencetakan_ihc' && isset($_POST['id_pencetakan_ihc'])) {
            $id_pencetakan_ihc = $this->request->getPost('id_pencetakan_ihc');
            $this->pencetakan_ihc->update($id_pencetakan_ihc, [
                'id_user_pencetakan_ihc' => $id_user,
                'status_pencetakan_ihc' => 'Selesai Pencetakan',
                'selesai_pencetakan_ihc' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan_ihc/index')->with('success', 'Data berhasil simpan.');
        }
        // Jika redirect tidak sesuai dengan yang diharapkan
        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
    }

    public function update_status()
    {
        // Ambil data dari POST
        $id_ihc = $this->request->getPost('id_ihc');
        $status_ihc = $this->request->getPost('status_ihc');
        // Data yang akan diupdate
        $data = [
            'status_ihc' => $status_ihc,
        ];
        // Gunakan model yang sudah diinisialisasi di konstruktor
        $this->ihcModel->update($id_ihc, $data);
        // Redirect dengan pesan sukses
        return redirect()->to('ihc/index')->with('success', 'Status IHC berhasil disimpan.');
    }

    public function uploadFotoMakroskopis($id_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');
        $ihcModel = new ihcModel();

        // Ambil data ihc untuk mendapatkan nama file lama
        $ihc = $ihcModel->find($id_ihc);

        if (!$ihc) {
            return redirect()->back()->with('error', 'Data ihc tidak ditemukan.');
        }

        // Ambil kode_ihc dan ekstrak nomor dari format "H.nomor/25"
        $kode_ihc = $ihc['kode_ihc'];
        preg_match('/H\.(\d+)\/\d+/', $kode_ihc, $matches);
        $kode_ihc = isset($matches[1]) ? $matches[1] : '000';

        // Validasi input file
        $validation = \Config\Services::validation();
        $validation->setRules([
            'foto_makroskopis_ihc' => [
                'rules' => 'uploaded[foto_makroskopis_ihc]|ext_in[foto_makroskopis_ihc,jpg,jpeg,png]',
                'errors' => [
                    'uploaded' => 'Harap unggah file foto makroskopis.',
                    'ext_in' => 'File harus berformat JPG, JPEG, atau PNG.',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Proses upload file
        $file = $this->request->getFile('foto_makroskopis_ihc');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru berdasarkan waktu
            $newFileName = $kode_ihc . date('dmY') . '.' . $file->getExtension();

            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/ihc/makroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Membuat folder jika belum ada
            }

            // Hapus file lama jika ada
            if (!empty($ihc['foto_makroskopis_ihc'])) {
                $oldFilePath = $uploadPath . $ihc['foto_makroskopis_ihc'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Pindahkan file baru ke folder tujuan dengan nama baru
            if ($file->move($uploadPath, $newFileName)) {
                // Update nama file baru di database
                $ihcModel->update($id_ihc, ['foto_makroskopis_ihc' => $newFileName]);

                // Berhasil, redirect dengan pesan sukses
                return redirect()->back()->with('success', 'Foto makroskopis berhasil diunggah dan diperbarui.');
            } else {
                // Gagal memindahkan file
                return redirect()->back()->with('error', 'Gagal mengunggah foto makroskopis.');
            }
        } else {
            // Jika file tidak valid
            return redirect()->back()->with('error', 'File tidak valid atau tidak diunggah.');
        }
    }

    public function uploadFotoMikroskopis($id_ihc)
    {
        date_default_timezone_set('Asia/Jakarta');
        $ihcModel = new ihcModel();

        // Ambil data ihc untuk mendapatkan nama file lama
        $ihc = $ihcModel->find($id_ihc);

        // Validasi input file
        $validation = \Config\Services::validation();
        $validation->setRules([
            'foto_mikroskopis_ihc' => [
                'rules' => 'uploaded[foto_mikroskopis_ihc]|ext_in[foto_mikroskopis_ihc,jpg,jpeg,png]|max_size[foto_mikroskopis_ihc,5000]', // 5MB max size
                'errors' => [
                    'uploaded' => 'Harap unggah file foto mikroskopis.',
                    'ext_in' => 'File harus berformat JPG, JPEG, atau PNG.',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Proses upload file
        $file = $this->request->getFile('foto_mikroskopis_ihc');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru berdasarkan waktu
            $newFileName = date('HisdmY') . '.' . $file->getExtension();

            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/ihc/mikroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Membuat folder jika belum ada
            }

            // Hapus file lama jika ada
            if (!empty($ihc['foto_mikroskopis_ihc'])) {
                $oldFilePath = $uploadPath . $ihc['foto_mikroskopis_ihc'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Pindahkan file baru ke folder tujuan dengan nama baru
            if ($file->move($uploadPath, $newFileName)) {
                // Update nama file baru di database
                $ihcModel->update($id_ihc, ['foto_mikroskopis_ihc' => $newFileName]);

                // Berhasil, redirect dengan pesan sukses
                return redirect()->back()->with('success', 'Foto mikroskopis berhasil diunggah dan diperbarui.');
            } else {
                // Gagal memindahkan file
                return redirect()->back()->with('error', 'Gagal mengunggah foto mikroskopis.');
            }
        } else {
            // Jika file tidak valid
            return redirect()->back()->with('error', 'File tidak valid atau tidak diunggah.');
        }
    }

    public function laporan_pemeriksaan()
    {
        $ihcData = $this->ihcModel->getihcWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData' => $ihcData,
        ];

        return view('ihc/laporan/laporan_pemeriksaan', $data);
    }

    public function laporan_kerja()
    {
        $ihcData = $this->ihcModel->getihcWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData' => $ihcData,
        ];

        return view('ihc/laporan/laporan_kerja', $data);
    }

    public function laporan_oprasional()
    {
        $ihcData = $this->ihcModel->getihcWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData' => $ihcData,
        ];

        return view('ihc/laporan/laporan_oprasional', $data);
    }

    public function laporan_ER()
    {
        $ihcData = $this->ihcModel->kontrol_ER() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData' => $ihcData,
        ];

        return view('ihc/laporan/laporan_ER', $data);
    }

    public function laporan_PR()
    {
        $ihcData = $this->ihcModel->kontrol_PR() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData' => $ihcData,
        ];

        return view('ihc/laporan/laporan_PR', $data);
    }

    public function laporan_HER2()
    {
        $ihcData = $this->ihcModel->kontrol_HER2() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData' => $ihcData,
        ];

        return view('ihc/laporan/laporan_HER2', $data);
    }

    public function laporan_KI67()
    {
        $ihcData = $this->ihcModel->kontrol_KI67() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData' => $ihcData,
        ];

        return view('ihc/laporan/laporan_KI67', $data);
    }

    public function filter()
    {
        $filterField = $this->request->getGet('filterInput');
        $filterValue = $this->request->getGet('filterValue');
        $startDate   = $this->request->getGet('filterDate');
        $endDate     = $this->request->getGet('filterDate2');

        $filteredData = $this->ihcModel->filterihcWithRelations(
            $filterField ?: null,
            $filterValue ?: null,
            $startDate,
            $endDate
        );

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'ihcData'       => $filteredData,
        ];

        return view('ihc/laporan/laporan_pemeriksaan', $data);
    }
}
