<?php

namespace App\Controllers;

use App\Models\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\ProsesModel\PenerimaanModel;
use App\Models\ProsesModel\PengirisanModel;
use App\Models\ProsesModel\PemotonganModel;
use App\Models\ProsesModel\PemprosesanModel;
use App\Models\ProsesModel\PenanamanModel;
use App\Models\ProsesModel\PemotonganTipisModel;
use App\Models\ProsesModel\PewarnaanModel;
use App\Models\ProsesModel\PembacaanModel;
use App\Models\ProsesModel\PenulisanModel;
use App\Models\ProsesModel\PemverifikasiModel;
use App\Models\ProsesModel\PencetakanModel;
use App\Models\MutuModel;

use CodeIgniter\Controller;
use Exception;

class Exam extends BaseController
{
    protected $hpaModel;
    protected $usersModel;
    protected $pembacaanModel;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->usersModel = new UsersModel();
        $this->pembacaanModel = new PembacaanModel();
        session()->set('previous_url', previous_url());
    }

    public function index_exam()
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
        return view('exam/index_exam', [
            'hpaData' => $hpaData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
    }
    // Menampilkan halaman daftar exam
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
        return view('exam/index_buku_penerima', [
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
        return redirect()->to('exam/index_buku_penerima')->with('success', 'Penerima berhasil disimpan.');
    }

    // Menampilkan form edit exam
    public function edit_exam($id_hpa)
    {
        session()->set('previous_url', previous_url());
        $hpaModel = new HpaModel();

        // Ambil id_user dan nama_user dari session yang sedang aktif
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);

        // Jika hpa ditemukan, tampilkan form edit
        if ($hpa) {
            // Menggabungkan data hpa dengan session data
            $data['hpa'] = $hpa;
            // Kirimkan data ke view
            return view('exam/edit_exam', $data);
        } else {
            // Jika tidak ditemukan, tampilkan pesan error
            return redirect()->back()->withInput()->with('message', [
                'error' => 'hpa tidak ditemukan.'
            ]);
        }
    }

    // Menampilkan form edit exam
    public function edit_makroskopis($id_hpa)
    {
        session()->set('previous_url', previous_url());
        $hpaModel = new HpaModel();
        $userModel = new UsersModel();
        $pemotonganModel = new PemotonganModel(); // Model Pemotongan

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }

        // Ambil id_pemotongan dari data hpa
        $id_pemotongan = $hpa['id_pemotongan'];
        // Ambil data pemotongan berdasarkan id_pemotongan
        $pemotongan = $pemotonganModel->find($id_pemotongan);
        // Ambil data pengguna dengan status "Dokter" dari tabel users
        $users = $userModel->where('status_user', 'Dokter')->findAll();

        // Ambil nama dokter dari pemotongan berdasarkan id_user_dokter_pemotongan
        if ($pemotongan && !empty($pemotongan['id_user_dokter_pemotongan'])) {
            $dokter = $userModel->find($pemotongan['id_user_dokter_pemotongan']);
            $pemotongan['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
        } else {
            $pemotongan['dokter_nama'] = null;
        }

        // Persiapkan data yang akan dikirim ke view
        $data = [
            'hpa' => $hpa,               // Data HPA
            'pemotongan' => $pemotongan, // Data Pemotongan
            'users' => $users,           // Data Pengguna (Dokter)
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        // Kirimkan data ke view
        return view('exam/edit_makroskopis', $data);
    }

    // Menampilkan form edit exam
    public function edit_mikroskopis($id_hpa)
    {
        session()->set('previous_url', previous_url());
        $hpaModel = new HpaModel();
        $userModel = new UsersModel();
        $pemotonganModel = new PemotonganModel();

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }

        // Ambil id_pemotongan dari data hpa
        $id_pemotongan = $hpa['id_pemotongan'];

        // Ambil data pemotongan berdasarkan id_pemotongan
        $pemotongan = $pemotonganModel->find($id_pemotongan);

        // Ambil data pengguna dengan status "Dokter" dari tabel users
        $users = $userModel->where('status_user', 'Dokter')->findAll();

        // Ambil nama dokter dari pemotongan berdasarkan id_user_dokter_pemotongan
        if ($pemotongan && !empty($pemotongan['id_user_dokter_pemotongan'])) {
            $dokter = $userModel->find($pemotongan['id_user_dokter_pemotongan']);
            $pemotongan['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
        } else {
            $pemotongan['dokter_nama'] = null;
        }

        // Persiapkan data yang akan dikirim ke view
        $data = [
            'hpa' => $hpa,               // Data HPA
            'pemotongan' => $pemotongan, // Data Pemotongan
            'users' => $users,           // Data Pengguna (Dokter)
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        // Kirimkan data ke view
        return view('exam/edit_mikroskopis', $data);
    }

    public function edit_penulisan($id_hpa)
    {
        
        $hpaModel = new HpaModel();
        $userModel = new UsersModel();
        $pemotonganModel = new PemotonganModel();
        
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);

        // Ambil id_pemotongan dari data hpa
        $id_pemotongan = $hpa['id_pemotongan'];
        // Ambil data pemotongan berdasarkan id_pemotongan
        $pemotongan = $pemotonganModel->find($id_pemotongan);
        // Ambil data pengguna dengan status "Dokter" dari tabel users
        $users = $userModel->where('status_user', 'Dokter')->findAll();

        // Ambil nama dokter dari pemotongan berdasarkan id_user_dokter_pemotongan
        if ($pemotongan && !empty($pemotongan['id_user_dokter_pemotongan'])) {
            $dokter = $userModel->find($pemotongan['id_user_dokter_pemotongan']);
            $pemotongan['dokter_nama'] = $dokter ? $dokter['nama_user'] : null;
        } else {
            $pemotongan['dokter_nama'] = null;
        }

        // Persiapkan data yang akan dikirim ke view
        $data = [
            'hpa' => $hpa,               // Data HPA
            'pemotongan' => $pemotongan, // Data Pemotongan
            'users' => $users,           // Data Pengguna (Dokter)
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('exam/edit_penulisan', $data);

    }

    public function edit_print_hpa($id_hpa)
    {
        session()->set('previous_url', previous_url());
        $hpaModel = new HpaModel();
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithAllRelations($id_hpa);

        // Jika hpa ditemukan, tampilkan form edit
        if ($hpa) {
            // Menggabungkan data hpa dengan session data
            $data['hpa'] = $hpa;
            // Kirimkan data ke view

            return view('exam/edit_print_hpa', $data);
        } else {
            // Jika tidak ditemukan, tampilkan pesan error
            return redirect()->back()->withInput()->with('message', [
                'error' => 'hpa tidak ditemukan.'
            ]);
        }
    }

    public function update($id_hpa)
    {
        // Inisialisasi model
        $hpaModel = new HpaModel();
        $pemotonganModel = new PemotonganModel();

        // Mendapatkan id_hpa dari POST
        $id_hpa = $this->request->getPost('id_hpa');
        
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
            dd($validation->getErrors());
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Mengambil data dari form
        $data = $this->request->getPost();

        // Mengubah 'jumlah_slide' jika memilih 'lainnya'
        if ($this->request->getPost('jumlah_slide') === 'lainnya') {
            $data['jumlah_slide'] = $this->request->getPost('jumlah_slide_custom');
        }

        // Update data pada tabel HPA
        if ($hpaModel->update($id_hpa, $data)) {
            // Menyimpan status pembaruan di session
            session()->setFlashdata('update_success', true);

            // Ambil id_user_dokter_pemotongan dan update tabel pemotongan jika ada
            $id_user_dokter_pemotongan = $this->request->getPost('id_user_dokter_pemotongan');

            // Jika ada id_user_dokter_pemotongan yang diterima dari form, lakukan pembaruan di tabel pemotongan
            if (!empty($id_user_dokter_pemotongan)) {
                // Ambil id_pemotongan berdasarkan id_hpa
                $pemotongan = $pemotonganModel->where('id_hpa', $id_hpa)->first();

                if ($pemotongan) {
                    // Lakukan update data di tabel pemotongan
                    $pemotonganModel->update($pemotongan['id_pemotongan'], [
                        'id_user_dokter_pemotongan' => $id_user_dokter_pemotongan
                    ]);
                }
            }

            $previousUrl = session()->get('previous_url');
            if ($previousUrl) {
                // Jika ada previous URL, redirect ke URL tersebut
                return redirect()->back()->withInput()->with('success', 'Data berhasil diperbarui.');
            } else {
                // Jika tidak ada previous URL, arahkan ke halaman default
                return redirect()->to('/')->with('success', 'Data berhasil diperbarui.');
            }
        }
    }
    public function update_penulisan($id_hpa)
    {
        // Inisialisasi model
        $hpaModel = new HpaModel();
        $pemotonganModel = new PemotonganModel();

        // Mendapatkan id_hpa dari POST
        $id_hpa = $this->request->getPost('id_hpa');

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
            dd($validation->getErrors());
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Mengambil data dari form
        $data = $this->request->getPost();

        // Mengubah 'jumlah_slide' jika memilih 'lainnya'
        if ($this->request->getPost('jumlah_slide') === 'lainnya') {
            $data['jumlah_slide'] = $this->request->getPost('jumlah_slide_custom');
        }

        // Update data pada tabel HPA
        if ($hpaModel->update($id_hpa, $data)) {
            // Menyimpan status pembaruan di session
            session()->setFlashdata('update_success', true);

            // Ambil id_user_dokter_pemotongan dan update tabel pemotongan jika ada
            $id_user_dokter_pemotongan = $this->request->getPost('id_user_dokter_pemotongan');

            // Jika ada id_user_dokter_pemotongan yang diterima dari form, lakukan pembaruan di tabel pemotongan
            if (!empty($id_user_dokter_pemotongan)) {
                // Ambil id_pemotongan berdasarkan id_hpa
                $pemotongan = $pemotonganModel->where('id_hpa', $id_hpa)->first();

                if ($pemotongan) {
                    // Lakukan update data di tabel pemotongan
                    $pemotonganModel->update($pemotongan['id_pemotongan'], [
                        'id_user_dokter_pemotongan' => $id_user_dokter_pemotongan
                    ]);
                }
            }
                return redirect()->to('penulisan/index_penulisan')->with('success', 'Data berhasil diperbarui.');
        }
    }

    public function uploadFotoMakroskopis($id_hpa)
    {
        session()->set('previous_url', previous_url());
        date_default_timezone_set('Asia/Jakarta');
        $hpaModel = new HpaModel();

        // Ambil data HPA untuk mendapatkan nama file lama
        $hpa = $hpaModel->find($id_hpa);

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
            $newFileName = date('HisdmY') . '.' . $file->getExtension();

            // Tentukan folder tujuan upload
            $uploadPath = ROOTPATH . 'public/uploads/hpa/makroskopis/';
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Membuat folder jika belum ada
            }

            // Hapus file lama jika ada
            if (!empty($hpa['foto_makroskopis_hpa'])) {
                $oldFilePath = $uploadPath . $hpa['foto_makroskopis_hpa'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Pindahkan file baru ke folder tujuan dengan nama baru
            if ($file->move($uploadPath, $newFileName)) {
                // Update nama file baru di database
                $hpaModel->update($id_hpa, ['foto_makroskopis_hpa' => $newFileName]);

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

    public function uploadFotoMikroskopis($id_hpa)
    {
        session()->set('previous_url', previous_url());
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
        return redirect()->to('exam/index_exam')->with('success', 'Status HPA berhasil disimpan.');
    }

    public function register_exam()
    {
        // Ambil data kode HPA terakhir dari model HpaModel
        $lastHPA = $this->hpaModel->getLastKodeHPA();

        // Ambil tahun saat ini
        $currentYear = date('y');
        $nextNumber = 53;

        // Jika ada data kode HPA terakhir
        if ($lastHPA) {
            // Ambil kode HPA terakhir dan pisahkan berdasarkan format 'H.XX/YY'
            $lastKode = $lastHPA['kode_hpa']; 
            $lastParts = explode('/', $lastKode);
            $lastYear = $lastParts[1]; 

            // Jika tahun kode HPA terakhir sama dengan tahun sekarang
            if ($lastYear == $currentYear) {
                $lastNumber = (int) explode('.', $lastParts[0])[1];
                $nextNumber = $lastNumber + 1;
            }
        }

        // Format kode HPA baru
        $kodeHPA = 'H.' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT) . '/' . $currentYear;

        // Siapkan data untuk dikirim ke view
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'kode_hpa' => $kodeHPA,
            'patient' => null,
        ];

        // Mendapatkan nilai norm_pasien dari query string
        $norm_pasien = $this->request->getGet('norm_pasien');

        // Jika norm_pasien ada, cari data pasien berdasarkan norm_pasien
        if ($norm_pasien) {
            $patientModel = new PatientModel();
            $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();
            $data['patient'] = $patient ?: null;
        }
        // Kirim data ke view
        return view('exam/register_exam', $data);
    }

    public function insert()
    {
        try {
            // Validasi data input hanya untuk kode_hpa
            $validation = \Config\Services::validation();
            $validation->setRules([
                'kode_hpa' => [
                    'rules' => 'required|is_unique[hpa.kode_hpa]',
                    'errors' => [
                        'required' => 'Kode Hpa harus diisi.',
                        'is_unique' => 'Kode Hpa sudah terdaftar!',
                    ],
                ],
            ]);

            // Jika validasi gagal, kembalikan ke form dengan error
            if (!$validation->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            // Ambil data dari form
            $data = [
                'kode_hpa'           => $this->request->getPost('kode_hpa'),
                'id_pasien'          => $this->request->getPost('id_pasien'),
                'unit_asal'          => $this->request->getPost('unit_asal') . ' ' . $this->request->getPost('unit_asal_detail'),
                'dokter_pengirim' => $this->request->getPost('dokter_pengirim_custom')
                    ? $this->request->getPost('dokter_pengirim_custom')
                    : $this->request->getPost('dokter_pengirim'),
                'tanggal_permintaan' => $this->request->getPost('tanggal_permintaan') ?: null,
                'tanggal_hasil'      => $this->request->getPost('tanggal_hasil') ?: null,
                'lokasi_spesimen'    => $this->request->getPost('lokasi_spesimen'),
                'tindakan_spesimen' => $this->request->getPost('tindakan_spesimen_custom')
                    ? $this->request->getPost('tindakan_spesimen_custom')
                    : $this->request->getPost('tindakan_spesimen'),
                'diagnosa_klinik'    => $this->request->getPost('diagnosa_klinik'),
                'status_hpa'         => $this->request->getPost('status_hpa'),
            ];

            // Data untuk tabel HPA
            $hpaModel = new HpaModel();
            $hpaData = [
                'kode_hpa'           => $data['kode_hpa'],
                'id_pasien'          => $data['id_pasien'],
                'unit_asal'          => $data['unit_asal'],
                'dokter_pengirim'    => $data['dokter_pengirim'],
                'tanggal_permintaan' => $data['tanggal_permintaan'],
                'tanggal_hasil'      => $data['tanggal_hasil'],
                'lokasi_spesimen'    => $data['lokasi_spesimen'],
                'tindakan_spesimen'  => $data['tindakan_spesimen'],
                'diagnosa_klinik'    => $data['diagnosa_klinik'],
                'status_hpa'         => $data['status_hpa'],
            ];

            // Simpan data HPA terlebih dahulu
            if (!$hpaModel->insert($hpaData)) {
                throw new Exception('Gagal menyimpan data HPA.');
            }

            // Ambil id_hpa yang baru saja disimpan
            $id_hpa = $hpaModel->getInsertID();

            // Data untuk tabel penerimaan
            $penerimaanData = [
                'id_hpa'            => $id_hpa,
                'status_penerimaan' => 'Belum Diperiksa',
            ];

            // Simpan data ke tabel Penerimaan
            $penerimaanModel = new PenerimaanModel();
            if (!$penerimaanModel->insert($penerimaanData)) {
                throw new Exception('Gagal menyimpan data penerimaan.');
            }

            // Ambil id_penerimaan yang baru saja disimpan
            $id_penerimaan = $penerimaanModel->getInsertID();

            // Update id_penerimaan pada tabel hpa
            $hpaModel->update($id_hpa, ['id_penerimaan' => $id_penerimaan]);

            // Data untuk tabel mutu
            $mutuData = [
                'id_hpa'            => $id_hpa,  // Menambahkan id_hpa yang baru
            ];

            // Simpan data ke tabel mutu
            $mutuModel = new MutuModel();
            if (!$mutuModel->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu.');
            }

            // Ambil id_mutu yang baru saja disimpan
            $id_mutu = $mutuModel->getInsertID();

            // Update id_mutu pada tabel hpa
            $hpaModel->update($id_hpa, ['id_mutu' => $id_mutu]);

            // Jika berhasil, redirect ke halaman dashboard
            return redirect()->to('/dashboard')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->back()->withInput()->with('error', $e->getMessage());
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
}
