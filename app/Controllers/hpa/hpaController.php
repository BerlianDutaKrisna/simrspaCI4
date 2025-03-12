<?php

namespace App\Controllers\Hpa;

use App\Controllers\BaseController;
use App\Controllers\mutu;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
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
    protected $userModel;
    protected $patientModel;
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
        // Inisialisasi model HPA
        $this->hpaModel = new hpaModel();
        $this->userModel = new UsersModel();
        $this->patientModel = new PatientModel();
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

    public function index_hpa()
    {
        // Mengambil data dari session
        $session = session();
        $id_user = $session->get('id_user');
        $nama_user = $session->get('nama_user');

        // Memastikan session terisi dengan benar
        if (!$id_user || !$nama_user) {
            return redirect()->to('login'); // Redirect ke halaman login jika session tidak ada
        }

        // Memanggil model HpaModel untuk mengambil data
        $hpaModel = new HpaModel();
        $hpaData = $hpaModel->getHpaWithAllPatient();

        // Pastikan $hpaData berisi array
        if (!$hpaData) {
            $hpaData = []; // Jika tidak ada data, set menjadi array kosong
        }
        return view('hpa/index_hpa', [
            'hpaData' => $hpaData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
    }

    public function register()
    {
        $lastHPA = $this->hpaModel->getLastKodeHPA();
        $currentYear = date('y');
        $nextNumber = 1;
        if ($lastHPA) {
            $lastKode = $lastHPA['kode_hpa'];
            $lastParts = explode('/', $lastKode);
            $lastYear = $lastParts[1];
            if ($lastYear == $currentYear) {
                $lastNumber = (int) explode('.', $lastParts[0])[1];
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
        } else {
            $nextNumber = 1;
        }
        $kodeHPA = 'H.' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT) . '/' . $currentYear;
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'kode_hpa' => $kodeHPA,
            'patient' => null,
        ];
        $norm_pasien = $this->request->getGet('norm_pasien');
        if ($norm_pasien) {
            $patientModel = new PatientModel();
            $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();
            $data['patient'] = $patient ?: null;
        }
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
            $dokter_pengirim = !empty($data['dokter_pengirim']) ? $data['dokter_pengirim'] : $data['dokter_pengirim_custom'];
            // Tentukan tindakan_spesimen
            $tindakan_spesimen = !empty($data['tindakan_spesimen']) ? $data['tindakan_spesimen'] : $data['tindakan_spesimen_custom'];
            // Data yang akan disimpan
            $hpaData = [
                'kode_hpa' => $data['kode_hpa'],
                'id_pasien' => $data['id_pasien'],
                'unit_asal' => $unit_asal,
                'dokter_pengirim' => $dokter_pengirim,
                'tanggal_permintaan' => $data['tanggal_permintaan'] ?: null,
                'tanggal_hasil' => $data['tanggal_hasil'] ?: null,
                'lokasi_spesimen' => $data['lokasi_spesimen'],
                'tindakan_spesimen' => $tindakan_spesimen,
                'diagnosa_klinik' => $data['diagnosa_klinik'],
                'status_hpa' => 'Penerimaan',
            ];
            // Simpan data HPA
            if (!$this->hpaModel->insert($hpaData)) {
                throw new Exception('Gagal menyimpan data HPA: ' . $this->hpaModel->errors());
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
        // Mendapatkan data dari request
        $id_hpa = $this->request->getPost('id_hpa');

        // Cek apakah id_hpa valid
        if ($id_hpa) {
            // Inisialisasi model
            $hpaModel = new HpaModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel hpa
            $deleteHpa = $hpaModel->delete($id_hpa);

            // Cek apakah delete berhasil
            if ($deleteHpa) {
                // Selesaikan transaksi
                $db->transComplete();

                // Cek apakah transaksi berhasil
                if ($db->transStatus() === FALSE) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data.']);
                }
            } else {
                // Jika id_hpa tidak valid, kirimkan response error
                return $this->response->setJSON(['success' => false, 'message' => 'ID HPA tidak valid.']);
            }
        }
        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function index_buku_penerima()
    {
        // Mengambil data dari session
        $session = session();
        $id_user = $session->get('id_user');
        $nama_user = $session->get('nama_user');

        // Memastikan session terisi dengan benar
        if (!$id_user || !$nama_user) {
            return redirect()->to('login'); // Redirect ke halaman login jika session tidak ada
        }

        // Memanggil model HpaModel untuk mengambil data
        $hpaModel = new HpaModel();
        $hpaData = $hpaModel->getHpaWithAllPatient();

        // Pastikan $hpaData berisi array
        if (!$hpaData) {
            $hpaData = []; // Jika tidak ada data, set menjadi array kosong
        }

        // Kirimkan data ke view
        return view('hpa/index_buku_penerima', [
            'hpaData' => $hpaData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
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
        $hpaModel->updatePenerima($id_hpa, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('hpa/index_buku_penerima')->with('success', 'Penerima berhasil disimpan.');
    }

    // Menampilkan form edit hpa
    public function edit_hpa($id_hpa)
    {
        $hpaModel = new HpaModel();
        $userModel = new UsersModel();
        $Pemotongan_hpa = new Pemotongan_hpa(); // Model Pemotongan
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }
        $id_pemotongan_hpa = $hpa['id_pemotongan_hpa'];
        // Ambil data pengguna dengan status "Dokter" dari tabel users
        $users = $userModel->where('status_user', 'Dokter')->findAll();


        // Ambil data pemotongan berdasarkan id_pemotongan_hpa
        $pemotongan = $Pemotongan_hpa->find($id_pemotongan_hpa);
        // Ambil data pemotongan berdasarkan id_pemotongan_hpa
        $pemotongan = $Pemotongan_hpa->find($id_pemotongan_hpa);

        // Inisialisasi dokter dan analis
        $dokter_nama = null;
        $analis_nama = null;

        if ($pemotongan) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pemotongan['id_user_dokter_pemotongan_hpa'])) {
                $dokter = $userModel->find($pemotongan['id_user_dokter_pemotongan_hpa']);
                $dokter_nama = $dokter ? $dokter['nama_user'] : null;
            }

            if (!empty($pemotongan['id_user_pemotongan'])) {
                $analis = $userModel->find($pemotongan['id_user_pemotongan']);
                $analis_nama = $analis ? $analis['nama_user'] : null;
            }

            // Tambahkan ke array pemotongan
            $pemotongan['dokter_nama'] = $dokter_nama;
            $pemotongan['analis_nama'] = $analis_nama;
        }


        // Ambil data dari sesi pengguna
        $data = [
            'id_user'   => session()->get('id_user'),
            'pemotongan' => $pemotongan, // Data Pemotongan
            'nama_user' => session()->get('nama_user'),
            'users'     => $users, // Menambahkan daftar dokter jika diperlukan di view
        ];

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);

        if ($hpa) {
            $data['hpa'] = $hpa;
            return view('hpa/edit_hpa', $data);
        } else {
            return redirect()->back()->withInput()->with('message', [
                'error' => 'HPA tidak ditemukan.'
            ]);
        }
    }

    public function edit_makroskopis($id_hpa, $id_pemotongan_hpa)
    {
        // Ambil data HPA berdasarkan ID
        $hpa = $this->hpaModel->getHpaWithPatient($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }
    
        // Ambil data pemotongan berdasarkan id_pemotongan_hpa
        $pemotongan = $this->pemotongan_hpa->find($id_pemotongan_hpa);
    
        if (!empty($pemotongan)) {
            // Ambil nama dokter dan analis jika ID tersedia
            $pemotongan['dokter_nama'] = !empty($pemotongan['id_user_dokter_pemotongan_hpa'])
                ? ($this->userModel->find($pemotongan['id_user_dokter_pemotongan_hpa'])['nama_user'] ?? null)
                : null;
    
            $pemotongan['analis_nama'] = !empty($pemotongan['id_user_pemotongan'])
                ? ($this->userModel->find($pemotongan['id_user_pemotongan'])['nama_user'] ?? null)
                : null;
        } else {
            // Jika data pemotongan tidak ditemukan, set sebagai array kosong untuk mencegah error
            $pemotongan = [];
        }
    
        // Ambil data pengguna dengan status "Dokter"
        $users = $this->userModel->where('status_user', 'Dokter')->findAll();
    
        // Persiapkan data yang akan dikirim ke view
        $data = [
            'hpa'        => $hpa,
            'pemotongan' => $pemotongan,
            'users'      => $users,
            'id_user'    => $this->session->get('id_user'),
            'nama_user'  => $this->session->get('nama_user'),
        ];
        
        return view('hpa/edit_makroskopis', $data);
    }
    

    // Menampilkan form edit hpa
    public function edit_mikroskopis($id_hpa, $id_pemotongan_hpa, $id_pembacaan_hpa, $id_mutu_hpa)
    {
        // Ambil data hpa berdasarkan ID
        $hpa = $this->hpaModel->getHpaWithPatient($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }

        // Ambil data pemotongan dan pembacaan_hpa berdasarkan ID
        $pemotongan_hpa = $this->pemotongan_hpa->find($id_pemotongan_hpa);
        $pembacaan_hpa = $this->pembacaan_hpa->find($id_pembacaan_hpa);
        $mutu = $this->mutu_hpa->find($id_mutu_hpa);

        // Inisialisasi dokter dan analis
        $dokter_nama = null;
        $analis_nama = null;

        if ($pemotongan_hpa) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pemotongan_hpa['id_user_dokter_pemotongan_hpa'])) {
                $dokter = $this->userModel->find($pemotongan_hpa['id_user_dokter_pemotongan_hpa']);
                $dokter_nama = $dokter ? $dokter['nama_user'] : null;
            }

            if (!empty($pemotongan_hpa['id_user_pemotongan'])) {
                $analis = $this->userModel->find($pemotongan_hpa['id_user_pemotongan']);
                $analis_nama = $analis ? $analis['nama_user'] : null;
            }

            // Tambahkan ke array pemotongan
            $pemotongan_hpa['dokter_nama'] = $dokter_nama;
            $pemotongan_hpa['analis_nama'] = $analis_nama;
        }

        // Pastikan mutu tidak kosong sebelum mengambil indikator
        $indikator_4 = $mutu ? $mutu['indikator_4'] : "0";
        $indikator_5 = $mutu ? $mutu['indikator_5'] : "0";
        $indikator_6 = $mutu ? $mutu['indikator_6'] : "0";
        $indikator_7 = $mutu ? $mutu['indikator_7'] : "0";
        $indikator_8 = $mutu ? $mutu['indikator_8'] : "0";
        $total_nilai_mutu_hpa = $mutu ? $mutu['total_nilai_mutu_hpa'] : "0";

        // Tambahkan nilai indikator ke dalam pembacaan_hpa
        $pembacaan_hpa['indikator_4'] = $indikator_4;
        $pembacaan_hpa['indikator_5'] = $indikator_5;
        $pembacaan_hpa['indikator_6'] = $indikator_6;
        $pembacaan_hpa['indikator_7'] = $indikator_7;
        $pembacaan_hpa['indikator_8'] = $indikator_8;
        $pembacaan_hpa['total_nilai_mutu_hpa'] = $total_nilai_mutu_hpa;
        // Ambil data pengguna dengan status "Dokter" dari tabel users
        $users = $this->userModel->where('status_user', 'Dokter')->findAll();

        // Persiapkan data yang akan dikirim ke view
        $data = [
            'hpa' => $hpa,
            'pemotongan_hpa' => $pemotongan_hpa,
            'pembacaan_hpa' => $pembacaan_hpa,
            'mutu' => $mutu,
            'users' => $users,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        
        return view('hpa/edit_mikroskopis', $data);
    }

    public function edit_penulisan($id_hpa, $id_pembacaan_hpa, $id_penulisan_hpa)
    {
        $hpa = $this->hpaModel->getHpaWithPatient($id_hpa);    
        $pembacaan = $this->pembacaan_hpa->find($id_pembacaan_hpa);
        $penulisan = $this->penulisan_hpa->find($id_penulisan_hpa);
        $users = $this->userModel->where('status_user', 'Dokter')->findAll();
    
        // Ambil nama dokter dari pembacaan berdasarkan id_user_dokter_pembacaan_hpa
        if ($pembacaan && !empty($pembacaan['id_user_dokter_pembacaan_hpa'])) {
            $dokter = $this->userModel->find($pembacaan['id_user_dokter_pembacaan_hpa']);
            $pembacaan['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
        } else {
            $pembacaan['dokter_nama'] = null;
        }
    
        // Data yang akan dikirim ke view
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'hpa' => $hpa,
            'pembacaan' => $pembacaan,
            'penulisan' => $penulisan,
            'users' => $users,
        ];
        
        return view('hpa/edit_penulisan', $data);
    }
    

    public function edit_print_hpa($id_hpa, $id_penerimaan_hpa, $id_pembacaan_hpa, $id_pemverivifikasi_hpa, $id_authorized_hpa, $id_pencetakan_hpa)
    {
        // Ambil data hpa berdasarkan ID
        $hpa = $this->hpaModel->getHpaWithPatient($id_hpa);
        $penerimaan_hpa = $this->penerimaan_hpa->find($id_penerimaan_hpa);
        $pembacaan_hpa = $this->pembacaan_hpa->find($id_pembacaan_hpa);
        $pemverifikasi_hpa = $this->pemverifikasi_hpa->find($id_pemverivifikasi_hpa) ?? [];
        $authorized_hpa = $this->authorized_hpa->find($id_authorized_hpa) ?? [];
        $pencetakan_hpa = $this->pencetakan_hpa->find($id_pencetakan_hpa) ?? [];

        // Persiapkan data yang akan dikirim ke view
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'hpa' => $hpa,
            'penerimaan_hpa' => $penerimaan_hpa,
            'pembacaan_hpa' => $pembacaan_hpa,
            'pemverifikasi_hpa' => $pemverifikasi_hpa,
            'authorized_hpa' => $authorized_hpa,
            'pencetakan_hpa' => $pencetakan_hpa,
        ];
        
        return view('hpa/edit_print_hpa', $data);
    }

    public function update($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');

        // Mendapatkan id_hpa dari POST
        if (!$id_hpa) {
            $id_hpa = $this->request->getPost('id_hpa');
        }

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
        $page_source = $this->request->getPost('page_source');

        // Mengubah 'jumlah_slide' jika memilih 'lainnya'
        if ($this->request->getPost('jumlah_slide') === 'lainnya') {
            $data['jumlah_slide'] = $this->request->getPost('jumlah_slide') === 'lainnya'
                ? $this->request->getPost('jumlah_slide_custom')
                : $this->request->getPost('jumlah_slide');
        }

        // Proses update tabel HPA
        if ($this->hpaModel->update($id_hpa, $data)) {
            // Update data pemotongan jika id_user_dokter_pemotongan_hpa ada
            if (!empty($data['id_user_dokter_pemotongan_hpa'])) {
                $pemotongan = $this->pemotongan_hpa->where('id_hpa', $id_hpa)->first();

                if ($pemotongan) {
                    $this->pemotongan_hpa->update($pemotongan['id_pemotongan_hpa'], [
                        'id_user_dokter_pemotongan_hpa' => $data['id_user_dokter_pemotongan_hpa'],
                    ]);
                }
            }

            switch ($page_source) {
                case 'edit_makroskopis':
                    $id_pemotongan_hpa = $this->request->getPost('id_pemotongan_hpa');
                    $this->pemotongan_hpa->update($id_pemotongan_hpa, [
                        'id_user_pemotongan_hpa' => $id_user,
                        'status_pemotongan_hpa' => 'Selesai Pemotongan',
                        'selesai_pemotongan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect()->to('hpa/edit_makroskopis/' . $id_hpa . '/' . $id_pemotongan_hpa)->with('success', 'Data makroskopis berhasil diperbarui.');

                case 'edit_mikroskopis':
                    $id_pemotongan_hpa = $this->request->getPost('id_pemotongan_hpa');
                    $id_pembacaan_hpa = $this->request->getPost('id_pembacaan_hpa');
                    $id_user_dokter_pembacaan_hpa = (int) $this->request->getPost('id_user_dokter_pemotongan_hpa');
                    $this->pembacaan_hpa->update($id_pembacaan_hpa, [
                        'id_user_pembacaan_hpa' => $id_user,
                        'id_user_dokter_pembacaan_hpa' => $id_user_dokter_pembacaan_hpa,
                        'status_pembacaan_hpa' => 'Selesai Pembacaan',
                        'selesai_pembacaan_hpa' => date('Y-m-d H:i:s'),
                    ]);
                    $id_mutu_hpa = $this->request->getPost('id_mutu_hpa');
                    $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                    $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                    $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                    $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                    $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                    $total_nilai_mutu_hpa = (string) ($this->request->getPost('total_nilai_mutu_hpa') ?? '0');
                    $keseluruhan_nilai_mutu = $total_nilai_mutu_hpa + (int)$indikator_5 + (int)$indikator_6 + (int)$indikator_7 + (int)$indikator_8;
                    $this->mutu_hpa->update($id_mutu_hpa, [
                        'indikator_4' => $indikator_4,
                        'indikator_5' => $indikator_5,
                        'indikator_6' => $indikator_6,
                        'indikator_7' => $indikator_7,
                        'indikator_8' => $indikator_8,
                        'total_nilai_mutu_hpa' => $keseluruhan_nilai_mutu,
                    ]);
                    return redirect()->to('hpa/edit_mikroskopis/' . $id_hpa . '/' . $id_pemotongan_hpa . '/' . $id_pembacaan_hpa . '/' . $id_mutu_hpa)->with('success', 'Data mikroskopis berhasil diperbarui.');

                case 'edit_penulisan':
                    $id_pembacaan_hpa = $this->request->getPost('id_pembacaan_hpa');
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

                    return redirect()->to('hpa/edit_penulisan/' . $id_hpa . '/' . $id_pembacaan_hpa . '/' . $id_penulisan_hpa)->with('success', 'Data penulisan berhasil diperbarui.');

                default:
                    return redirect()->back()->with('success', 'Data berhasil diperbarui.');
            }
        }
        return redirect()->back()->with('error', 'Gagal memperbarui data.');
    }

    public function update_print_hpa($id_hpa)
    {

        date_default_timezone_set('Asia/Jakarta');
        $id_user = session()->get('id_user');
        // Mendapatkan id_hpa dari POST
        if (!$id_hpa) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID HPA tidak ditemukan.');
        }
        // Mengambil data dari POST dan melakukan update
        $data = $this->request->getPost();
        $this->hpaModel->update($id_hpa, $data);
        $redirect = $this->request->getPost('redirect');
        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }
        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'index_pemverifikasi_hpa' && isset($_POST['id_pemverifikasi_hpa'])) {
            $id_pemverifikasi_hpa = $this->request->getPost('id_pemverifikasi_hpa');
            $this->pemverifikasi_hpa->update($id_pemverifikasi_hpa, [
                'id_user_pemverifikasi_hpa' => $id_user,
                'status_pemverifikasi_hpa' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi_hpa' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi_hpa/index')->with('success', 'Data berhasil diverifikasi.');
        }
        if ($redirect === 'index_authorized_hpa' && isset($_POST['id_authorized_hpa'])) {
            $id_authorized_hpa = $this->request->getPost('id_authorized_hpa');
            $this->authorized_hpa->update($id_authorized_hpa, [
                'id_user_authorized_hpa' => $id_user,
                'status_authorized_hpa' => 'Selesai Authorized',
                'selesai_authorized_hpa' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('authorized_hpa/index')->with('success', 'Data berhasil diauthorized.');
        }
        if ($redirect === 'index_pencetakan_hpa' && isset($_POST['id_pencetakan_hpa'])) {
            $id_pencetakan_hpa = $this->request->getPost('id_pencetakan_hpa');
            $this->pencetakan_hpa->update($id_pencetakan_hpa, [
                'id_user_pencetakan_hpa' => $id_user,
                'status_pencetakan_hpa' => 'Selesai Pencetakan',
                'selesai_pencetakan_hpa' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan_hpa/index')->with('success', 'Data berhasil dicetak.');
        }
        // Jika redirect tidak sesuai dengan yang diharapkan
        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
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
            return redirect()->back()->with('success', 'Foto makroskopis berhasil diunggah.');
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

    public function update_status_hpa()
    {
        $id_hpa = $this->request->getPost('id_hpa');
        // Inisialisasi model
        $hpaModel = new HpaModel();

        // Mengambil data dari form
        $status_hpa = $this->request->getPost('status_hpa');

        // Data yang akan diupdate
        $data = [
            'status_hpa' => $status_hpa,
        ];

        // Update data status_hpa berdasarkan id_hpa
        $hpaModel->updateStatusHpa($id_hpa, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('hpa/index_hpa')->with('success', 'Status HPA berhasil disimpan.');
    }
}
