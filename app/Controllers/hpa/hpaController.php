<?php

namespace App\Controllers\Hpa;

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
use App\Models\Hpa\Proses\Penerimaan_hpa;
use App\Models\Hpa\Proses\Pemotongan_hpa;
use App\Models\Hpa\Proses\pembacaan_hpa;
use App\Models\Hpa\Proses\Penulisan_hpa;
use App\Models\Hpa\Proses\Pemverifikasi_hpa;
use App\Models\Hpa\Proses\Authorized_hpa;
use App\Models\Hpa\Proses\Pencetakan_hpa;
use App\Models\Hpa\Mutu_hpa;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;


class HpaController extends BaseController
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
    protected $penerimaan_hpa;
    protected $pemotongan_hpa;
    protected $pembacaan_hpa;
    protected $penulisan_hpa;
    protected $pemverifikasi_hpa;
    protected $authorized_hpa;
    protected $pencetakan_hpa;
    protected $mutu_hpa;
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
        $this->penerimaan_hpa = new Penerimaan_hpa();
        $this->pemotongan_hpa = new Pemotongan_hpa();
        $this->pembacaan_hpa = new pembacaan_hpa();
        $this->penulisan_hpa = new Penulisan_hpa();
        $this->pemverifikasi_hpa = new Pemverifikasi_hpa();
        $this->authorized_hpa = new Authorized_hpa();
        $this->pencetakan_hpa = new Pencetakan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $hpaData = $this->hpaModel->gethpaWithPatient();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'hpaData' => $hpaData
        ];

        return view('Hpa/index', $data);
    }

    public function index_buku_penerima()
    {
        // Mengambil data HPA menggunakan properti yang sudah ada
        $hpaData = $this->hpaModel->gethpaWithPatientDESC() ?? [];

        // Kirimkan data ke view
        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpaData' => $hpaData,
        ];

        return view('hpa/index_buku_penerima', $data);
    }

    public function register()
    {
        // --- Ambil kode HPA terakhir ---
        $lasthpa      = $this->hpaModel->getLastKodeHPA();
        $currentYear  = date('y');
        $nextNumber   = 1;

        if ($lasthpa) {
            $lastKode  = $lasthpa['kode_hpa'];
            $lastParts = explode('/', $lastKode);
            $lastYear  = $lastParts[1] ?? '';

            if ($lastYear == $currentYear) {
                $lastNumber = (int) explode('.', $lastParts[0])[1];
                $nextNumber = $lastNumber + 1;
            }
        }

        $kodehpa = sprintf('H.%02d/%s', $nextNumber, $currentYear);

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
            'kode_hpa'      => $kodehpa,
            'patient'       => $patient,
            'riwayat_api'   => $riwayat_api,
            'riwayat_hpa'   => $riwayat_hpa,
            'riwayat_frs'   => $riwayat_frs,
            'riwayat_srs'   => $riwayat_srs,
            'riwayat_ihc'   => $riwayat_ihc,
        ];

        return view('Hpa/Register', $data);
    }

    public function insert()
    {
        try {
            // Set rules untuk validasi
            $this->validation->setRules([
                'kode_hpa' => [
                    'rules' => 'required|is_unique[hpa.kode_hpa]',
                    'errors' => [
                        'required' => 'Kode Hpa harus diisi.',
                        'is_unique' => 'Kode Hpa sudah terdaftar!',
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
            // Data yang akan disimpan

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

            $hpaData = [
                'kode_hpa' => $data['kode_hpa'],
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
                'status_hpa' => 'Penerimaan'
            ];

            // Simpan data HPA
            if (!$this->hpaModel->insert($hpaData)) {
                throw new Exception('Gagal menyimpan data HPA: ' . $this->hpaModel->errors());
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
            // Mendapatkan ID HPA yang baru diinsert
            $id_hpa = $this->hpaModel->getInsertID();
            // Data penerimaan
            $penerimaanData = [
                'id_hpa' => $id_hpa,
                'status_penerimaan_hpa' => 'Belum Penerimaan',
            ];
            // Simpan data penerimaan
            if (!$this->penerimaan_hpa->insert($penerimaanData)) {
                throw new Exception('Gagal menyimpan data penerimaan: ' . $this->penerimaan_hpa->errors());
            }
            // Data mutu
            $mutuData = [
                'id_hpa' => $id_hpa,
            ];
            if (!$this->mutu_hpa->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu: ' . $this->mutu_hpa->errors());
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
        $id_hpa = $this->request->getPost('id_hpa');
        if (!$id_hpa) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID HPA tidak valid.']);
        }
        if ($this->hpaModel->delete($id_hpa)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data.']);
        }
    }

    public function update_buku_penerima()
    {
        // Set zona waktu Indonesia/Jakarta (opsional jika sudah diatur dalam konfigurasi)
        date_default_timezone_set('Asia/Jakarta');
        // Mendapatkan data dari request
        $id_hpa = $this->request->getPost('id_hpa');
        // Inisialisasi model
        $hpaModel = new HpaModel();
        // Mengambil data dari form
        $penerima_hpa = $this->request->getPost('penerima_hpa');
        // Data yang akan diupdate
        $data = [
            'penerima_hpa' => $penerima_hpa,
            'tanggal_penerima' => date('Y-m-d H:i:s'),
        ];
        // Update data penerima_hpa berdasarkan id_hpa
        $hpaModel->update($id_hpa, $data);
        // Redirect setelah berhasil mengupdate data
        return redirect()->to('hpa/index_buku_penerima')->with('success', 'Penerima berhasil disimpan.');
    }

    // Menampilkan form edit HPA
    public function edit($id_hpa)
    {
        // Ambil data HPA berdasarkan ID
        $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        $id_mutu_hpa = $hpa['id_mutu_hpa'];
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }
        $id_pemotongan_hpa = $hpa['id_pemotongan_hpa'];
        $id_penerimaan_hpa = $hpa['id_penerimaan_hpa'];
        $id_pembacaan_hpa = $hpa['id_pembacaan_hpa'];
        $id_pemnulisan_hpa = $hpa['id_penulisan_hpa'];
        $id_pasien = $hpa['id_pasien'];
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Ambil data pemotongan berdasarkan ID
        $pemotongan_hpa = $this->pemotongan_hpa->find($id_pemotongan_hpa);
        $penerima_hpa = $this->penerimaan_hpa->find($id_penerimaan_hpa);
        $pembacaan_hpa = $this->pembacaan_hpa->find($id_pembacaan_hpa);
        $penulisan_hpa = $this->penulisan_hpa->find($id_pemnulisan_hpa);
        $dokter_nama = null;
        $analis_nama = null;
        if ($pemotongan_hpa) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pemotongan_hpa['id_user_dokter_pemotongan_hpa'])) {
                $dokter = $this->usersModel->find($pemotongan_hpa['id_user_dokter_pemotongan_hpa']);
                $dokter_nama = $dokter ? $dokter['nama_user'] : null;
            }

            if (!empty($pemotongan_hpa['id_user_pemotongan_hpa'])) {
                $analis = $this->usersModel->find($pemotongan_hpa['id_user_pemotongan_hpa']);
                $analis_nama = $analis ? $analis['nama_user'] : null;
            }
            // Tambahkan ke array pemotongan
            $pemotongan_hpa['dokter_nama'] = $dokter_nama;
            $pemotongan_hpa['analis_nama'] = $analis_nama;
        }
        if ($pembacaan_hpa) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pembacaan_hpa['id_user_dokter_pembacaan_hpa'])) {
                $dokter = $this->usersModel->find($pembacaan_hpa['id_user_dokter_pembacaan_hpa']);
                $dokter_nama = $dokter ? $dokter['nama_user'] : null;
            }

            if (!empty($pembacaan_hpa['id_user_pembacaan'])) {
                $analis = $this->usersModel->find($pembacaan_hpa['id_user_pembacaan']);
                $analis_nama = $analis ? $analis['nama_user'] : null;
            }
            // Tambahkan ke array pembacaan
            $pembacaan_hpa['dokter_nama'] = $dokter_nama;
            $pembacaan_hpa['analis_nama'] = $analis_nama;
        }
        // Data yang dikirim ke view
        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpa'        => $hpa,
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pemotongan_hpa' => $pemotongan_hpa,
            'penerimaan_hpa' => $penerima_hpa,
            'pembacaan_hpa' => $pembacaan_hpa,
            'penulisan_hpa' => $penulisan_hpa,
            'users'      => $users,
        ];

        return view('hpa/edit', $data);
    }

    public function edit_makroskopis($id_hpa)
    {
        // Ambil data HPA berdasarkan ID
        $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        $id_pasien = $hpa['id_pasien'];
        // --- Riwayat API ---
        if (!empty($hpa['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($hpa['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }
        // Jika field mulai_pemotongan_hpa masih kosong dan ada id_pemotongan_hpa
        if (!empty($hpa['id_pemotongan_hpa']) && empty($hpa['mulai_pemotongan_hpa'])) {
            $this->pemotongan_hpa->update($hpa['id_pemotongan_hpa'], [
                'mulai_pemotongan_hpa' => date('Y-m-d H:i:s'),
                'id_user_pemotongan_hpa'  => $this->session->get('id_user'),
                'status_pemotongan_hpa' => 'Proses Pemotongan',
            ]);
            // Refresh data
            $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        }
        $id_pemotongan_hpa = $hpa['id_pemotongan_hpa'];
        // Ambil data pemotongan berdasarkan id_pemotongan_hpa
        $pemotongan = $this->pemotongan_hpa->find($id_pemotongan_hpa);
        if (!empty($pemotongan)) {
            // Ambil nama dokter dan analis jika ID tersedia
            $pemotongan['dokter_nama'] = !empty($pemotongan['id_user_dokter_pemotongan_hpa'])
                ? ($this->usersModel->find($pemotongan['id_user_dokter_pemotongan_hpa'])['nama_user'] ?? null)
                : null;

            $pemotongan['analis_nama'] = !empty($pemotongan['id_user_pemotongan_hpa'])
                ? ($this->usersModel->find($pemotongan['id_user_pemotongan_hpa'])['nama_user'] ?? null)
                : null;
        } else {
            // Jika data pemotongan tidak ditemukan, set sebagai array kosong untuk mencegah error
            $pemotongan = [];
        }
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Persiapkan data yang akan dikirim ke view

        $data = [
            'hpa'        => $hpa,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pemotongan' => $pemotongan,
            'users'      => $users,
            'id_user'    => $this->session->get('id_user'),
            'nama_user'  => $this->session->get('nama_user'),
        ];

        return view('hpa/edit_makroskopis', $data);
    }

    // Menampilkan form edit HPA mikroskopis
    public function edit_mikroskopis($id_hpa)
    {
        // Ambil data HPA berdasarkan ID
        $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }
        // Jika field mulai_pembacaan_hpa masih kosong dan ada id_pembacaan_hpa
        if (!empty($hpa['id_pembacaan_hpa']) && empty($hpa['mulai_pembacaan_hpa'])) {
            $this->pembacaan_hpa->update($hpa['id_pembacaan_hpa'], [
                'mulai_pembacaan_hpa' => date('Y-m-d H:i:s'),
                'id_user_pembacaan_hpa'  => $this->session->get('id_user'),
                'status_pembacaan_hpa' => 'Proses Pembacaan',
            ]);
            // Refresh data
            $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        }
        // Ambil data pemotongan dan pembacaan_hpa berdasarkan ID
        $id_pemotongan_hpa = $hpa['id_pemotongan_hpa'];
        $id_pembacaan_hpa = $hpa['id_pembacaan_hpa'];
        $id_mutu_hpa = $hpa['id_mutu_hpa'];
        $id_pasien = $hpa['id_pasien'];
        if (!empty($hpa['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($hpa['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        // Inisialisasi dokter dan analis
        $dokter_nama = null;
        $analis_nama = null;
        $pemotongan_hpa = [];
        if ($id_pemotongan_hpa) {
            // Ambil data pemotongan_hpa dari model
            $pemotongan_hpa = $this->pemotongan_hpa->find($id_pemotongan_hpa) ?? [];
            if (!empty($pemotongan_hpa)) {
                // Ambil nama dokter dan analis jika ID tersedia
                $dokter_nama = !empty($pemotongan_hpa['id_user_dokter_pemotongan_hpa'])
                    ? ($this->usersModel->find($pemotongan_hpa['id_user_dokter_pemotongan_hpa'])['nama_user'] ?? null)
                    : null;
                $analis_nama = !empty($pemotongan_hpa['id_user_pemotongan_hpa'])
                    ? ($this->usersModel->find($pemotongan_hpa['id_user_pemotongan_hpa'])['nama_user'] ?? null)
                    : null;
                // Tambahkan informasi dokter dan analis ke dalam array pemotongan
                $pemotongan_hpa['dokter_nama'] = $dokter_nama;
                $pemotongan_hpa['analis_nama'] = $analis_nama;
            }
        }
        // Ambil data pembacaan_hpa jika tersedia
        $pembacaan_hpa = $id_pembacaan_hpa ? $this->pembacaan_hpa->find($id_pembacaan_hpa) : [];
        // Ambil data mutu jika tersedia
        $mutu_hpa = $id_mutu_hpa ? $this->mutu_hpa->find($id_mutu_hpa) : null;
        // Pastikan mutu tidak kosong sebelum mengambil indikator
        $mutu_hpa = [
            'id_mutu_hpa' => $id_mutu_hpa ?? null,
            'indikator_4' => $mutu_hpa['indikator_4'] ?? "0",
            'indikator_5' => $mutu_hpa['indikator_5'] ?? "0",
            'indikator_6' => $mutu_hpa['indikator_6'] ?? "0",
            'indikator_7' => $mutu_hpa['indikator_7'] ?? "0",
            'indikator_8' => $mutu_hpa['indikator_8'] ?? "0",
            'indikator_9' => $mutu_hpa['indikator_9'] ?? "0",
            'indikator_10' => $mutu_hpa['indikator_10'] ?? "0",
            'total_nilai_mutu_hpa' => $mutu_hpa['total_nilai_mutu_hpa'] ?? "0"
        ];
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Persiapkan data yang akan dikirim ke view
        $data = [
            'id_user'  => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpa'             => $hpa,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pemotongan_hpa'  => $pemotongan_hpa,
            'pembacaan_hpa'   => $pembacaan_hpa,
            'mutu_hpa'        => $mutu_hpa,
            'users'           => $users,
        ];

        return view('hpa/edit_mikroskopis', $data);
    }

    public function edit_penulisan($id_hpa)
    {
        // Ambil data HPA berdasarkan ID
        $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        $id_pasien = $hpa['id_pasien'];
        if (!empty($hpa['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($hpa['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);

        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }
        // Jika field mulai_penulisan_hpa masih kosong dan ada id_penulisan_hpa
        if (!empty($hpa['id_penulisan_hpa']) && empty($hpa['mulai_penulisan_hpa'])) {
            $this->penulisan_hpa->update($hpa['id_penulisan_hpa'], [
                'mulai_penulisan_hpa' => date('Y-m-d H:i:s'),
                'id_user_penulisan_hpa'  => $this->session->get('id_user'),
                'status_penulisan_hpa' => 'Proses Penulisan',
            ]);

            // Refresh data
            $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        }
        // Inisialisasi array untuk pembacaan dan penulisan HPA
        $pembacaan_hpa = [];
        $penulisan_hpa = [];
        // Ambil data pembacaan HPA jika tersedia
        if (!empty($hpa['id_pembacaan_hpa'])) {
            $pembacaan_hpa = $this->pembacaan_hpa->find($hpa['id_pembacaan_hpa']) ?? [];
            // Ambil nama dokter dari pembacaan jika tersedia
            if (!empty($pembacaan_hpa['id_user_dokter_pembacaan_hpa'])) {
                $dokter = $this->usersModel->find($pembacaan_hpa['id_user_dokter_pembacaan_hpa']);
                $pembacaan_hpa['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
            } else {
                $pembacaan_hpa['dokter_nama'] = null;
            }
        }
        // Ambil data penulisan HPA jika tersedia
        if (!empty($hpa['id_penulisan_hpa'])) {
            $penulisan_hpa = $this->penulisan_hpa->find($hpa['id_penulisan_hpa']) ?? [];
        }

        // Ambil daftar user dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Data yang akan dikirim ke view
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'hpa' => $hpa,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan' => $pembacaan_hpa,
            'penulisan' => $penulisan_hpa,
            'users' => $users,
        ];

        return view('hpa/edit_penulisan', $data);
    }


    public function edit_print($id_hpa)
    {
        // Ambil data hpa berdasarkan ID
        $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        $id_pasien = $hpa['id_pasien'];
        if (!empty($hpa['norm_pasien'])) {
            $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($hpa['norm_pasien']);
            if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                $riwayat_api = $riwayat_api_response['data'];
            }
        }
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);

        // Jika field mulai_pemverifikasi_hpa masih kosong dan ada id_pemverifikasi_hpa
        if (!empty($hpa['id_pemverifikasi_hpa']) && empty($hpa['mulai_pemverifikasi_hpa'])) {
            $this->pemverifikasi_hpa->update($hpa['id_pemverifikasi_hpa'], [
                'mulai_pemverifikasi_hpa' => date('Y-m-d H:i:s'),
                'id_user_pemverifikasi_hpa'  => $this->session->get('id_user'),
                'status_pemverifikasi_hpa' => 'Proses Pemverifikasi',
            ]);

            // Refresh data
            $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        }

        // Jika field mulai_authorized_hpa masih kosong dan ada id_authorized_hpa
        if (!empty($hpa['id_authorized_hpa']) && empty($hpa['mulai_authorized_hpa'])) {
            $this->authorized_hpa->update($hpa['id_authorized_hpa'], [
                'mulai_authorized_hpa' => date('Y-m-d H:i:s'),
                'id_user_authorized_hpa'  => $this->session->get('id_user'),
                'status_authorized_hpa' => 'Proses Authorized',
            ]);

            // Refresh data
            $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        }

        // Jika field mulai_pencetakan_hpa masih kosong dan ada id_pencetakan_hpa
        if (!empty($hpa['id_pencetakan_hpa']) && empty($hpa['mulai_pencetakan_hpa'])) {
            $this->pencetakan_hpa->update($hpa['id_pencetakan_hpa'], [
                'mulai_pencetakan_hpa' => date('Y-m-d H:i:s'),
                'id_user_pencetakan_hpa'  => $this->session->get('id_user'),
                'status_pencetakan_hpa' => 'Proses Pencetakan',
            ]);

            // Refresh data
            $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        }

        // Ambil data pembacaan HPA jika tersedia
        $pembacaan_hpa = [];
        if (!empty($hpa['id_pembacaan_hpa'])) {
            $pembacaan_hpa = $this->pembacaan_hpa->find($hpa['id_pembacaan_hpa']) ?? [];
            if (!empty($pembacaan_hpa['id_user_dokter_pembacaan_hpa'])) {
                $dokter = $this->usersModel->find($pembacaan_hpa['id_user_dokter_pembacaan_hpa']);
                $pembacaan_hpa['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
            } else {
                $pembacaan_hpa['dokter_nama'] = null;
            }
        }

        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'hpa' => $hpa,
            'riwayat_api'   => $riwayat_api ?? [],
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan_hpa' => $pembacaan_hpa,
        ];

        return view('hpa/edit_print', $data);
    }

    public function update($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');

        $hpa = $this->hpaModel->getHpaWithRelationsProses($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }
        $id_pemotongan_hpa = $hpa['id_pemotongan_hpa'];
        $id_pembacaan_hpa = $hpa['id_pembacaan_hpa'];

        // Validasi form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_hpa' => [
                'rules' => 'required|is_unique[hpa.kode_hpa,id_hpa,' . $id_hpa . ']',
                'errors' => [
                    'required' => 'Kode Hpa harus diisi.',
                    'is_unique' => 'Kode Hpa sudah terdaftar!',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            session()->setFlashdata('errors', $validation->getErrors()); // Simpan error ke session
            return redirect()->back()->withInput(); // Redirect kembali ke halaman form
        }

        // Mengambil data dari form
        $data = $this->request->getPost();

        $id_user_pemotongan_hpa = !empty($data['id_user_pemotongan_hpa']) ? (int) $data['id_user_pemotongan_hpa'] : null;
        $id_user_dokter_pemotongan_hpa = !empty($data['id_user_dokter_pemotongan_hpa']) ? (int) $data['id_user_dokter_pemotongan_hpa'] : null;
        $id_user_pembacaan_hpa = !empty($data['id_user_pembacaan_hpa']) ? (int) $data['id_user_pembacaan_hpa'] : null;
        $id_user_dokter_pembacaan_hpa = !empty($data['id_user_dokter_pembacaan_hpa']) ? (int) $data['id_user_dokter_pembacaan_hpa'] : null;
        $page_source = $this->request->getPost('page_source');
        // Proses update tabel mutu_hpa (jalankan hanya jika ada ID)
        $id_mutu_hpa = $this->request->getPost('id_mutu_hpa');
        if (!empty($id_mutu_hpa)) {
            $indikator_1 = (string) ($this->request->getPost('indikator_1') ?? '0');
            $indikator_2 = (string) ($this->request->getPost('indikator_2') ?? '0');
            $indikator_3 = (string) ($this->request->getPost('indikator_3') ?? '0');
            $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
            $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
            $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
            $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
            $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
            $indikator_9 = (string) ($this->request->getPost('indikator_9') ?? '0');
            $indikator_10 = (string) ($this->request->getPost('indikator_10') ?? '0');
            $total_nilai_mutu_hpa = (string) ($this->request->getPost('total_nilai_mutu_hpa') ?? '0');
            $keseluruhan_nilai_mutu =
                (int)$indikator_1 +
                (int)$indikator_2 +
                (int)$indikator_3 +
                (int)$indikator_4 +
                (int)$indikator_5 +
                (int)$indikator_6 +
                (int)$indikator_7 +
                (int)$indikator_8 +
                (int)$indikator_9 +
                (int)$indikator_10;
                
                $this->mutu_hpa->update($id_mutu_hpa, [
                    'indikator_1' => $indikator_1,
                    'indikator_2' => $indikator_2,
                    'indikator_3' => $indikator_3,
                    'indikator_4' => $indikator_4,
                    'indikator_5' => $indikator_5,
                    'indikator_6' => $indikator_6,
                    'indikator_7' => $indikator_7,
                    'indikator_8' => $indikator_8,
                    'indikator_9' => $indikator_9,
                    'indikator_10' => $indikator_10,
                    'total_nilai_mutu_hpa' => $keseluruhan_nilai_mutu,
                ]);
        }
        // Mengubah jika memilih 'lainnya'
        $data['PUG'] = ($this->request->getPost('PUG') === 'lainnya')
            ? $this->request->getPost('pug_custom')
            : $this->request->getPost('PUG');
        $data['PUB'] = ($this->request->getPost('PUB') === 'lainnya')
            ? $this->request->getPost('PUB_custom')
            : $this->request->getPost('PUB');

        // Proses update tabel HPA
        if ($this->hpaModel->update($id_hpa, $data)) {
            // Update tabel pemotongan_hpa
            $pemotongan = $this->pemotongan_hpa->where('id_pemotongan_hpa', $id_pemotongan_hpa)->first();
            if ($pemotongan) {
                $updatePemotongan = [];

                if ($id_user_dokter_pemotongan_hpa !== null) {
                    $updatePemotongan['id_user_dokter_pemotongan_hpa'] = $id_user_dokter_pemotongan_hpa;
                }
                if ($id_user_pemotongan_hpa !== null) {
                    $updatePemotongan['id_user_pemotongan_hpa'] = $id_user_pemotongan_hpa;
                }

                if (!empty($updatePemotongan)) {
                    $this->pemotongan_hpa->update($pemotongan['id_pemotongan_hpa'], $updatePemotongan);
                }
            }

            // Update tabel pembacaan_hpa
            $pembacaan = $this->pembacaan_hpa->where('id_pembacaan_hpa', $id_pembacaan_hpa)->first();
            if ($pembacaan) {
                $updatePembacaan = [];

                if ($id_user_dokter_pembacaan_hpa !== null) {
                    $updatePembacaan['id_user_dokter_pembacaan_hpa'] = $id_user_dokter_pembacaan_hpa;
                }
                if ($id_user_pembacaan_hpa !== null) {
                    $updatePembacaan['id_user_pembacaan_hpa'] = $id_user_pembacaan_hpa;
                }

                if (!empty($updatePembacaan)) {
                    $this->pembacaan_hpa->update($pembacaan['id_pembacaan_hpa'], $updatePembacaan);
                }
            }
            switch ($page_source) {
                case 'edit_makroskopis':
                    $id_pemotongan_hpa = $this->request->getPost('id_pemotongan_hpa');
                    $this->pemotongan_hpa->update($id_pemotongan_hpa, [
                        'id_user_pemotongan_hpa_hpa' => $id_user,
                        'status_pemotongan_hpa' => 'Selesai Pemotongan',
                        'selesai_pemotongan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect()->to('hpa/edit_makroskopis/' . $id_hpa)->with('success', 'Data makroskopis berhasil diperbarui.');
                case 'edit_mikroskopis':
                    $id_pembacaan_hpa = $this->request->getPost('id_pembacaan_hpa');
                    $this->pembacaan_hpa->update($id_pembacaan_hpa, [
                        'id_user_pembacaan_hpa' => $id_user,
                        'status_pembacaan_hpa' => 'Selesai Pembacaan',
                        'selesai_pembacaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    $id_mutu_hpa = $this->request->getPost('id_mutu_hpa');
                    $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                    $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                    $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                    $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                    $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                    $indikator_9 = (string) ($this->request->getPost('indikator_9') ?? '0');
                    $indikator_10 = (string) ($this->request->getPost('indikator_10') ?? '0');
                    $total_nilai_mutu_hpa = (string) ($this->request->getPost('total_nilai_mutu_hpa') ?? '0');
                    $keseluruhan_nilai_mutu = $total_nilai_mutu_hpa + (int)$indikator_4 + (int)$indikator_5 + (int)$indikator_6 + (int)$indikator_7 + (int)$indikator_8 + (int)$indikator_9 + (int)$indikator_10;
                    $this->mutu_hpa->update($id_mutu_hpa, [
                        'indikator_4' => $indikator_4,
                        'indikator_5' => $indikator_5,
                        'indikator_6' => $indikator_6,
                        'indikator_7' => $indikator_7,
                        'indikator_8' => $indikator_8,
                        'indikator_9' => $indikator_9,
                        'indikator_10' => $indikator_10,
                        'total_nilai_mutu_hpa' => $keseluruhan_nilai_mutu,
                    ]);
                    return redirect()->to('hpa/edit_mikroskopis/' . $id_hpa)->with('success', 'Data mikroskopis berhasil diperbarui.');
                case 'edit_penulisan':
                    $id_penulisan_hpa = $this->request->getPost('id_penulisan_hpa');
                    $this->penulisan_hpa->update($id_penulisan_hpa, [
                        'id_user_penulisan_hpa' => $id_user,
                        'status_penulisan_hpa' => 'Selesai Penulisan',
                        'selesai_penulisan_hpa' => date('Y-m-d H:i:s'),
                    ]);

                    // Ambil data dari form
                    $lokasi_spesimen = $this->request->getPost('lokasi_spesimen');
                    $diagnosa_klinik = $this->request->getPost('diagnosa_klinik');
                    $makroskopis_hpa = $this->request->getPost('makroskopis_hpa');
                    $mikroskopis_hpa = $this->request->getPost('mikroskopis_hpa');
                    $tindakan_spesimen = $this->request->getPost('tindakan_spesimen');
                    $hasil_hpa = $this->request->getPost('hasil_hpa');

                    // Simpan data lokasi, diagnosa, makroskopis, mikroskopis, hasil terlebih dahulu
                    $this->hpaModel->update($id_hpa, [
                        'lokasi_spesimen' => $lokasi_spesimen,
                        'diagnosa_klinik' => $diagnosa_klinik,
                        'makroskopis_hpa' => $makroskopis_hpa,
                        'mikroskopis_hpa' => $mikroskopis_hpa,
                        'hasil_hpa' => $hasil_hpa,
                    ]);

                    // Setelah semua data tersimpan, buat data print_hpa
                    $print_hpa = '
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
                        <font size="5" face="verdana">' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $makroskopis_hpa))) . '</font>
                    </div>
                    <br>
                    <div>
                        <font size="5" face="verdana"><b>MIKROSKOPIK :</b><br></font>
                    </div>
                    <div>
                        <font size="5" face="verdana">' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $mikroskopis_hpa))) . '</font>
                    </div>
                    <br>
                    <div>
                        <font size="5" face="verdana"><b>KESIMPULAN :</b> ' . htmlspecialchars($lokasi_spesimen) . ', ' . htmlspecialchars($tindakan_spesimen) . ':</b></font>
                    </div>
                    <div>
                        <font size="5" face="verdana"><b>' . strtoupper(htmlspecialchars(str_replace(['<p>', '</p>'], '', $hasil_hpa))) . '</b></font>
                    </div>
                    <br>
                    <div>
                        <font size="5" face="verdana"><b><br><br></b></font>
                    </div>
                    <div>
                        <font size="3"><i>
                            <font face="verdana">Ket : <br></font>
                        </i></font>
                    </div>
                    <div>
                        <font size="5" face="verdana">
                            <font size="3">
                                <i>Jaringan telah dilakukan fiksasi dengan formalin sehingga terjadi perubahan ukuran makroskopis</i>
                            </font>
                        </font>
                    </div>';
                    // Simpan print_hpa setelah semua data yang dibutuhkan telah ada
                    $this->hpaModel->update($id_hpa, [
                        'print_hpa' => $print_hpa,
                    ]);

                    return redirect()->to('hpa/edit_penulisan/' . $id_hpa)->with('success', 'Data penulisan berhasil diperbarui.');

                default:
                    return redirect()->back()->with('success', 'Data berhasil diperbarui.');
            }
        }
        return redirect()->back()->with('error', 'Gagal memperbarui data.');
    }

    public function update_print($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');

        if (!$id_hpa) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID HPA tidak ditemukan.');
        }

        // Ambil data dari POST dan update HPA lebih dulu
        $data = $this->request->getPost();
        $this->hpaModel->update($id_hpa, $data);

        $redirect = $this->request->getPost('redirect');
        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }

        if ($redirect === 'index_pemverifikasi_hpa' && isset($_POST['id_pemverifikasi_hpa'])) {
            $id_pemverifikasi_hpa = $this->request->getPost('id_pemverifikasi_hpa');
            $this->pemverifikasi_hpa->update($id_pemverifikasi_hpa, [
                'id_user_pemverifikasi_hpa' => $id_user,
                'status_pemverifikasi_hpa' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi_hpa' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi_hpa/index')->with('success', 'Data berhasil diverifikasi.');
        }

        if ($redirect === 'index_authorized_hpa' && isset($data['id_authorized_hpa'])) {
            $id_authorized_hpa = $data['id_authorized_hpa'];
            $selesaiAuthorized = date('Y-m-d H:i:s'); // gunakan untuk diambil & responsetime
        
            // --- UPDATE AUTHORIZED HPA ---
            $updateData = [
                'id_user_authorized_hpa'        => $id_user,
                'id_user_dokter_authorized_hpa' => $id_user,
                'status_authorized_hpa'         => 'Selesai Authorized',
                'selesai_authorized_hpa'        => $selesaiAuthorized,
            ];
        
            $update = $this->authorized_hpa->update($id_authorized_hpa, $updateData);
        
            if (! $update) {
                log_message('error', '[AUTHORIZED HPA] Update gagal untuk ID: ' . $id_authorized_hpa 
                    . ' | Errors: ' . json_encode($this->authorized_hpa->errors()));
            } else {
                log_message('debug', '[AUTHORIZED HPA] Update BERHASIL untuk ID: ' . $id_authorized_hpa);
            }
        
            // Ambil data hasil terbaru HPA setelah update
            $hpaTerbaru = $this->hpaModel->find($id_hpa);
        
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
                'hasil'            => $hpaTerbaru['print_hpa'] ?? null,
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
        
            return redirect()->to('authorized_hpa/index')
                ->with('success', session()->getFlashdata('success') ?? 'Data berhasil diauthorized.');
        }        

        if ($redirect === 'index_pencetakan_hpa' && isset($_POST['id_pencetakan_hpa'])) {
            $id_pencetakan_hpa = $this->request->getPost('id_pencetakan_hpa');
            $this->pencetakan_hpa->update($id_pencetakan_hpa, [
                'id_user_pencetakan_hpa' => $id_user,
                'status_pencetakan_hpa' => 'Selesai Pencetakan',
                'selesai_pencetakan_hpa' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan_hpa/index')->with('success', 'Data berhasil simpan.');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
    }

    public function update_status()
    {
        // Ambil data dari POST
        $id_hpa = $this->request->getPost('id_hpa');
        $status_hpa = $this->request->getPost('status_hpa');
        // Data yang akan diupdate
        $data = [
            'status_hpa' => $status_hpa,
        ];
        // Gunakan model yang sudah diinisialisasi di konstruktor
        $this->hpaModel->update($id_hpa, $data);
        // Redirect dengan pesan sukses
        return redirect()->to('hpa/index')->with('success', 'Status HPA berhasil disimpan.');
    }

    public function update_jumlah_slide()
    {
        if ($this->request->isAJAX()) {
            $id_hpa = $this->request->getPost('id_hpa');
            $jumlah_slide = $this->request->getPost('jumlah_slide');

            if ($id_hpa && $jumlah_slide !== null) {
                $this->hpaModel->update($id_hpa, ['jumlah_slide' => $jumlah_slide]);
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

    public function uploadFotoMakroskopis($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');
        // Ambil data HPA untuk mendapatkan nama file lama
        $hpa = $this->hpaModel->find($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('error', 'Data HPA tidak ditemukan.');
        }
        // Ambil kode_hpa dan ekstrak nomor dari format "H.nomor/25"
        $kode_hpa = $hpa['kode_hpa'];
        preg_match('/H\.(\d+)\/\d+/', $kode_hpa, $matches);
        $kode_hpa = isset($matches[1]) ? $matches[1] : '000';
        // Validasi input file
        $validation = \Config\Services::validation();
        $validation->setRules([
            'foto_makroskopis_hpa' => [
                'rules' => 'uploaded[foto_makroskopis_hpa]|ext_in[foto_makroskopis_hpa,jpg,jpeg,png]',
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
        $file = $this->request->getFile('foto_makroskopis_hpa');
        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru berdasarkan waktu
            $newFileName = $kode_hpa . date('dmY') . '.' . $file->getExtension();
            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/hpa/makroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            // Hapus file lama jika ada
            if (!empty($hpa['foto_makroskopis_hpa'])) {
                $oldFilePath = $uploadPath . $hpa['foto_makroskopis_hpa'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            // Pindahkan file sementara ke folder tujuan
            $tempPath = $file->getTempName();
            $finalPath = $uploadPath . $newFileName;
            // Cek orientasi gambar dan putar jika perlu
            list($width, $height) = getimagesize($tempPath);
            if ($height > $width) {
                // Jika portrait, putar 90 derajat agar landscape
                $image = imagecreatefromstring(file_get_contents($tempPath));
                $rotatedImage = imagerotate($image, -90, 0); // Rotasi 90 derajat ke kiri
                // Simpan gambar sesuai format
                if ($file->getExtension() === 'jpg' || $file->getExtension() === 'jpeg') {
                    imagejpeg($rotatedImage, $finalPath, 90);
                } elseif ($file->getExtension() === 'png') {
                    imagepng($rotatedImage, $finalPath);
                }
                // Hapus dari memori
                imagedestroy($image);
                imagedestroy($rotatedImage);
            } else {
                // Jika sudah landscape, langsung simpan
                move_uploaded_file($tempPath, $finalPath);
            }
            // Update nama file baru di database
            $this->hpaModel->update($id_hpa, ['foto_makroskopis_hpa' => $newFileName]);
            // Berhasil, redirect dengan pesan sukses
            return redirect()->back();
        } else {
            return redirect()->back()->with('error', 'File tidak valid atau tidak diunggah.');
        }
    }

    public function uploadFotoMikroskopis($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');
        $hpaModel = new HpaModel();

        // Ambil data HPA untuk mendapatkan nama file lama
        $hpa = $hpaModel->find($id_hpa);

        // Validasi input file
        $validation = \Config\Services::validation();
        $validation->setRules([
            'foto_mikroskopis_hpa' => [
                'rules' => 'uploaded[foto_mikroskopis_hpa]|ext_in[foto_mikroskopis_hpa,jpg,jpeg,png]|max_size[foto_mikroskopis_hpa,5000]', // 5MB max size
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
        $file = $this->request->getFile('foto_mikroskopis_hpa');
        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru berdasarkan waktu
            $newFileName = date('HisdmY') . '.' . $file->getExtension();
            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/hpa/mikroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Membuat folder jika belum ada
            }
            // Hapus file lama jika ada
            if (!empty($hpa['foto_mikroskopis_hpa'])) {
                $oldFilePath = $uploadPath . $hpa['foto_mikroskopis_hpa'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }
            // Pindahkan file baru ke folder tujuan dengan nama baru
            if ($file->move($uploadPath, $newFileName)) {
                // Update nama file baru di database
                $hpaModel->update($id_hpa, ['foto_mikroskopis_hpa' => $newFileName]);
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
        $hpaData = $this->hpaModel->gethpaWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpaData' => $hpaData,
        ];

        return view('hpa/laporan/laporan_pemeriksaan', $data);
    }

    public function laporan_mutu()
    {
        $hpaData = $this->hpaModel->laporan_mutu() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpaData' => $hpaData,
        ];
        
        return view('hpa/laporan/laporan_mutu', $data);
    }

    public function laporan_kerja()
    {
        $hpaData = $this->hpaModel->gethpaWithTime() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpaData' => $hpaData,
        ];

        return view('hpa/laporan/laporan_kerja', $data);
    }

    public function laporan_oprasional()
    {
        $hpaData = $this->hpaModel->gethpaWithTime() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpaData' => $hpaData,
        ];

        return view('hpa/laporan/laporan_oprasional', $data);
    }

    public function laporan_PUG()
    {
        $hpaData = $this->hpaModel->laporan_PUG() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpaData' => $hpaData,
        ];

        return view('hpa/laporan/laporan_PUG', $data);
    }

    public function laporan_PUB()
    {
        $hpaData = $this->hpaModel->laporan_PUB() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpaData' => $hpaData,
        ];

        return view('hpa/laporan/laporan_PUB', $data);
    }

    public function filter()
    {
        $filterField = $this->request->getGet('filterInput');
        $filterValue = $this->request->getGet('filterValue');
        $startDate   = $this->request->getGet('filterDate');
        $endDate     = $this->request->getGet('filterDate2');

        $filteredData = $this->hpaModel->filterhpaWithRelations(
            $filterField ?: null,
            $filterValue ?: null,
            $startDate,
            $endDate
        );

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'hpaData'       => $filteredData,
        ];

        return view('hpa/laporan/laporan_pemeriksaan', $data);
    }
}
