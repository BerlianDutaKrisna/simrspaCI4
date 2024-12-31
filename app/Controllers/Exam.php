<?php

namespace App\Controllers;

use App\Models\HpaModel;
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
    public function __construct()
    {
        $this->hpaModel = new HpaModel();
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

    public function update_buku_penerima($id_hpa)
    {
        $hpaModel = new HpaModel();

        // Mengambil data dari form
        $data = [
            'penerima_hpa' => $this->request->getVar('penerima_hpa'),
        ];

        // Update data penerima_hpa berdasarkan kode_hpa
        $hpaModel->updatePenerima($id_hpa, $data);

        // Redirect setelah berhasil mengupdate data
        return redirect()->to('exam/index_buku_penerima')->with('success', 'Penerima berhasil disimpan.');
    }



    public function register_exam()
    {
        // Inisialisasi array $data dengan nilai default dari session
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'patient' => null, // Default jika tidak ada data pasien ditemukan
        ];

        // Mendapatkan nilai norm_pasien dari query string
        $norm_pasien = $this->request->getGet('norm_pasien');

        // Jika nilai norm_pasien ada, cari data pasien berdasarkan norm_pasien
        if ($norm_pasien) {
            // Inisialisasi model
            $patientModel = new PatientModel();

            // Mencari data pasien berdasarkan norm_pasien
            $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();

            // Menambahkan data pasien ke $data jika ditemukan
            $data['patient'] = $patient ?: null;
        }

        // Mengirim data ke view untuk ditampilkan
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
                'id_hpa'            => $id_hpa,  // Menambahkan id_hpa yang baru
                'id_user_penerimaan' => session()->get('id_user'),
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
    // Menampilkan form edit pengguna
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
}
