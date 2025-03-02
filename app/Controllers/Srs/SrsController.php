<?php

namespace App\Controllers\Srs;

use App\Controllers\BaseController;
use App\Models\Srs\srsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Srs\Proses\Penerimaan_srs;
use App\Models\Srs\Proses\Pembacaan_srs;
use App\Models\Srs\Proses\Penulisan_srs;
use App\Models\Srs\Proses\Pemverifikasi_srs;
use App\Models\Srs\Proses\Authorized_srs;
use App\Models\Srs\Proses\Pencetakan_srs;
use App\Models\Srs\Mutu_srs;
use Exception;

class srsController extends BaseController
{
    protected $srsModel;
    protected $usersModel;
    protected $patientModel;
    protected $Penerimaan_srs;
    protected $Pemotongan_srs;
    protected $Pembacaan_srs;
    protected $Penulisan_srs;
    protected $Pemverifikasi_srs;
    protected $Authorized_srs;
    protected $Pencetakan_srs;
    protected $Mutu_srs;
    protected $validation;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->usersModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->Penerimaan_srs = new Penerimaan_srs();;
        $this->Pembacaan_srs = new Pembacaan_srs();
        $this->Penulisan_srs = new Penulisan_srs();
        $this->Pemverifikasi_srs = new Pemverifikasi_srs();
        $this->Authorized_srs = new Authorized_srs();
        $this->Pencetakan_srs = new Pencetakan_srs();
        $this->Mutu_srs = new Mutu_srs();
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

        // Memanggil model srsModel untuk mengambil data
        $srsModel = new srsModel();
        $srsData = $srsModel->getsrsWithAllPatient();

        // Pastikan $srsData berisi array
        if (!$srsData) {
            $srsData = []; // Jika tidak ada data, set menjadi array kosong
        }
        return view('srs/index_srs', [
            'srsData' => $srsData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
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
            } else {
                $nextNumber = 1;
            }
        } else {
            $nextNumber = 1;
        }
        $kodesrs = 'SRS.' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT) . '/' . $currentYear;
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'kode_srs' => $kodesrs,
            'patient' => null,
        ];
        $norm_pasien = $this->request->getGet('norm_pasien');
        if ($norm_pasien) {
            $patientModel = new PatientModel();
            $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();
            $data['patient'] = $patient ?: null;
        }
        return view('Srs/Register', $data);
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
            $dokter_pengirim = !empty($data['dokter_pengirim']) ? $data['dokter_pengirim'] : $data['dokter_pengirim_custom'];
            // Tentukan tindakan_spesimen
            $tindakan_spesimen = !empty($data['tindakan_spesimen']) ? $data['tindakan_spesimen'] : $data['tindakan_spesimen_custom'];
            // Data yang akan disimpan
            $srsData = [
                'kode_srs' => $data['kode_srs'],
                'id_pasien' => $data['id_pasien'],
                'unit_asal' => $unit_asal,
                'dokter_pengirim' => $dokter_pengirim,
                'tanggal_permintaan' => $data['tanggal_permintaan'] ?: null,
                'tanggal_hasil' => $data['tanggal_hasil'] ?: null,
                'lokasi_spesimen' => $data['lokasi_spesimen'],
                'tindakan_spesimen' => $tindakan_spesimen,
                'diagnosa_klinik' => $data['diagnosa_klinik'],
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
            if (!$this->Penerimaan_srs->insert($penerimaanData)) {
                throw new Exception('Gagal menyimpan data penerimaan: ' . $this->Penerimaan_srs->errors());
            }
            // Data mutu
            $mutuData = [
                'id_srs' => $id_srs,
            ];
            if (!$this->Mutu_srs->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu: ' . $this->Mutu_srs->errors());
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
        $id_srs = $this->request->getPost('id_srs');

        // Cek apakah id_srs valid
        if ($id_srs) {
            // Inisialisasi model
            $srsModel = new srsModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel srs
            $deletesrs = $srsModel->delete($id_srs);

            // Cek apakah delete berhasil
            if ($deletesrs) {
                // Selesaikan transaksi
                $db->transComplete();

                // Cek apakah transaksi berhasil
                if ($db->transStatus() === FALSE) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data.']);
                }
            } else {
                // Jika id_srs tidak valid, kirimkan response error
                return $this->response->setJSON(['success' => false, 'message' => 'ID srs tidak valid.']);
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

        // Memanggil model srsModel untuk mengambil data
        $srsModel = new srsModel();
        $srsData = $srsModel->getsrsWithAllPatient();

        // Pastikan $srsData berisi array
        if (!$srsData) {
            $srsData = []; // Jika tidak ada data, set menjadi array kosong
        }

        // Kirimkan data ke view
        return view('srs/index_buku_penerima', [
            'srsData' => $srsData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
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
    

    public function update_print_srs($id_srs)
    {

        date_default_timezone_set('Asia/Jakarta');
        $srsModel = new srsModel();
        $Pemverifikasi_srs = new Pemverifikasi_srs();
        $Authorized_srs = new Authorized_srs();
        $Pencetakan_srs = new Pencetakan_srs();

        $id_user = session()->get('id_user');

        // Mendapatkan id_srs dari POST
        if (!$id_srs) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID srs tidak ditemukan.');
        }

        // Mengambil data dari POST dan melakukan update
        $data = $this->request->getPost();
        $srsModel->update($id_srs, $data);

        $redirect = $this->request->getPost('redirect');

        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }

        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'index_pemverifikasi' && isset($_POST['id_pemverifikasi'])) {
            $id_pemverifikasi = $this->request->getPost('id_pemverifikasi');
            $Pemverifikasi_srs->updatePemverifikasi($id_pemverifikasi, [
                'id_user_pemverifikasi' => $id_user,
                'status_pemverifikasi' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi/index_pemverifikasi')->with('success', 'Data berhasil diverifikasi.');
        }

        if ($redirect === 'index_autorized' && isset($_POST['id_autorized'])) {
            $id_autorized = $this->request->getPost('id_autorized');
            $Authorized_srs->updateAutorized($id_autorized, [
                'id_user_autorized' => $id_user,
                'status_autorized' => 'Selesai Authorized',
                'selesai_autorized' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('autorized/index_autorized')->with('success', 'Data berhasil diauthorized.');
        }

        if ($redirect === 'index_pencetakan' && isset($_POST['id_pencetakan'])) {
            $id_pencetakan = $this->request->getPost('id_pencetakan');
            $Pencetakan_srs->updatePencetakan($id_pencetakan, [
                'id_user_pencetakan' => $id_user,
                'status_pencetakan' => 'Selesai Pencetakan',
                'selesai_pencetakan' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan/index_pencetakan')->with('success', 'Data berhasil dicetak.');
        }

        // Jika redirect tidak sesuai dengan yang diharapkan
        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
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
        preg_match('/H\.(\d+)\/\d+/', $kode_srs, $matches);
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

    public function update_status_srs()
    {
        $id_srs = $this->request->getPost('id_srs');
        // Inisialisasi model
        $srsModel = new srsModel();

        // Mengambil data dari form
        $status_srs = $this->request->getPost('status_srs');

        // Data yang akan diupdate
        $data = [
            'status_srs' => $status_srs,
        ];

        // Update data status_srs berdasarkan id_srs
        $srsModel->updateStatussrs($id_srs, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('srs/index_srs')->with('success', 'Status srs berhasil disimpan.');
    }
}