<?php

namespace App\Controllers\srs;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\Frs\FrsModel;
use App\Models\Srs\SrsModel;
use App\Models\Ihc\IhcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\SimrsModel;
use App\Models\srs\Proses\Penerimaan_srs;
use App\Models\srs\Proses\Pembacaan_srs;
use App\Models\srs\Proses\Penulisan_srs;
use App\Models\srs\Proses\Pemverifikasi_srs;
use App\Models\srs\Proses\Authorized_srs;
use App\Models\srs\Proses\Pencetakan_srs;
use App\Models\srs\Mutu_srs;
use Exception;

class srsController extends BaseController
{
    protected $hpaModel;
    protected $frsModel;
    protected $srsModel;
    protected $ihcModel;
    protected $usersModel;
    protected $patientModel;
    protected $simrsModel;
    protected $penerimaan_srs;
    protected $pemotongan_srs;
    protected $pembacaan_srs;
    protected $penulisan_srs;
    protected $pemverifikasi_srs;
    protected $authorized_srs;
    protected $pencetakan_srs;
    protected $mutu_srs;
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
        $this->penerimaan_srs = new Penerimaan_srs();;
        $this->pembacaan_srs = new Pembacaan_srs();
        $this->penulisan_srs = new Penulisan_srs();
        $this->pemverifikasi_srs = new Pemverifikasi_srs();
        $this->authorized_srs = new Authorized_srs();
        $this->pencetakan_srs = new Pencetakan_srs();
        $this->mutu_srs = new Mutu_srs();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $srsData = $this->srsModel->getsrsWithPatient();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'srsData' => $srsData
        ];

        return view('Srs/index', $data);
    }

    public function index_buku_penerima()
    {
        // Mengambil data srs menggunakan properti yang sudah ada
        $srsData = $this->srsModel->getsrsWithPatientDESC() ?? [];

        // Kirimkan data ke view
        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'srsData' => $srsData,
        ];

        return view('srs/index_buku_penerima', $data);
    }

    public function register()
    {
        $lastsrs = $this->srsModel->getLastKodesrs();
        $currentYear = date('y');
        $nextNumber = 1;

        if ($lastsrs) {
            $lastKode = $lastsrs['kode_srs'];
            $lastParts = explode('/', $lastKode);
            $lastYear = $lastParts[1];

            if ($lastYear == $currentYear) {
                $lastNumber = (int) explode('.', $lastParts[0])[1];
                $nextNumber = $lastNumber + 1;
            }
        }
        $kodesrs = sprintf('SRS.%02d/%s', $nextNumber, $currentYear);

        // Tangkap dari GET parameter
        $register_api_raw = $this->request->getGet('register_api');
        $register_api = json_decode($register_api_raw, true);

        // Ambil data riwayat dari API / Manual
        // Cek apakah data dari API tersedia
        if (!empty($register_api) && is_array($register_api)) {

            // Ambil norm
            $norm = $register_api['norm'] ?? '';

            // Ambil riwayat dari API
            $riwayat_api = [];
            if ($norm !== '') {
                $riwayat_api_response = $this->simrsModel->getPemeriksaanPasien($norm);
                if (!empty($riwayat_api_response['code']) && $riwayat_api_response['code'] == 200) {
                    $riwayat_api = $riwayat_api_response['data'];
                }
            }

            // Map ke array $patient
            $patient = [
                'id_pasien'             => isset($register_api['idpasien']) ? (int) $register_api['idpasien'] : null,
                'norm_pasien'           => $register_api['norm'] ?? '',
                'nama_pasien'           => $register_api['nama'] ?? '',
                'alamat_pasien'         => $register_api['alamat'] ?? '',
                'tanggal_lahir_pasien'  => $register_api['tgl_lhr'] ?? '',
                'jenis_kelamin_pasien'  => $register_api['jeniskelamin'] ?? '',
                'status_pasien'         => $register_api['jenispasien'] ?? '',
                'unitasal'              => $register_api['unitasal'] ?? '',
                'dokterperujuk'         => $register_api['dokterperujuk'] ?? '',
                'pemeriksaan'           => $register_api['pemeriksaan'] ?? '',
                'id_transaksi_simrs'    => $register_api['idtransaksi'] ?? '',
                'lokasi_spesimen'       => $register_api['statuslokasi'] ?? '',
                'diagnosa_klinik'       => $register_api['diagnosaklinik'] ?? '',
                'tindakan_spesimen'     => $register_api['pemeriksaan'] ?? '',
                'id_transaksi'          => isset($register_api['idtransaksi']) ? (int) $register_api['idtransaksi'] : null,
                'tanggal_transaksi'     => $register_api['tanggal'] ?? '',
                'no_register'           => $register_api['register'] ?? ''
            ];
        } elseif ($normPasien = $this->request->getGet('norm_pasien')) {

            // Ambil data pasien dari DB jika norm_pasien tersedia di GET
            $patient = $this->patientModel->where('norm_pasien', $normPasien)->first();
            $riwayat_api = [];
        } else {
            // Jika tidak ada sumber data sama sekali
            $patient = null;
            $riwayat_api = [];
        }

        $data = [
            'id_user'       => session()->get('id_user'),
            'nama_user'     => session()->get('nama_user'),
            'kode_srs'      => $kodesrs,
            'patient'       => $patient,
            'riwayat_api'   => $riwayat_api,
        ];

        return view('srs/Register', $data);
    }

    public function insert()
    {
        try {
            // Set rules untuk validasi
            $this->validation->setRules([
                'kode_srs' => [
                    'rules' => 'required|is_unique[srs.kode_srs]',
                    'errors' => [
                        'required' => 'Kode srs harus diisi.',
                        'is_unique' => 'Kode srs sudah terdaftar!',
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
            $srsData = [
                'kode_srs' => $data['kode_srs'],
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
                'status_srs' => 'Penerimaan',
            ];
            // Simpan data srs
            if (!$this->srsModel->insert($srsData)) {
                throw new Exception('Gagal menyimpan data srs: ' . $this->srsModel->errors());
            }
            // Mendapatkan ID srs yang baru diinsert
            $id_srs = $this->srsModel->getInsertID();
            // Data penerimaan
            $penerimaanData = [
                'id_srs' => $id_srs,
                'status_penerimaan_srs' => 'Belum Penerimaan',
            ];
            // Simpan data penerimaan
            if (!$this->penerimaan_srs->insert($penerimaanData)) {
                throw new Exception('Gagal menyimpan data penerimaan: ' . $this->penerimaan_srs->errors());
            }
            // Data mutu
            $mutuData = [
                'id_srs' => $id_srs,
            ];
            if (!$this->mutu_srs->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu: ' . $this->mutu_srs->errors());
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
        $id_srs = $this->request->getPost('id_srs');
        if (!$id_srs) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID srs tidak valid.']);
        }
        if ($this->srsModel->delete($id_srs)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data.']);
        }
    }

    // Menampilkan form edit srs
    public function edit($id_srs)
    {
        // Ambil data srs berdasarkan ID
        $srs = $this->srsModel->getsrsWithRelationsProses($id_srs);
        if (!$srs) {
            return redirect()->back()->with('message', ['error' => 'srs tidak ditemukan.']);
        }
        $id_pembacaan_srs = $srs['id_pembacaan_srs'];
        $id_pasien = $srs['id_pasien'];
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Ambil data pemotongan berdasarkan ID
        $pembacaan_srs = $this->pembacaan_srs->find($id_pembacaan_srs);
        $dokter_nama = null;
        $analis_nama = null;
        if ($pembacaan_srs) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pembacaan_srs['id_user_dokter_pembacaan_srs'])) {
                $dokter = $this->usersModel->find($pembacaan_srs['id_user_dokter_pembacaan_srs']);
                $dokter_nama = $dokter ? $dokter['nama_user'] : null;
            }

            if (!empty($pembacaan_srs['id_user_pembacaan'])) {
                $analis = $this->usersModel->find($pembacaan_srs['id_user_pembacaan']);
                $analis_nama = $analis ? $analis['nama_user'] : null;
            }
            // Tambahkan ke array pembacaan
            $pembacaan_srs['dokter_nama'] = $dokter_nama;
            $pembacaan_srs['analis_nama'] = $analis_nama;
        }
        // Data yang dikirim ke view
        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'srs'        => $srs,
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan_srs' => $pembacaan_srs,
            'users'      => $users,
        ];

        return view('srs/edit', $data);
    }

    public function edit_makroskopis($id_srs)
    {
        // Ambil data srs berdasarkan ID
        $srs = $this->srsModel->getsrsWithRelationsProses($id_srs);
        $id_pasien = $srs['id_pasien'];
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        if (!$srs) {
            return redirect()->back()->with('message', ['error' => 'srs tidak ditemukan.']);
        }
        $id_penerimaan_srs = $srs['id_penerimaan_srs'];
        // Ambil data penerimaan berdasarkan id_penerimaan_srs
        $penerimaan = $this->penerimaan_srs->find($id_penerimaan_srs);
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Persiapkan data yang akan dikirim ke view
        $data = [
            'srs'        => $srs,
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'penerimaan' => $penerimaan,
            'users'      => $users,
            'id_user'    => $this->session->get('id_user'),
            'nama_user'  => $this->session->get('nama_user'),
        ];

        return view('srs/edit_makroskopis', $data);
    }

    // Menampilkan form edit srs mikroskopis
    public function edit_mikroskopis($id_srs)
    {
        // Ambil data srs berdasarkan ID
        $srs = $this->srsModel->getsrsWithRelationsProses($id_srs);
        $id_pasien = $srs['id_pasien'];
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        if (!$srs) {
            return redirect()->back()->with('message', ['error' => 'srs tidak ditemukan.']);
        }
        $id_penerimaan_srs = $srs['id_penerimaan_srs'];
        $id_pembacaan_srs = $srs['id_pembacaan_srs'];
        $penerimaan_srs = $id_penerimaan_srs ? $this->penerimaan_srs->find($id_penerimaan_srs) : [];
        $pembacaan_srs = $id_pembacaan_srs ? $this->pembacaan_srs->find($id_pembacaan_srs) : [];
        if (!empty($srs['id_penerimaan_srs'])) {
            $penerimaan_srs = $this->penerimaan_srs->find($srs['id_penerimaan_srs']) ?? [];
            // Ambil nama analis dari penerimaan jika tersedia
            if (!empty($penerimaan_srs['id_user_penerimaan_srs'])) {
                $analis = $this->usersModel->find($penerimaan_srs['id_user_penerimaan_srs']);
                $penerimaan_srs['analis_nama'] = $analis ? $analis['nama_user'] : null;
            } else {
                $penerimaan_srs['analis_nama'] = null;
            }
        }
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        // Persiapkan data yang akan dikirim ke view
        $data = [
            'srs'             => $srs,
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'penerimaan_srs' => $penerimaan_srs,
            'pembacaan_srs'   => $pembacaan_srs,
            'users'           => $users,
            'id_user'         => session()->get('id_user'),
            'nama_user'       => session()->get('nama_user'),
        ];

        return view('srs/edit_mikroskopis', $data);
    }

    public function edit_penulisan($id_srs)
    {
        // Ambil data srs berdasarkan ID
        $srs = $this->srsModel->getsrsWithRelationsProses($id_srs);
        $id_pasien = $srs['id_pasien'];
        if (!$srs) {
            return redirect()->back()->with('message', ['error' => 'srs tidak ditemukan.']);
        }
        // Inisialisasi array untuk pembacaan dan penulisan srs
        $pembacaan_srs = [];
        $penulisan_srs = [];
        // Ambil data pembacaan srs jika tersedia
        if (!empty($srs['id_pembacaan_srs'])) {
            $pembacaan_srs = $this->pembacaan_srs->find($srs['id_pembacaan_srs']) ?? [];
            // Ambil nama dokter dari pembacaan jika tersedia
            if (!empty($pembacaan_srs['id_user_dokter_pembacaan_srs'])) {
                $dokter = $this->usersModel->find($pembacaan_srs['id_user_dokter_pembacaan_srs']);
                $pembacaan_srs['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
            } else {
                $pembacaan_srs['dokter_nama'] = null;
            }
        }
        // Ambil data penulisan srs jika tersedia
        if (!empty($srs['id_penulisan_srs'])) {
            $penulisan_srs = $this->penulisan_srs->find($srs['id_penulisan_srs']) ?? [];
        }
        // Ambil daftar user dengan status "Dokter"
        $users = $this->usersModel->where('status_user', 'Dokter')->findAll();
        $riwayat_hpa = $this->hpaModel->riwayatPemeriksaanhpa($id_pasien);
        $riwayat_frs = $this->frsModel->riwayatPemeriksaanfrs($id_pasien);
        $riwayat_srs = $this->srsModel->riwayatPemeriksaansrs($id_pasien);
        $riwayat_ihc = $this->ihcModel->riwayatPemeriksaanihc($id_pasien);
        // Data yang akan dikirim ke view
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'srs' => $srs,
            'riwayat_hpa'        => $riwayat_hpa,
            'riwayat_frs'        => $riwayat_frs,
            'riwayat_srs'        => $riwayat_srs,
            'riwayat_ihc'        => $riwayat_ihc,
            'pembacaan' => $pembacaan_srs,
            'penulisan' => $penulisan_srs,
            'users' => $users,
        ];

        return view('srs/edit_penulisan', $data);
    }

    public function edit_print($id_srs)
    {
        // Ambil data srs berdasarkan ID
        $srs = $this->srsModel->getsrsWithRelationsProses($id_srs);
        // Ambil data pembacaan SRS jika tersedia
        if (!empty($srs['id_pembacaan_srs'])) {
            $pembacaan_srs = $this->pembacaan_srs->find($srs['id_pembacaan_srs']) ?? [];
            // Ambil nama dokter dari pembacaan jika tersedia
            if (!empty($pembacaan_srs['id_user_dokter_pembacaan_srs'])) {
                $dokter = $this->usersModel->find($pembacaan_srs['id_user_dokter_pembacaan_srs']);
                $pembacaan_srs['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
            } else {
                $pembacaan_srs['dokter_nama'] = null;
            }
        }
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'srs' => $srs,
            'pembacaan_srs' => $pembacaan_srs,
        ];

        return view('srs/edit_print', $data);
    }

    public function update($id_srs)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');

        $srs = $this->srsModel->getsrsWithRelationsProses($id_srs);
        if (!$srs) {
            return redirect()->back()->with('message', ['error' => 'srs tidak ditemukan.']);
        }
        $id_pembacaan_srs = $srs['id_pembacaan_srs'];

        // Validasi form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_srs' => [
                'rules' => 'required|is_unique[srs.kode_srs,id_srs,' . $id_srs . ']',
                'errors' => [
                    'required' => 'Kode srs harus diisi.',
                    'is_unique' => 'Kode srs sudah terdaftar!',
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
        // Proses update tabel srs
        if ($this->srsModel->update($id_srs, $data)) {
            // Update data pembacaan jika id_user_dokter_pembacaan_srs ada
            if (!empty($data['id_user_dokter_pembacaan_srs'])) {
                $pembacaan = $this->pembacaan_srs->where('id_pembacaan_srs', $id_pembacaan_srs)->first();

                if ($pembacaan) {
                    $this->pembacaan_srs->update($pembacaan['id_pembacaan_srs'], [
                        'id_user_dokter_pembacaan_srs' => $data['id_user_dokter_pembacaan_srs'],
                    ]);
                }
            }
            switch ($page_source) {
                case 'edit_makroskopis':
                    $id_penerimaan_srs = $this->request->getPost('id_penerimaan_srs');
                    $this->penerimaan_srs->update($id_penerimaan_srs, [
                        'id_user_penerimaan_srs' => $id_user,
                        'status_penerimaan_srs' => 'Selesai Penerimaan',
                        'selesai_penerimaan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect()->to('srs/edit_makroskopis/' . $id_srs)->with('success', 'Data mikroskopis berhasil diperbarui.');
                case 'edit_mikroskopis':
                    $id_pembacaan_srs = $this->request->getPost('id_pembacaan_srs');
                    $this->pembacaan_srs->update($id_pembacaan_srs, [
                        'id_user_pembacaan_srs' => $id_user,
                        'status_pembacaan_srs' => 'Selesai Pembacaan',
                        'selesai_pembacaan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect()->to('srs/edit_mikroskopis/' . $id_srs)->with('success', 'Data mikroskopis berhasil diperbarui.');
                case 'edit_penulisan':
                    $id_penulisan_srs = $this->request->getPost('id_penulisan_srs');
                    $this->penulisan_srs->update($id_penulisan_srs, [
                        'id_user_penulisan_srs' => $id_user,
                        'status_penulisan_srs' => 'Selesai Penulisan',
                        'selesai_penulisan_srs' => date('Y-m-d H:i:s'),
                    ]);
                    // Ambil data dari form
                    $lokasi_spesimen = $this->request->getPost('lokasi_spesimen');
                    $diagnosa_klinik = $this->request->getPost('diagnosa_klinik');
                    $makroskopis_srs = $this->request->getPost('makroskopis_srs');
                    $mikroskopis_srs = $this->request->getPost('mikroskopis_srs');
                    $tindakan_spesimen = $this->request->getPost('tindakan_spesimen');
                    $hasil_srs = $this->request->getPost('hasil_srs');
                    // Simpan data lokasi, diagnosa, makroskopis, mikroskopis, hasil terlebih dahulu
                    $this->srsModel->update($id_srs, [
                        'lokasi_spesimen' => $lokasi_spesimen,
                        'diagnosa_klinik' => $diagnosa_klinik,
                        'makroskopis_srs' => $makroskopis_srs,
                        'mikroskopis_srs' => $mikroskopis_srs,
                        'hasil_srs' => $hasil_srs,
                    ]);
                    // Setelah semua data tersimpan, buat data print_srs
                    $print_srs = '
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
                        <font size="5" face="verdana"><b> MAKROSKOPIK :</b> <br></font>
                    </div>
                    <div>
                        <font size="5" face="verdana">
                    ' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>', '<br>'], '', $makroskopis_srs))) . '
                    </font>
                    </div>
                    <br>
                    <div>
                        <font size="5" face="verdana"><b>MIKROSKOPIK :</b><br></font>
                    </div>
                    <div>
                        <font size="5" face="verdana">' . nl2br(htmlspecialchars(str_replace(['<p>', '</p>'], '', $mikroskopis_srs))) . '</font>
                    </div>
                    <br>
                    <div>
                        <font size="5" face="verdana"><b>KESIMPULAN :</b> ' . htmlspecialchars($lokasi_spesimen) . ', ' . htmlspecialchars($tindakan_spesimen) . ':</b></font>
                    </div>
                    <div>
                        <font size="5" face="verdana"><b>' . strtoupper(htmlspecialchars(str_replace(['<p>', '</p>'], '', $hasil_srs))) . '</b></font>
                    </div>
                    <br>';
                    // Simpan print_srs setelah semua data yang dibutuhkan telah ada
                    $this->srsModel->update($id_srs, [
                        'print_srs' => $print_srs,
                    ]);
                    return redirect()->to('srs/edit_penulisan/' . $id_srs)->with('success', 'Data penulisan berhasil diperbarui.');
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
        $id_srs = $this->request->getPost('id_srs');
        // Inisialisasi model
        $srsModel = new srsModel();

        // Mengambil data dari form
        $penerima_srs = $this->request->getPost('penerima_srs');

        // Data yang akan diupdate
        $data = [
            'penerima_srs' => $penerima_srs,
            'tanggal_penerima' => date('Y-m-d H:i:s'),
        ];

        // Update data penerima_srs berdasarkan id_srs
        $srsModel->updatePenerima($id_srs, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('srs/index_buku_penerima')->with('success', 'Penerima berhasil disimpan.');
    }


    public function update_print($id_srs)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');
        // Mendapatkan id_srs dari POST
        if (!$id_srs) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID srs tidak ditemukan.');
        }
        // Mengambil data dari POST dan melakukan update
        $data = $this->request->getPost();
        $this->srsModel->update($id_srs, $data);
        $redirect = $this->request->getPost('redirect');
        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }
        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'index_pemverifikasi_srs' && isset($_POST['id_pemverifikasi_srs'])) {
            $id_pemverifikasi_srs = $this->request->getPost('id_pemverifikasi_srs');
            $this->pemverifikasi_srs->update($id_pemverifikasi_srs, [
                'id_user_pemverifikasi_srs' => $id_user,
                'status_pemverifikasi_srs' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi_srs' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi_srs/index')->with('success', 'Data berhasil diverifikasi.');
        }
        if ($redirect === 'index_authorized_srs' && isset($_POST['id_authorized_srs'])) {
            $id_authorized_srs = $this->request->getPost('id_authorized_srs');
            $this->authorized_srs->update($id_authorized_srs, [
                'id_user_authorized_srs' => $id_user,
                'id_user_dokter_authorized_srs' => $id_user,
                'status_authorized_srs' => 'Selesai Authorized',
                'selesai_authorized_srs' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('authorized_srs/index')->with('success', 'Data berhasil diauthorized.');
        }
        if ($redirect === 'index_pencetakan_srs' && isset($_POST['id_pencetakan_srs'])) {
            $id_pencetakan_srs = $this->request->getPost('id_pencetakan_srs');
            $this->pencetakan_srs->update($id_pencetakan_srs, [
                'id_user_pencetakan_srs' => $id_user,
                'status_pencetakan_srs' => 'Selesai Pencetakan',
                'selesai_pencetakan_srs' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan_srs/index')->with('success', 'Data berhasil simpan.');
        }
        // Jika redirect tidak sesuai dengan yang diharapkan
        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
    }

    public function update_status()
    {
        // Ambil data dari POST
        $id_srs = $this->request->getPost('id_srs');
        $status_srs = $this->request->getPost('status_srs');
        // Data yang akan diupdate
        $data = [
            'status_srs' => $status_srs,
        ];
        // Gunakan model yang sudah diinisialisasi di konstruktor
        $this->srsModel->update($id_srs, $data);
        // Redirect dengan pesan sukses
        return redirect()->to('srs/index')->with('success', 'Status SRS berhasil disimpan.');
    }

    public function uploadFotoMakroskopis($id_srs)
    {
        date_default_timezone_set('Asia/Jakarta');
        $srsModel = new srsModel();

        // Ambil data srs untuk mendapatkan nama file lama
        $srs = $srsModel->find($id_srs);

        if (!$srs) {
            return redirect()->back()->with('error', 'Data srs tidak ditemukan.');
        }

        // Ambil kode_srs dan ekstrak nomor dari format "H.nomor/25"
        $kode_srs = $srs['kode_srs'];
        preg_match('/SRS\.(\d+)\/\d+/', $kode_srs, $matches);
        $kode_srs = isset($matches[1]) ? $matches[1] : '000';

        // Validasi input file
        $validation = \Config\Services::validation();
        $validation->setRules([
            'foto_makroskopis_srs' => [
                'rules' => 'uploaded[foto_makroskopis_srs]|ext_in[foto_makroskopis_srs,jpg,jpeg,png]',
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
        $file = $this->request->getFile('foto_makroskopis_srs');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru berdasarkan waktu
            $newFileName = $kode_srs . date('dmY') . '.' . $file->getExtension();

            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/srs/makroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Membuat folder jika belum ada
            }

            // Hapus file lama jika ada
            if (!empty($srs['foto_makroskopis_srs'])) {
                $oldFilePath = $uploadPath . $srs['foto_makroskopis_srs'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Pindahkan file baru ke folder tujuan dengan nama baru
            if ($file->move($uploadPath, $newFileName)) {
                // Update nama file baru di database
                $srsModel->update($id_srs, ['foto_makroskopis_srs' => $newFileName]);

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

    public function uploadFotoMikroskopis($id_srs)
    {
        date_default_timezone_set('Asia/Jakarta');
        $srsModel = new srsModel();

        // Ambil data srs untuk mendapatkan nama file lama
        $srs = $srsModel->find($id_srs);

        // Validasi input file
        $validation = \Config\Services::validation();
        $validation->setRules([
            'foto_mikroskopis_srs' => [
                'rules' => 'uploaded[foto_mikroskopis_srs]|ext_in[foto_mikroskopis_srs,jpg,jpeg,png]|max_size[foto_mikroskopis_srs,5000]', // 5MB max size
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
        $file = $this->request->getFile('foto_mikroskopis_srs');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru berdasarkan waktu
            $newFileName = date('HisdmY') . '.' . $file->getExtension();

            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/srs/mikroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Membuat folder jika belum ada
            }

            // Hapus file lama jika ada
            if (!empty($srs['foto_mikroskopis_srs'])) {
                $oldFilePath = $uploadPath . $srs['foto_mikroskopis_srs'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Pindahkan file baru ke folder tujuan dengan nama baru
            if ($file->move($uploadPath, $newFileName)) {
                // Update nama file baru di database
                $srsModel->update($id_srs, ['foto_mikroskopis_srs' => $newFileName]);

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
        $srsData = $this->srsModel->getsrsWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'srsData' => $srsData,
        ];

        return view('srs/laporan/laporan_pemeriksaan', $data);
    }

    public function laporan_kerja()
    {
        $srsData = $this->srsModel->getsrsWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'srsData' => $srsData,
        ];

        return view('srs/laporan/laporan_kerja', $data);
    }

    public function laporan_oprasional()
    {
        $srsData = $this->srsModel->getsrsWithRelations() ?? [];

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'srsData' => $srsData,
        ];

        return view('srs/laporan/laporan_oprasional', $data);
    }

    public function filter()
    {
        $filterField = $this->request->getGet('filterInput');
        $filterValue = $this->request->getGet('filterValue');
        $startDate   = $this->request->getGet('filterDate');
        $endDate     = $this->request->getGet('filterDate2');

        $filteredData = $this->srsModel->filtersrsWithRelations(
            $filterField ?: null,
            $filterValue ?: null,
            $startDate,
            $endDate
        );

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'srsData'       => $filteredData,
        ];

        return view('srs/laporan/laporan_pemeriksaan', $data);
    }
}
