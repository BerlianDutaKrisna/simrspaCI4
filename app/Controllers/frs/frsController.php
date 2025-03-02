<?php

namespace App\Controllers\Frs;

use App\Controllers\BaseController;
use App\Models\Frs\FrsModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
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
    protected $frsModel;
    protected $usersModel;
    protected $patientModel;
    protected $Penerimaan_frs;
    protected $Pemotongan_frs;
    protected $Pembacaan_frs;
    protected $Penulisan_frs;
    protected $Pemverifikasi_frs;
    protected $Authorized_frs;
    protected $Pencetakan_frs;
    protected $Mutu_frs;
    protected $validation;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->usersModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->Penerimaan_frs = new Penerimaan_frs();;
        $this->Pembacaan_frs = new Pembacaan_frs();
        $this->Penulisan_frs = new Penulisan_frs();
        $this->Pemverifikasi_frs = new Pemverifikasi_frs();
        $this->Authorized_frs = new Authorized_frs();
        $this->Pencetakan_frs = new Pencetakan_frs();
        $this->Mutu_frs = new Mutu_frs();
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

        // Memanggil model frsModel untuk mengambil data
        $frsModel = new frsModel();
        $frsData = $frsModel->getfrsWithAllPatient();

        // Pastikan $frsData berisi array
        if (!$frsData) {
            $frsData = []; // Jika tidak ada data, set menjadi array kosong
        }
        return view('frs/index_frs', [
            'frsData' => $frsData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
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
            } else {
                $nextNumber = 1;
            }
        } else {
            $nextNumber = 1;
        }
        $kodefrs = 'FRS.' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT) . '/' . $currentYear;
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'kode_frs' => $kodefrs,
            'patient' => null,
        ];
        $norm_pasien = $this->request->getGet('norm_pasien');
        if ($norm_pasien) {
            $patientModel = new PatientModel();
            $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();
            $data['patient'] = $patient ?: null;
        }
        return view('Frs/Register', $data);
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
            $dokter_pengirim = !empty($data['dokter_pengirim']) ? $data['dokter_pengirim'] : $data['dokter_pengirim_custom'];
            // Tentukan tindakan_spesimen
            $tindakan_spesimen = !empty($data['tindakan_spesimen']) ? $data['tindakan_spesimen'] : $data['tindakan_spesimen_custom'];
            // Data yang akan disimpan
            $frsData = [
                'kode_frs' => $data['kode_frs'],
                'id_pasien' => $data['id_pasien'],
                'unit_asal' => $unit_asal,
                'dokter_pengirim' => $dokter_pengirim,
                'tanggal_permintaan' => $data['tanggal_permintaan'] ?: null,
                'tanggal_hasil' => $data['tanggal_hasil'] ?: null,
                'lokasi_spesimen' => $data['lokasi_spesimen'],
                'tindakan_spesimen' => $tindakan_spesimen,
                'diagnosa_klinik' => $data['diagnosa_klinik'],
                'status_frs' => 'Penerimaan',
            ];
            // Simpan data frs
            if (!$this->frsModel->insert($frsData)) {
                throw new Exception('Gagal menyimpan data frs: ' . $this->frsModel->errors());
            }
            // Mendapatkan ID frs yang baru diinsert
            $id_frs = $this->frsModel->getInsertID();
            // Data penerimaan
            $penerimaanData = [
                'id_frs' => $id_frs,
                'status_penerimaan_frs' => 'Belum Pemeriksaan',
            ];
            // Simpan data penerimaan
            if (!$this->Penerimaan_frs->insert($penerimaanData)) {
                throw new Exception('Gagal menyimpan data penerimaan: ' . $this->Penerimaan_frs->errors());
            }
            // Data mutu
            $mutuData = [
                'id_frs' => $id_frs,
            ];
            if (!$this->Mutu_frs->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu: ' . $this->Mutu_frs->errors());
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
        $id_frs = $this->request->getPost('id_frs');

        // Cek apakah id_frs valid
        if ($id_frs) {
            // Inisialisasi model
            $frsModel = new frsModel();

            // Ambil instance dari database service
            $db = \Config\Database::connect();

            // Mulai transaksi untuk memastikan kedua operasi berjalan atomik
            $db->transStart();

            // Hapus data dari tabel frs
            $deletefrs = $frsModel->delete($id_frs);

            // Cek apakah delete berhasil
            if ($deletefrs) {
                // Selesaikan transaksi
                $db->transComplete();

                // Cek apakah transaksi berhasil
                if ($db->transStatus() === FALSE) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data.']);
                }
            } else {
                // Jika id_frs tidak valid, kirimkan response error
                return $this->response->setJSON(['success' => false, 'message' => 'ID frs tidak valid.']);
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

        // Memanggil model frsModel untuk mengambil data
        $frsModel = new frsModel();
        $frsData = $frsModel->getfrsWithAllPatient();

        // Pastikan $frsData berisi array
        if (!$frsData) {
            $frsData = []; // Jika tidak ada data, set menjadi array kosong
        }

        // Kirimkan data ke view
        return view('frs/index_buku_penerima', [
            'frsData' => $frsData,
            'id_user' => $id_user,
            'nama_user' => $nama_user
        ]);
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
    

    public function update_print_frs($id_frs)
    {

        date_default_timezone_set('Asia/Jakarta');
        $frsModel = new frsModel();
        $Pemverifikasi_frs = new Pemverifikasi_frs();
        $Authorized_frs = new Authorized_frs();
        $Pencetakan_frs = new Pencetakan_frs();

        $id_user = session()->get('id_user');

        // Mendapatkan id_frs dari POST
        if (!$id_frs) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID frs tidak ditemukan.');
        }

        // Mengambil data dari POST dan melakukan update
        $data = $this->request->getPost();
        $frsModel->update($id_frs, $data);

        $redirect = $this->request->getPost('redirect');

        if (!$redirect) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman asal tidak ditemukan.');
        }

        // Cek ke halaman mana harus diarahkan setelah update
        if ($redirect === 'index_pemverifikasi' && isset($_POST['id_pemverifikasi'])) {
            $id_pemverifikasi = $this->request->getPost('id_pemverifikasi');
            $Pemverifikasi_frs->updatePemverifikasi($id_pemverifikasi, [
                'id_user_pemverifikasi' => $id_user,
                'status_pemverifikasi' => 'Selesai Pemverifikasi',
                'selesai_pemverifikasi' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pemverifikasi/index_pemverifikasi')->with('success', 'Data berhasil diverifikasi.');
        }

        if ($redirect === 'index_autorized' && isset($_POST['id_autorized'])) {
            $id_autorized = $this->request->getPost('id_autorized');
            $Authorized_frs->updateAutorized($id_autorized, [
                'id_user_autorized' => $id_user,
                'status_autorized' => 'Selesai Authorized',
                'selesai_autorized' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('autorized/index_autorized')->with('success', 'Data berhasil diauthorized.');
        }

        if ($redirect === 'index_pencetakan' && isset($_POST['id_pencetakan'])) {
            $id_pencetakan = $this->request->getPost('id_pencetakan');
            $Pencetakan_frs->updatePencetakan($id_pencetakan, [
                'id_user_pencetakan' => $id_user,
                'status_pencetakan' => 'Selesai Pencetakan',
                'selesai_pencetakan' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('pencetakan/index_pencetakan')->with('success', 'Data berhasil dicetak.');
        }

        // Jika redirect tidak sesuai dengan yang diharapkan
        return redirect()->back()->with('error', 'Terjadi kesalahan: Halaman tujuan tidak valid.');
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
        preg_match('/H\.(\d+)\/\d+/', $kode_frs, $matches);
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

    public function update_status_frs()
    {
        $id_frs = $this->request->getPost('id_frs');
        // Inisialisasi model
        $frsModel = new frsModel();

        // Mengambil data dari form
        $status_frs = $this->request->getPost('status_frs');

        // Data yang akan diupdate
        $data = [
            'status_frs' => $status_frs,
        ];

        // Update data status_frs berdasarkan id_frs
        $frsModel->updateStatusfrs($id_frs, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('frs/index_frs')->with('success', 'Status frs berhasil disimpan.');
    }
}