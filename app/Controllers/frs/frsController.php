<?php

namespace App\Controllers\Frs;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\Frs\FrsModel;
use App\Models\Srs\SrsModel;
use App\Models\Ihc\IhcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\SimrsModel;
use App\Models\KunjunganModel;
use App\Models\PengirimanDataSimrsModel;
use App\Models\Frs\Proses\Penerimaan_frs;
use App\Models\Frs\Proses\Pembacaan_frs;
use App\Models\Frs\Proses\Penulisan_frs;
use App\Models\Frs\Proses\Pemverifikasi_frs;
use App\Models\Frs\Proses\Authorized_frs;
use App\Models\Frs\Proses\Pencetakan_frs;
use App\Models\Frs\Mutu_frs;
use Exception;


class FrsController extends BaseController
{
    protected $hpaModel;
    protected $frsModel;
    protected $srsModel;
    protected $ihcModel;
    protected $usersModel;
    protected $patientModel;
    protected $simrsModel;
    protected $kunjunganModel;
    protected $pengirimanDataSimrsModel;
    protected $penerimaan_frs;
    protected $pemotongan_frs;
    protected $pembacaan_frs;
    protected $penulisan_frs;
    protected $pemverifikasi_frs;
    protected $authorized_frs;
    protected $pencetakan_frs;
    protected $mutu_frs;
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
        $this->pengirimanDataSimrsModel = new PengirimanDataSimrsModel();
        $this->penerimaan_frs = new Penerimaan_frs();;
        $this->pembacaan_frs = new Pembacaan_frs();
        $this->penulisan_frs = new Penulisan_frs();
        $this->pemverifikasi_frs = new Pemverifikasi_frs();
        $this->authorized_frs = new Authorized_frs();
        $this->pencetakan_frs = new Pencetakan_frs();
        $this->mutu_frs = new Mutu_frs();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $frsData = $this->frsModel->getfrsWithPatient();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'frsData' => $frsData
        ];

        return view('Frs/index', $data);
    }

    public function index_buku_penerima()
    {
        // Mengambil data frs menggunakan properti yang sudah ada
        $frsData = $this->frsModel->getfrsWithPatientDESC() ?? [];

        // Kirimkan data ke view
        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'frsData' => $frsData,
        ];

        return view('frs/index_buku_penerima', $data);
    }

    public function register()
    {
        $lastfrs = $this->frsModel->getLastKodefrs();
        $currentYear = date('y');
        $nextNumber = 1;

        if ($lastfrs) {
            $lastKode = $lastfrs['kode_frs'];
            $lastParts = explode('/', $lastKode);
            $lastYear = $lastParts[1];

            if ($lastYear == $currentYear) {
                $lastNumber = (int) explode('.', $lastParts[0])[1];
                $nextNumber = $lastNumber + 1;
            }
        }
        $kodefrs = sprintf('FRS.%02d/%s', $nextNumber, $currentYear);

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
            'kode_frs'      => $kodefrs,
            'patient'       => $patient,
            'riwayat_api'   => $riwayat_api,
            'riwayat_hpa'   => $riwayat_hpa,
            'riwayat_frs'   => $riwayat_frs,
            'riwayat_srs'   => $riwayat_srs,
            'riwayat_ihc'   => $riwayat_ihc,
        ];

        return view('frs/Register', $data);
    }

    public function insert()
    {
        try {
            // Set rules untuk validasi
            $this->validation->setRules([
                'kode_frs' => [
                    'rules' => 'required|is_unique[frs.kode_frs]',
                    'errors' => [
                        'required' => 'Kode frs harus diisi.',
                        'is_unique' => 'Kode frs sudah terdaftar!',
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
            $frsData = [
                'kode_frs' => $data['kode_frs'],
                'id_pasien' => (int) $data['id_pasien'],
                'unit_asal' => $unit_asal,
                'dokter_pengirim' => $dokter_pengirim,
                'tanggal_permintaan' => $data['tanggal_permintaan'] ?: null,
                'tanggal_hasil' => $data['tanggal_hasil'] ?: null,
                'lokasi_spesimen' => $data['lokasi_spesimen'],
                'tindakan_spesimen' => $tindakan_spesimen,
                'diagnosa_klinik' => $data['diagnosa_klinik'],
                'id_transaksi' => ($data['id_transaksi'] ?? '') === '' ? null : (int) $data['id_transaksi'],
                'tanggal_transaksi' => $data['tanggal_transaksi'] ?: null,
                'no_register' => $data['no_register'] ?? '',
                'status_frs' => 'Penerimaan',
            ];

            // Simpan data frs
            if (!$this->frsModel->insert($frsData)) {
                throw new Exception('Gagal menyimpan data frs: ' . $this->frsModel->errors());
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
            // Mendapatkan ID frs yang baru diinsert
            $id_frs = $this->frsModel->getInsertID();
            // Data penerimaan
            $penerimaanData = [
                'id_frs' => $id_frs,
                'status_penerimaan_frs' => 'Belum Penerimaan',
            ];
            // Simpan data penerimaan
            if (!$this->penerimaan_frs->insert($penerimaanData)) {
                throw new Exception('Gagal menyimpan data penerimaan: ' . $this->penerimaan_frs->errors());
            }
            // Data mutu
            $mutuData = [
                'id_frs' => $id_frs,
            ];
            if (!$this->mutu_frs->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu: ' . $this->mutu_frs->errors());
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
        $id_frs = $this->request->getPost('id_frs');
        if (!$id_frs) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID frs tidak valid.']);
        }
        if ($this->frsModel->delete($id_frs)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data.']);
        }
    }

    // Menampilkan form edit frs
    public function edit($id_frs)
    {
        // Ambil data frs berdasarkan ID
        $frs = $this->frsModel->getfrsWithRelationsProses($id_frs);
        if (!$frs) {
            return redirect()->back()->with('message', ['error' => 'frs tidak ditemukan.']);
        }
        $id_pembacaan_frs = $frs['id_pembacaan_frs'];
        $id_pasien = $frs['id_pasien'];
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Ambil data pemotongan berdasarkan ID
        $pembacaan_frs = $this->pembacaan_frs->find($id_pembacaan_frs);
        $dokter_nama = null;
        $analis_nama = null;
        if ($pembacaan_frs) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pembacaan_frs['id_user_dokter_pembacaan_frs'])) {
                $dokter = $this->usersModel->find($pembacaan_frs['id_user_dokter_pembacaan_frs']);
                $dokter_nama = $dokter ? $dokter['nama_user'] : null;
            }

            if (!empty($pembacaan_frs['id_user_pembacaan'])) {
                $analis = $this->usersModel->find($pembacaan_frs['id_user_pembacaan']);
                $analis_nama = $analis ? $analis['nama_user'] : null;
            }
            // Tambahkan ke array pembacaan
            $pembacaan_frs['dokter_nama'] = $dokter_nama;
            $pembacaan_frs['analis_nama'] = $analis_nama;
        }
        // Data yang dikirim ke view
        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'frs'        => $frs,
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan_frs' => $pembacaan_frs,
            'users'      => $users,
        ];

        return view('frs/edit', $data);
    }

    public function edit_makroskopis($id_frs)
    {
        // Ambil data frs berdasarkan ID
        $frs = $this->frsModel->getfrsWithRelationsProses($id_frs);
        $id_pasien = $frs['id_pasien'];
        // --- Riwayat API ---
        if (!empty($frs['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($frs['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        if (!$frs) {
            return redirect()->back()->with('message', ['error' => 'frs tidak ditemukan.']);
        }
        // Jika field mulai_penerimaan_frs masih kosong dan ada id_penerimaan_frs
        if (!empty($frs['id_penerimaan_frs']) && empty($frs['mulai_penerimaan_frs'])) {
            $this->penerimaan_frs->update($frs['id_penerimaan_frs'], [
                'mulai_penerimaan_frs' => date('Y-m-d H:i:s'),
                'id_user_penerimaan_frs'  => $this->session->get('id_user'),
                'status_penerimaan_frs' => 'Proses Penerimaan',
            ]);
            // Refresh data
            $frs = $this->frsModel->getfrsWithRelationsProses($id_frs);
        }
        // Ambil data penerimaan berdasarkan ID
        $id_penerimaan_frs = $frs['id_penerimaan_frs'];
        // Ambil data penerimaan berdasarkan id_penerimaan_frs
        $penerimaan = $this->penerimaan_frs->find($id_penerimaan_frs);
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Persiapkan data yang akan dikirim ke view
        $data = [
            'frs'        => $frs,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'penerimaan' => $penerimaan,
            'users'      => $users,
            'id_user'    => $this->session->get('id_user'),
            'nama_user'  => $this->session->get('nama_user'),
        ];

        return view('frs/edit_makroskopis', $data);
    }

    // Menampilkan form edit frs mikroskopis
    public function edit_mikroskopis($id_frs)
    {
        // Ambil data frs berdasarkan ID
        $frs = $this->frsModel->getfrsWithRelationsProses($id_frs);
        $id_pasien = $frs['id_pasien'];
        // --- Riwayat API ---
        if (!empty($frs['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($frs['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        if (!$frs) {
            return redirect()->back()->with('message', ['error' => 'frs tidak ditemukan.']);
        }
        // Jika field mulai_pembacaan_frs masih kosong dan ada id_pembacaan_frs
        if (!empty($frs['id_pembacaan_frs']) && empty($frs['mulai_pembacaan_frs'])) {
            $this->pembacaan_frs->update($frs['id_pembacaan_frs'], [
                'mulai_pembacaan_frs' => date('Y-m-d H:i:s'),
                'id_user_pembacaan_frs'  => $this->session->get('id_user'),
                'status_pembacaan_frs' => 'Proses Pembacaan',
            ]);
            // Refresh data
            $frs = $this->frsModel->getfrsWithRelationsProses($id_frs);
        }
        // Ambil data pemotongan dan pembacaan_frs berdasarkan ID
        $id_pembacaan_frs = $frs['id_pembacaan_frs'];
        // Ambil data pembacaan_frs jika tersedia
        $pembacaan_frs = $id_pembacaan_frs ? $this->pembacaan_frs->find($id_pembacaan_frs) : [];
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Persiapkan data yang akan dikirim ke view
        $data = [
            'frs'             => $frs,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan_frs'   => $pembacaan_frs,
            'users'           => $users,
            'id_user'         => session()->get('id_user'),
            'nama_user'       => session()->get('nama_user'),
        ];

        return view('frs/edit_mikroskopis', $data);
    }

    public function edit_penulisan($id_frs)
    {
        // Ambil data frs berdasarkan ID
        $frs = $this->frsModel->getfrsWithRelationsProses($id_frs);
        $id_pasien = $frs['id_pasien'];
        if (!$frs) {
            return redirect()->back()->with('message', ['error' => 'frs tidak ditemukan.']);
        }
        // Jika field mulai_penulisan_frs masih kosong dan ada id_penulisan_frs
        if (!empty($frs['id_penulisan_frs']) && empty($frs['mulai_penulisan_frs'])) {
            $this->penulisan_frs->update($frs['id_penulisan_frs'], [
                'mulai_penulisan_frs' => date('Y-m-d H:i:s'),
                'id_user_penulisan_frs'  => $this->session->get('id_user'),
                'status_penulisan_frs' => 'Proses Penulisan',
            ]);

            // Refresh data
            $frs = $this->frsModel->getfrsWithRelationsProses($id_frs);
        }
        // Inisialisasi array untuk pembacaan dan penulisan frs
        $pembacaan_frs = [];
        $penulisan_frs = [];
        // Ambil data pembacaan frs jika tersedia
        if (!empty($frs['id_pembacaan_frs'])) {
            $pembacaan_frs = $this->pembacaan_frs->find($frs['id_pembacaan_frs']) ?? [];
            // Ambil nama dokter dari pembacaan jika tersedia
            if (!empty($pembacaan_frs['id_user_dokter_pembacaan_frs'])) {
                $dokter = $this->usersModel->find($pembacaan_frs['id_user_dokter_pembacaan_frs']);
                $pembacaan_frs['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
            } else {
                $pembacaan_frs['dokter_nama'] = null;
            }
        }
        // Ambil data penulisan frs jika tersedia
        if (!empty($frs['id_penulisan_frs'])) {
            $penulisan_frs = $this->penulisan_frs->find($frs['id_penulisan_frs']) ?? [];
        }
        // Ambil daftar user dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // --- Riwayat API ---
        if (!empty($frs['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($frs['norm_pasien']);
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
            'frs' => $frs,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan' => $pembacaan_frs,
            'penulisan' => $penulisan_frs,
            'users' => $users,
        ];

        return view('frs/edit_penulisan', $data);
    }

    public function edit_print($id_frs)
    {
        // Ambil data frs berdasarkan ID
        $frs = $this->frsModel->getFrsWithRelationsProses($id_frs);
        $id_pasien = $frs['id_pasien'];
        if (!empty($frs['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($frs['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);

        // Jika field mulai_pemverifikasi_frs masih kosong dan ada id_pemverifikasi_frs
        if (!empty($frs['id_pemverifikasi_frs']) && empty($frs['mulai_pemverifikasi_frs'])) {
            $this->pemverifikasi_frs->update($frs['id_pemverifikasi_frs'], [
                'mulai_pemverifikasi_frs' => date('Y-m-d H:i:s'),
                'id_user_pemverifikasi_frs'  => $this->session->get('id_user'),
                'status_pemverifikasi_frs' => 'Proses Pemverifikasi',
            ]);

            // Refresh data
            $frs = $this->frsModel->getFrsWithRelationsProses($id_frs);
        }

        // Jika field mulai_authorized_frs masih kosong dan ada id_authorized_frs
        if (!empty($frs['id_authorized_frs']) && empty($frs['mulai_authorized_frs'])) {
            $this->authorized_frs->update($frs['id_authorized_frs'], [
                'mulai_authorized_frs' => date('Y-m-d H:i:s'),
                'id_user_authorized_frs'  => $this->session->get('id_user'),
                'status_authorized_frs' => 'Proses Authorized',
            ]);

            // Refresh data
            $frs = $this->frsModel->getFrsWithRelationsProses($id_frs);
        }

        // Jika field mulai_pencetakan_frs masih kosong dan ada id_pencetakan_frs
        if (!empty($frs['id_pencetakan_frs']) && empty($frs['mulai_pencetakan_frs'])) {
            $this->pencetakan_frs->update($frs['id_pencetakan_frs'], [
                'mulai_pencetakan_frs' => date('Y-m-d H:i:s'),
                'id_user_pencetakan_frs'  => $this->session->get('id_user'),
                'status_pencetakan_frs' => 'Proses Pencetakan',
            ]);

            // Refresh data
            $frs = $this->frsModel->getFrsWithRelationsProses($id_frs);
        }

        // Ambil data pembacaan frs jika tersedia
        $pembacaan_frs = [];
        if (!empty($frs['id_pembacaan_frs'])) {
            $pembacaan_frs = $this->pembacaan_frs->find($frs['id_pembacaan_frs']) ?? [];
            if (!empty($pembacaan_frs['id_user_dokter_pembacaan_frs'])) {
                $dokter = $this->usersModel->find($pembacaan_frs['id_user_dokter_pembacaan_frs']);
                $pembacaan_frs['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
            } else {
                $pembacaan_frs['dokter_nama'] = null;
            }
        }

        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'frs' => $frs,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan_frs' => $pembacaan_frs,
        ];
        
        return view('frs/edit_print', $data);
    }

    public function update($id_frs)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');

        $frs = $this->frsModel->getfrsWithRelationsProses($id_frs);
        if (!$frs) {
            return redirect()->back()->with('message', ['error' => 'frs tidak ditemukan.']);
        }
        $id_pembacaan_frs = $frs['id_pembacaan_frs'];

        // Validasi form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_frs' => [
                'rules' => 'required|is_unique[frs.kode_frs,id_frs,' . $id_frs . ']',
                'errors' => [
                    'required' => 'Kode frs harus diisi.',
                    'is_unique' => 'Kode frs sudah terdaftar!',
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
        // Proses update tabel frs
        if ($this->frsModel->update($id_frs, $data)) {
            // Update data pembacaan jika id_user_dokter_pembacaan_frs ada
            if (!empty($data['id_user_dokter_pembacaan_frs'])) {
                $pembacaan = $this->pembacaan_frs->where('id_pembacaan_frs', $id_pembacaan_frs)->first();

                if ($pembacaan) {
                    $this->pembacaan_frs->update($pembacaan['id_pembacaan_frs'], [
                        'id_user_dokter_pembacaan_frs' => $data['id_user_dokter_pembacaan_frs'],
                    ]);
                }
            }
            switch ($page_source) {
                case 'edit_mikroskopis':
                    $id_pembacaan_frs = $this->request->getPost('id_pembacaan_frs');
                    $this->pembacaan_frs->update($id_pembacaan_frs, [
                        'id_user_pembacaan_frs' => $id_user,
                        'status_pembacaan_frs' => 'Selesai Pembacaan',
                        'selesai_pembacaan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect()->to('frs/edit_mikroskopis/' . $id_frs)->with('success', 'Data mikroskopis berhasil diperbarui.');
                case 'edit_penulisan':
                    $id_penulisan_frs = $this->request->getPost('id_penulisan_frs');
                    $this->penulisan_frs->update($id_penulisan_frs, [
                        'id_user_penulisan_frs' => $id_user,
                        'status_penulisan_frs' => 'Selesai Penulisan',
                        'selesai_penulisan_frs' => date('Y-m-d H:i:s'),
                    ]);
                    // Ambil data dari form
                    $lokasi_spesimen = $this->request->getPost('lokasi_spesimen');
                    $diagnosa_klinik = $this->request->getPost('diagnosa_klinik');
                    $makroskopis_frs = $this->request->getPost('makroskopis_frs');
                    $mikroskopis_frs = $this->request->getPost('mikroskopis_frs');
                    $tindakan_spesimen = $this->request->getPost('tindakan_spesimen');
                    $hasil_frs = $this->request->getPost('hasil_frs');
                    // Simpan data lokasi, diagnosa, makroskopis, mikroskopis, hasil terlebih dahulu
                    $this->frsModel->update($id_frs, [
                        'lokasi_spesimen' => $lokasi_spesimen,
                        'diagnosa_klinik' => $diagnosa_klinik,
                        'makroskopis_frs' => $makroskopis_frs,
                        'mikroskopis_frs' => $mikroskopis_frs,
                        'hasil_frs' => $hasil_frs,
                    ]);
                    // Setelah semua data tersimpan, buat data print_frs
                    $print_frs = '
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
                                        <b>' . htmlspecialchars($lokasi_spesimen) . '<br></b>
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
                                    <font size="5" face="verdana"><b>' . htmlspecialchars($diagnosa_klinik) . '<br></b></font>
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
                    <font size="5" face="verdana"><b>LAPORAN PEMERIKSAAN:<br></b></font>
                    <div>
                        <font size="5" face="verdana"><b> MAKROSKOPIK :</b></font>
                    </div>
                    <div>
                        <font size="5" face="verdana">' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $makroskopis_frs))) . '</font>
                    </div>
                    <br>
                    <div>
                        <font size="5" face="verdana"><b>MIKROSKOPIK :</b><br></font>
                    </div>
                    <div>
                        <font size="5" face="verdana">' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $mikroskopis_frs))) . '</font>
                    </div>
                    <br>
                    <div>
                        <font size="5" face="verdana"><b>KESIMPULAN :</b> ' . htmlspecialchars($lokasi_spesimen) . ', ' . htmlspecialchars($tindakan_spesimen) . ':</b></font>
                    </div>
                    <div>
                        <font size="5" face="verdana"><b>' . strtoupper(htmlspecialchars(str_replace(['<p>', '</p>'], '', $hasil_frs))) . '</b></font>
                    </div>
                    <br>';
                    // Simpan print_frs setelah semua data yang dibutuhkan telah ada
                    $this->frsModel->update($id_frs, [
                        'print_frs' => $print_frs,
                    ]);
                    return redirect()->to('frs/edit_penulisan/' . $id_frs)->with('success', 'Data penulisan berhasil diperbarui.');
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
        $id_frs = $this->request->getPost('id_frs');
        // Inisialisasi model
        $frsModel = new frsModel();

        // Mengambil data dari form
        $penerima_frs = $this->request->getPost('penerima_frs');

        // Data yang akan diupdate
        $data = [
            'penerima_frs' => $penerima_frs,
            'tanggal_penerima' => date('Y-m-d H:i:s'),
        ];

        // Update data penerima_frs berdasarkan id_frs
        $frsModel->updatePenerima($id_frs, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('frs/index_buku_penerima')->with('success', 'Penerima berhasil disimpan.');
    }

    public function update_print($id_frs)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');
        // Mendapatkan id_frs dari POST
        if (!$id_frs) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID frs tidak ditemukan.');
        }
        // Ambil semua data POST
        $data = $this->request->getPost();

        // Simpan nilai redirect lalu hapus dari array $data (biar tidak ikut update frs)
        $redirect = $data['redirect'] ?? null;
        unset($data['redirect']);

        // Jika masih ada sisa field lain selain redirect → update ke frs
        if (!empty($data)) {
            $this->frsModel->update($id_frs, $data);
        }

        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }
        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'edit_makroskopis' && isset($_POST['id_penerimaan_frs'])) {
            $id_penerimaan_frs = $this->request->getPost('id_penerimaan_frs');
            $this->penerimaan_frs->update($id_penerimaan_frs, [
                'id_user_penerimaan_frs' => $id_user,
                'status_penerimaan_frs' => 'Selesai Penerimaan',
                'selesai_penerimaan_frs' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('penerimaan_frs/index')->with('success', 'Data berhasil disimpan.');
        }
        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'index_pemverifikasi_frs' && isset($_POST['id_pemverifikasi_frs'])) {
            $id_pemverifikasi_frs = $this->request->getPost('id_pemverifikasi_frs');
            $this->pemverifikasi_frs->update($id_pemverifikasi_frs, [
                'id_user_pemverifikasi_frs' => $id_user,
                'status_pemverifikasi_frs' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi_frs' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi_frs/index')->with('success', 'Data berhasil diverifikasi.');
        }
        
        if ($redirect === 'index_authorized_frs' && isset($data['id_authorized_frs'])) {
            $id_authorized_frs = $data['id_authorized_frs'];
            $selesaiAuthorized = date('Y-m-d H:i:s'); // gunakan untuk diambil & responsetime
        
            // --- UPDATE AUTHORIZED frs ---
            $updateData = [
                'id_user_authorized_frs'        => $id_user,
                'id_user_dokter_authorized_frs' => $id_user,
                'status_authorized_frs'         => 'Selesai Authorized',
                'selesai_authorized_frs'        => $selesaiAuthorized,
            ];
        
            $update = $this->authorized_frs->update($id_authorized_frs, $updateData);
        
            if (! $update) {
                log_message('error', '[AUTHORIZED frs] Update gagal untuk ID: ' . $id_authorized_frs 
                    . ' | Errors: ' . json_encode($this->authorized_frs->errors()));
            } else {
                log_message('debug', '[AUTHORIZED frs] Update BERHASIL untuk ID: ' . $id_authorized_frs);
            }
        
            // Ambil data hasil terbaru frs setelah update
            $frsTerbaru = $this->frsModel->find($id_frs);
        
            // --- HITUNG RESPONSETIME ---
            $responsetime = null;
            if (!empty($data['periksa'])) {
                $start = new \DateTime($data['periksa']);
                $end   = new \DateTime($selesaiAuthorized);
                $diff  = $start->diff($end);
                $responsetime = sprintf(
                    "%d hari %d jam %d menit %d detik",
                    $diff->days,
                    $diff->h,
                    $diff->i,
                    $diff->s
                );
            }
        
            // --- TENTUKAN ID DOKTER PA ---
            $iddokterpa = null;
            if (!empty($data['dokterpa'])) {
                if ($data['dokterpa'] === "dr. Vinna Chrisdianti, Sp.PA") {
                    $iddokterpa = 1179;
                } elseif ($data['dokterpa'] === "dr. Ayu Tyasmara Pratiwi, Sp.PA") {
                    $iddokterpa = 328;
                }
            }
        
            // --- PERSIAPAN PAYLOAD ---
            $payload = [
                'idtransaksi'      => $data['idtransaksi'] ?? null,
                'tanggal'          => $data['tanggal'] ?? null,
                'register'         => $data['register'] ?? null,
                'pemeriksaan'      => $data['pemeriksaan'] ?? null,
                'idpasien'         => $data['idpasien'] ?? null,
                'norm'             => $data['norm'] ?? null,
                'nama'             => $data['nama'] ?? null,
                'noregister'       => $data['noregister'] ?? null,
                'datang'           => $data['datang'] ?? null,
                'periksa'          => $data['periksa'] ?? null,
                'selesai'          => $data['selesai'] ?? null,
                'diambil'          => $selesaiAuthorized,
                'iddokterpa'       => $iddokterpa,
                'dokterpa'         => $data['dokterpa'] ?? null,
                'statuslokasi'     => $data['statuslokasi'] ?? null,
                'diagnosaklinik'   => $data['diagnosaklinik'] ?? null,
                'diagnosapatologi' => $data['diagnosapatologi'] ?? null,
                'mutusediaan'      => $data['mutusediaan'] ?? null,
                'responsetime'     => $responsetime,
                'hasil'            => $frsTerbaru['print_frs'] ?? null,
                'status'           => !empty($data['idtransaksi']) ? ($data['status'] ?? 'Belum Terkirim') : 'Belum Terdaftar',
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
        
                // simpan ke flashdata agar bisa dicek di halaman redirect
                session()->setFlashdata('simrs_payload', json_encode($payload));
                session()->setFlashdata('simrs_response', $responseBody);
        
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                log_message('error', '[PENGIRIMAN SIMRS] Gagal kirim: ' . $errorMessage);
        
                session()->setFlashdata('simrs_error', $errorMessage);
            }
        
            return redirect()->to('authorized_frs/index')
                ->with('success', session()->getFlashdata('success') ?? 'Data berhasil diauthorized.');
        }

        if ($redirect === 'index_pencetakan_frs' && isset($_POST['id_pencetakan_frs'])) {
            $id_pencetakan_frs = $this->request->getPost('id_pencetakan_frs');
            $this->pencetakan_frs->update($id_pencetakan_frs, [
                'id_user_pencetakan_frs' => $id_user,
                'status_pencetakan_frs' => 'Selesai Pencetakan',
                'selesai_pencetakan_frs' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan_frs/index')->with('success', 'Data berhasil simpan.');
        }
        // Jika redirect tidak sesuai dengan yang diharapkan
        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
    }

    public function update_status()
    {
        // Ambil data dari POST
        $id_frs = $this->request->getPost('id_frs');
        $status_frs = $this->request->getPost('status_frs');
        // Data yang akan diupdate
        $data = [
            'status_frs' => $status_frs,
        ];
        // Gunakan model yang sudah diinisialisasi di konstruktor
        $this->frsModel->update($id_frs, $data);
        // Redirect dengan pesan sukses
        return redirect()->to('frs/index')->with('success', 'Status FRS berhasil disimpan.');
    }

    public function update_jumlah_slide()
    {
        if ($this->request->isAJAX()) {
            $id_frs = $this->request->getPost('id_frs');
            $jumlah_slide = $this->request->getPost('jumlah_slide');

            if ($id_frs && $jumlah_slide !== null) {
                $this->frsModel->update($id_frs, ['jumlah_slide' => $jumlah_slide]);
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Jumlah slide berhasil disimpan.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak lengkap.'
                ]);
            }
        }
    }

    public function uploadFotoMakroskopis($id_frs)
    {
        date_default_timezone_set('Asia/Jakarta');
        $frsModel = new frsModel();

        // Ambil data frs untuk mendapatkan nama file lama
        $frs = $frsModel->find($id_frs);

        if (!$frs) {
            return redirect()->back()->with('error', 'Data frs tidak ditemukan.');
        }

        // Ambil kode_frs dan ekstrak nomor dari format "H.nomor/25"
        $kode_frs = $frs['kode_frs'];
        preg_match('/FRS\.(\d+)\/\d+/', $kode_frs, $matches);
        $kode_frs = isset($matches[1]) ? $matches[1] : '000';

        // Validasi input file
        $validation = \Config\Services::validation();
        $validation->setRules([
            'foto_makroskopis_frs' => [
                'rules' => 'uploaded[foto_makroskopis_frs]|ext_in[foto_makroskopis_frs,jpg,jpeg,png]',
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
        $file = $this->request->getFile('foto_makroskopis_frs');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru berdasarkan waktu
            $newFileName = $kode_frs . date('dmY') . '.' . $file->getExtension();

            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/frs/makroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Membuat folder jika belum ada
            }

            // Hapus file lama jika ada
            if (!empty($frs['foto_makroskopis_frs'])) {
                $oldFilePath = $uploadPath . $frs['foto_makroskopis_frs'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Pindahkan file baru ke folder tujuan dengan nama baru
            if ($file->move($uploadPath, $newFileName)) {
                // Update nama file baru di database
                $frsModel->update($id_frs, ['foto_makroskopis_frs' => $newFileName]);

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

    public function uploadFotoMikroskopis($id_frs)
    {
        date_default_timezone_set('Asia/Jakarta');
        $frsModel = new frsModel();

        // Ambil data frs untuk mendapatkan nama file lama
        $frs = $frsModel->find($id_frs);

        // Validasi input file
        $validation = \Config\Services::validation();
        $validation->setRules([
            'foto_mikroskopis_frs' => [
                'rules' => 'uploaded[foto_mikroskopis_frs]|ext_in[foto_mikroskopis_frs,jpg,jpeg,png]|max_size[foto_mikroskopis_frs,5000]', // 5MB max size
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
        $file = $this->request->getFile('foto_mikroskopis_frs');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru berdasarkan waktu
            $newFileName = date('HisdmY') . '.' . $file->getExtension();

            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/frs/mikroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Membuat folder jika belum ada
            }

            // Hapus file lama jika ada
            if (!empty($frs['foto_mikroskopis_frs'])) {
                $oldFilePath = $uploadPath . $frs['foto_mikroskopis_frs'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Pindahkan file baru ke folder tujuan dengan nama baru
            if ($file->move($uploadPath, $newFileName)) {
                // Update nama file baru di database
                $frsModel->update($id_frs, ['foto_mikroskopis_frs' => $newFileName]);

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
        $frsData = $this->frsModel->getfrsWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'frsData' => $frsData,
        ];
        
        return view('frs//laporan/laporan_pemeriksaan', $data);
    }

    public function laporan_kerja()
    {
        $frsData = $this->frsModel->getfrsWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'frsData' => $frsData,
        ];

        return view('frs/laporan/laporan_kerja', $data);
    }

    public function laporan_oprasional()
    {
        $frsData = $this->frsModel->getfrsWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'frsData' => $frsData,
        ];

        return view('frs/laporan/laporan_oprasional', $data);
    }

    public function filter()
    {
        $filterField = $this->request->getGet('filterInput');
        $filterValue = $this->request->getGet('filterValue');
        $startDate   = $this->request->getGet('filterDate');
        $endDate     = $this->request->getGet('filterDate2');

        $filteredData = $this->frsModel->filterfrsWithRelations(
            $filterField ?: null,
            $filterValue ?: null,
            $startDate,
            $endDate
        );

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'frsData'       => $filteredData,
        ];

        return view('frs/laporan/laporan_pemeriksaan', $data);
    }
}
