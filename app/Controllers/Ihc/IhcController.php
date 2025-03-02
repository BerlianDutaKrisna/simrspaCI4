<?php

namespace App\Controllers\Ihc;

use App\Controllers\BaseController;
use App\Models\Ihc\ihcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
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
    protected $ihcModel;
    protected $usersModel;
    protected $patientModel;
    protected $Penerimaan_ihc;
    protected $Pemotongan_ihc;
    protected $Pembacaan_ihc;
    protected $Penulisan_ihc;
    protected $Pemverifikasi_ihc;
    protected $Authorized_ihc;
    protected $Pencetakan_ihc;
    protected $Mutu_ihc;
    protected $validation;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->usersModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->Penerimaan_ihc = new Penerimaan_ihc();;
        $this->Pembacaan_ihc = new Pembacaan_ihc();
        $this->Penulisan_ihc = new Penulisan_ihc();
        $this->Pemverifikasi_ihc = new Pemverifikasi_ihc();
        $this->Authorized_ihc = new Authorized_ihc();
        $this->Pencetakan_ihc = new Pencetakan_ihc();
        $this->Mutu_ihc = new Mutu_ihc();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        // Mengambil data dari session
        $session = session();
        $id_user = $session->get('id_user');
        $nama_user = $session->get('nama_user');

        // Memastikan session terisi dengan benar
        if (!$id_user || !$nama_user) {
            return redirect()->to('login'); // Redirect ke halaman login jika session tidak ada
        }

        // Memanggil model ihcModel untuk mengambil data
        $ihcModel = new ihcModel();
        $ihcData = $ihcModel->getihcWithAllPatient();

        // Pastikan $ihcData berisi array
        if (!$ihcData) {
            $ihcData = []; // Jika tidak ada data, set menjadi array kosong
        }
        return view('ihc/index_ihc', [
            'ihcData' => $ihcData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
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
            } else {
                $nextNumber = 1;
            }
        } else {
            $nextNumber = 1;
        }
        $kodeihc = 'IHC.' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT) . '/' . $currentYear;
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'kode_ihc' => $kodeihc,
            'patient' => null,
        ];
        $norm_pasien = $this->request->getGet('norm_pasien');
        if ($norm_pasien) {
            $patientModel = new PatientModel();
            $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();
            $data['patient'] = $patient ?: null;
        }
        return view('Ihc/Register', $data);
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
            $dokter_pengirim = !empty($data['dokter_pengirim']) ? $data['dokter_pengirim'] : $data['dokter_pengirim_custom'];
            // Tentukan tindakan_spesimen
            $tindakan_spesimen = !empty($data['tindakan_spesimen']) ? $data['tindakan_spesimen'] : $data['tindakan_spesimen_custom'];
            // Data yang akan disimpan
            $ihcData = [
                'kode_ihc' => $data['kode_ihc'],
                'id_pasien' => $data['id_pasien'],
                'unit_asal' => $unit_asal,
                'dokter_pengirim' => $dokter_pengirim,
                'tanggal_permintaan' => $data['tanggal_permintaan'] ?: null,
                'tanggal_hasil' => $data['tanggal_hasil'] ?: null,
                'lokasi_spesimen' => $data['lokasi_spesimen'],
                'tindakan_spesimen' => $tindakan_spesimen,
                'diagnosa_klinik' => $data['diagnosa_klinik'],
                'status_ihc' => 'Penerimaan',
            ];
            // Simpan data ihc
            if (!$this->ihcModel->insert($ihcData)) {
                throw new Exception('Gagal menyimpan data ihc: ' . $this->ihcModel->errors());
            }
            // Mendapatkan ID ihc yang baru diinsert
            $id_ihc = $this->ihcModel->getInsertID();
            // Data penerimaan
            $penerimaanData = [
                'id_ihc' => $id_ihc,
                'status_penerimaan_ihc' => 'Belum Penerimaan',
            ];
            // Simpan data penerimaan
            if (!$this->Penerimaan_ihc->insert($penerimaanData)) {
                throw new Exception('Gagal menyimpan data penerimaan: ' . $this->Penerimaan_ihc->errors());
            }
            // Data mutu
            $mutuData = [
                'id_ihc' => $id_ihc,
            ];
            if (!$this->Mutu_ihc->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu: ' . $this->Mutu_ihc->errors());
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
        $id_ihc = $this->request->getPost('id_ihc');

        // Cek apakah id_ihc valid
        if ($id_ihc) {
            // Inisialisasi model
            $ihcModel = new ihcModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel ihc
            $deleteihc = $ihcModel->delete($id_ihc);

            // Cek apakah delete berhasil
            if ($deleteihc) {
                // Selesaikan transaksi
                $db->transComplete();

                // Cek apakah transaksi berhasil
                if ($db->transStatus() === FALSE) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data.']);
                }
            } else {
                // Jika id_ihc tidak valid, kirimkan response error
                return $this->response->setJSON(['success' => false, 'message' => 'ID ihc tidak valid.']);
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

        // Memanggil model ihcModel untuk mengambil data
        $ihcModel = new ihcModel();
        $ihcData = $ihcModel->getihcWithAllPatient();

        // Pastikan $ihcData berisi array
        if (!$ihcData) {
            $ihcData = []; // Jika tidak ada data, set menjadi array kosong
        }

        // Kirimkan data ke view
        return view('ihc/index_buku_penerima', [
            'ihcData' => $ihcData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
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
    

    public function update_print_ihc($id_ihc)
    {

        date_default_timezone_set('Asia/Jakarta');
        $ihcModel = new ihcModel();
        $Pemverifikasi_ihc = new Pemverifikasi_ihc();
        $Authorized_ihc = new Authorized_ihc();
        $Pencetakan_ihc = new Pencetakan_ihc();

        $id_user = session()->get('id_user');

        // Mendapatkan id_ihc dari POST
        if (!$id_ihc) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID ihc tidak ditemukan.');
        }

        // Mengambil data dari POST dan melakukan update
        $data = $this->request->getPost();
        $ihcModel->update($id_ihc, $data);

        $redirect = $this->request->getPost('redirect');

        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }

        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'index_pemverifikasi' && isset($_POST['id_pemverifikasi'])) {
            $id_pemverifikasi = $this->request->getPost('id_pemverifikasi');
            $Pemverifikasi_ihc->updatePemverifikasi($id_pemverifikasi, [
                'id_user_pemverifikasi' => $id_user,
                'status_pemverifikasi' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi/index_pemverifikasi')->with('success', 'Data berhasil diverifikasi.');
        }

        if ($redirect === 'index_autorized' && isset($_POST['id_autorized'])) {
            $id_autorized = $this->request->getPost('id_autorized');
            $Authorized_ihc->updateAutorized($id_autorized, [
                'id_user_autorized' => $id_user,
                'status_autorized' => 'Selesai Authorized',
                'selesai_autorized' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('autorized/index_autorized')->with('success', 'Data berhasil diauthorized.');
        }

        if ($redirect === 'index_pencetakan' && isset($_POST['id_pencetakan'])) {
            $id_pencetakan = $this->request->getPost('id_pencetakan');
            $Pencetakan_ihc->updatePencetakan($id_pencetakan, [
                'id_user_pencetakan' => $id_user,
                'status_pencetakan' => 'Selesai Pencetakan',
                'selesai_pencetakan' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan/index_pencetakan')->with('success', 'Data berhasil dicetak.');
        }

        // Jika redirect tidak sesuai dengan yang diharapkan
        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
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

    public function update_status_ihc()
    {
        $id_ihc = $this->request->getPost('id_ihc');
        // Inisialisasi model
        $ihcModel = new ihcModel();

        // Mengambil data dari form
        $status_ihc = $this->request->getPost('status_ihc');

        // Data yang akan diupdate
        $data = [
            'status_ihc' => $status_ihc,
        ];

        // Update data status_ihc berdasarkan id_ihc
        $ihcModel->updateStatusihc($id_ihc, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('ihc/index_ihc')->with('success', 'Status ihc berhasil disimpan.');
    }
}