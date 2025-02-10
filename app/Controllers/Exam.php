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
use App\Models\ProsesModel\AutorizedModel;
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

        // Inisialisasi dokter dan analis
        $dokter_nama = null;
        $analis_nama = null;

        if ($pemotongan) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pemotongan['id_user_dokter_pemotongan'])) {
                $dokter = $userModel->find($pemotongan['id_user_dokter_pemotongan']);
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

        // Ambil data pengguna dengan status "Dokter" dari tabel users
        $users = $userModel->where('status_user', 'Dokter')->findAll();

        // Ambil data session di awal
        $session = session();

        // Persiapkan data yang akan dikirim ke view
        $data = [
            'hpa' => $hpa,               // Data HPA
            'pemotongan' => $pemotongan, // Data Pemotongan
            'users' => $users,           // Data Pengguna (Dokter)
            'id_user' => $session->get('id_user'),
            'nama_user' => $session->get('nama_user'),
        ];

        return view('exam/edit_makroskopis', $data);
    }

    // Menampilkan form edit exam
    public function edit_mikroskopis($id_hpa)
    {
        $hpaModel = new HpaModel();
        $userModel = new UsersModel();
        $pemotonganModel = new PemotonganModel();
        $pembacaanModel = new PembacaanModel();
        $mutuModel = new MutuModel();

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('message', ['error' => 'HPA tidak ditemukan.']);
        }

        // Ambil id_pemotongan dan id_pembacaan dari data hpa
        $id_pemotongan = $hpa['id_pemotongan'];
        $id_pembacaan = $hpa['id_pembacaan'];
        $id_mutu = $hpa['id_mutu'];
        

        // Ambil data pemotongan dan pembacaan berdasarkan ID
        $pemotongan = $pemotonganModel->find($id_pemotongan);
        $pembacaan = $pembacaanModel->find($id_pembacaan);
        $mutu = $mutuModel->find($id_mutu);

        // Inisialisasi dokter dan analis
        $dokter_nama = null;
        $analis_nama = null;

        if ($pemotongan) {
            // Ambil nama dokter dan analis jika ID tersedia
            if (!empty($pemotongan['id_user_dokter_pemotongan'])) {
                $dokter = $userModel->find($pemotongan['id_user_dokter_pemotongan']);
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

        // Pastikan mutu tidak kosong sebelum mengambil indikator
        $indikator_4 = $mutu ? $mutu['indikator_4'] : "0";
        $indikator_5 = $mutu ? $mutu['indikator_5'] : "0";
        $indikator_6 = $mutu ? $mutu['indikator_6'] : "0";
        $indikator_7 = $mutu ? $mutu['indikator_7'] : "0";
        $indikator_8 = $mutu ? $mutu['indikator_8'] : "0";
        $total_nilai_mutu = $mutu ? $mutu['total_nilai_mutu'] : "0";

        // Tambahkan nilai indikator ke dalam pembacaan
        $pembacaan['indikator_4'] = $indikator_4;
        $pembacaan['indikator_5'] = $indikator_5;
        $pembacaan['indikator_6'] = $indikator_6;
        $pembacaan['indikator_7'] = $indikator_7;
        $pembacaan['indikator_8'] = $indikator_8;
        $pembacaan['total_nilai_mutu'] = $total_nilai_mutu;
        // Ambil data pengguna dengan status "Dokter" dari tabel users
        $users = $userModel->where('status_user', 'Dokter')->findAll();

        // Persiapkan data yang akan dikirim ke view
        $data = [
            'hpa' => $hpa,               // Data HPA
            'pemotongan' => $pemotongan, // Data Pemotongan
            'pembacaan' => $pembacaan,   // Data Pembacaan
            'users' => $users,           // Data Pengguna (Dokter)
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        
        return view('exam/edit_mikroskopis', $data);
    }

    public function edit_penulisan($id_hpa)
    {

        $hpaModel = new HpaModel();
        $userModel = new UsersModel();
        $pemotonganModel = new PemotonganModel();
        $penulisanModel = new PenulisanModel();

        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);

        // Ambil id_pemotongan dari data hpa
        $id_pemotongan = $hpa['id_pemotongan'];
        $id_penulisan = $hpa['id_penulisan'];

        $pemotongan = $pemotonganModel->find($id_pemotongan);
        $penulisan = $penulisanModel->find($id_penulisan);

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
            'hpa' => $hpa,               
            'pemotongan' => $pemotongan,
            'penulisan' => $penulisan,
            'users' => $users,           
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];

        return view('exam/edit_penulisan', $data);
    }

    public function edit_print_hpa($id_hpa)
    {
        $hpaModel = new HpaModel();
        $penerimaanModel = new PenerimaanModel();
        $pembacaanModel = new PembacaanModel();
        $pemverifikasiModel = new PemverifikasiModel();
        $autorizedModel = new AutorizedModel();
        $pencetakanModel = new PencetakanModel();

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithPatient($id_hpa);

        // Ambil id_pemotongan dari data hpa
        $id_penerimaan = $hpa['id_penerimaan'];
        $id_pembacaan = $hpa['id_pembacaan'];
        $id_pemverivifikasi = $hpa['id_pemverifikasi'];
        $id_autorized = $hpa['id_autorized'];
        $id_pencetakan = $hpa['id_pencetakan'];

        $penerimaan = $penerimaanModel->find($id_penerimaan);
        $pembacaan = $pembacaanModel->find($id_pembacaan);
        $pemverifikasi = $pemverifikasiModel->find($id_pemverivifikasi);
        $autorized = $autorizedModel->find($id_autorized);
        $pencetakan = $pencetakanModel->find($id_pencetakan);

        // Persiapkan data yang akan dikirim ke view
        $data = [
            'hpa' => $hpa,
            'penerimaan' => $penerimaan,
            'pembacaan' => $pembacaan,
            'pemverifikasi' => $pemverifikasi,
            'autorized' => $autorized,
            'pencetakan' => $pencetakan,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        
        return view('exam/edit_print_hpa', $data);
    }

    public function update($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');
        // Inisialisasi model
        $hpaModel = new HpaModel();
        $pemotonganModel = new PemotonganModel();
        $pembacaanModel = new PembacaanModel();
        $penulisanModel = new PenulisanModel();
        $mutuModel = new MutuModel();
        // Mendapatkan ID user dari session
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
        if ($hpaModel->update($id_hpa, $data)) {
            // Update data pemotongan jika id_user_dokter_pemotongan ada
            if (!empty($data['id_user_dokter_pemotongan'])) {
                $pemotongan = $pemotonganModel->where('id_hpa', $id_hpa)->first();

                if ($pemotongan) {
                    $pemotonganModel->update($pemotongan['id_pemotongan'], [
                        'id_user_dokter_pemotongan' => $data['id_user_dokter_pemotongan'],
                    ]);
                }
            }

            switch ($page_source) {
                case 'edit_makroskopis':
                    $id_pemotongan = $this->request->getPost('id_pemotongan');
                    $pemotonganModel->updatePemotongan($id_pemotongan, [
                        'id_user_pemotongan' => $id_user,
                        'status_pemotongan' => 'Selesai Pemotongan',
                        'selesai_pemotongan' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect()->to('exam/edit_makroskopis/' . $id_hpa)->with('success', 'Data makroskopis berhasil diperbarui.');

                case 'edit_mikroskopis':
                    $id_pembacaan = $this->request->getPost('id_pembacaan');
                    $id_user_dokter_pembacaan = $this->request->getPost('id_user_dokter_pemotongan');
                    $pembacaanModel->updatePembacaan($id_pembacaan, [
                        'id_user_pembacaan' => $id_user,
                        'id_user_dokter_pembacaan' => $id_user_dokter_pembacaan,
                        'status_pembacaan' => 'Selesai Pembacaan',
                        'selesai_pembacaan' => date('Y-m-d H:i:s'),
                    ]);
                    
                $id_mutu = $this->request->getPost('id_mutu');
                $indikator_4 = (string) ($this->request->getPost('indikator_4') ?? '0');
                $indikator_5 = (string) ($this->request->getPost('indikator_5') ?? '0');
                $indikator_6 = (string) ($this->request->getPost('indikator_6') ?? '0');
                $indikator_7 = (string) ($this->request->getPost('indikator_7') ?? '0');
                $indikator_8 = (string) ($this->request->getPost('indikator_8') ?? '0');
                $total_nilai_mutu = (string) ($this->request->getPost('total_nilai_mutu') ?? '0');
                $keseluruhan_nilai_mutu = $total_nilai_mutu + (int)$indikator_5 + (int)$indikator_6 + (int)$indikator_7 + (int)$indikator_8;
                $mutuModel->updateMutu($id_mutu, [
                    'indikator_4' => $indikator_4,
                    'indikator_5' => $indikator_5,
                    'indikator_6' => $indikator_6,
                    'indikator_7' => $indikator_7,
                    'indikator_8' => $indikator_8,
                    'total_nilai_mutu' => $keseluruhan_nilai_mutu,
                ]);
                    return redirect()->to('exam/edit_mikroskopis/' . $id_hpa)->with('success', 'Data mikroskopis berhasil diperbarui.');

                case 'edit_penulisan':
                    $id_penulisan = $this->request->getPost('id_penulisan');
                    $penulisanModel->updatePenulisan($id_penulisan, [
                        'id_user_penulisan' => $id_user,
                        'status_penulisan' => 'Selesai Penulisan',
                        'selesai_penulisan' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect()->to('exam/edit_penulisan/' . $id_hpa)->with('success', 'Data penulisan berhasil diperbarui.');

                default:
                    return redirect()->back()->with('success', 'Data berhasil diperbarui.');
            }
        }
        return redirect()->back()->with('error', 'Gagal memperbarui data.');
    }

    public function update_print_hpa($id_hpa){
        $hpaModel = new HpaModel();
        $pemverifikasiModel = new PemverifikasiModel();
        $autorizedModel = new AutorizedModel();
        $pencetakanModel = new PencetakanModel();

        $id_user = session()->get('id_user');

        // Mendapatkan id_hpa dari POST
        if (!$id_hpa) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID HPA tidak ditemukan.');
        }

        // Mengambil data dari POST dan melakukan update
        $data = $this->request->getPost();
        $hpaModel->update($id_hpa, $data);

        $redirect = $this->request->getPost('redirect');

        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }

        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'index_pemverifikasi' && isset($_POST['id_pemverifikasi'])) {
            $id_pemverifikasi = $this->request->getPost('id_pemverifikasi');
            $pemverifikasiModel->updatePemverifikasi($id_pemverifikasi, [
                'id_user_pemverifikasi' => $id_user,
                'status_pemverifikasi' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi/index_pemverifikasi')->with('success', 'Data berhasil diverifikasi.');
        }

        if ($redirect === 'index_autorized' && isset($_POST['id_autorized'])) {
            $id_autorized = $this->request->getPost('id_autorized');
            $autorizedModel->updateAutorized($id_autorized, [
                'id_user_autorized' => $id_user,
                'status_autorized' => 'Selesai Authorized',
                'selesai_autorized' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('autorized/index_autorized')->with('success', 'Data berhasil diauthorized.');
        }

        if ($redirect === 'index_pencetakan' && isset($_POST['id_pencetakan'])) {
            $id_pencetakan = $this->request->getPost('id_pencetakan');
            $pencetakanModel->updatePencetakan($id_pencetakan, [
                'id_user_pencetakan' => $id_user,
                'status_pencetakan' => 'Selesai Pencetakan',
                'selesai_pencetakan' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan/index_pencetakan')->with('success', 'Data berhasil dicetak.');
        }

        // Jika redirect tidak sesuai dengan yang diharapkan
        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
    }


    public function uploadFotoMakroskopis($id_hpa)
    {
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
        $nextNumber = 1;

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
            } else {
                // Jika tahun berbeda, nomor dimulai kembali dari 1
                $nextNumber = 1;
            }
        } else {
            // Jika tidak ada kode HPA sebelumnya, mulai dari 1
            $nextNumber = 1;
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
                'status_penerimaan' => 'Belum Pemeriksaan',
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
