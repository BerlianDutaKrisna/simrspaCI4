<?php

namespace App\Controllers\Fnab;

use App\Controllers\BaseController;
use App\Models\Fnab\FnabModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Fnab\ProsesModel\PenerimaanModel;
use App\Models\Fnab\ProsesModel\PembacaanModel;
use App\Models\Fnab\ProsesModel\PenulisanModel;
use App\Models\Fnab\ProsesModel\PemverifikasiModel;
use App\Models\Fnab\ProsesModel\AutorizedModel;
use App\Models\Fnab\ProsesModel\PencetakanModel;
use App\Models\Fnab\MutuModel;
use Exception;

class Fnab extends BaseController
{
    protected $fnabModel;
    protected $usersModel;
    protected $patientModel;
    protected $penerimaanModel;
    protected $pembacaanModel;
    protected $penulisanModel;
    protected $pemverifikasiModel;
    protected $autorizedModel;
    protected $pencetakanModel;
    protected $mutuModel;

    public function __construct()
    {
        $this->fnabModel = new FnabModel();
        $this->usersModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penerimaanModel = new PenerimaanModel();
        $this->pembacaanModel = new PembacaanModel();
        $this->penulisanModel = new PenulisanModel();
        $this->pemverifikasiModel = new PemverifikasiModel();
        $this->autorizedModel = new AutorizedModel();
        $this->pencetakanModel = new PencetakanModel();
        $this->mutuModel = new MutuModel();
    }

    public function register_fnab()
    {
        // Ambil data kode FNAB terakhir dari model FnabModel
        $lastfnab = $this->fnabModel->getLastKodefnab();

        // Ambil tahun saat ini
        $currentYear = date('y');
        $nextNumber = 1;

        // Jika ada data kode fnab terakhir
        if ($lastfnab) {
            // Ambil kode fnab terakhir dan pisahkan berdasarkan format 'H.XX/YY'
            $lastKode = $lastfnab['kode_fnab'];
            $lastParts = explode('/', $lastKode);
            $lastYear = $lastParts[1];

            // Jika tahun kode fnab terakhir sama dengan tahun sekarang
            if ($lastYear == $currentYear) {
                $lastNumber = (int) explode('.', $lastParts[0])[1];
                $nextNumber = $lastNumber + 1;
            } else {
                // Jika tahun berbeda, nomor dimulai kembali dari 1
                $nextNumber = 1;
            }
        } else {
            // Jika tidak ada kode fnab sebelumnya, mulai dari 1
            $nextNumber = 1;
        }

        // Format kode fnab baru
        $kodefnab = 'FRS.' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT) . '/' . $currentYear;

        // Siapkan data untuk dikirim ke view
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'kode_fnab' => $kodefnab,
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
        return view('fnab/register_fnab', $data);
    }

    public function insert()
    {
        try {
            // Validasi data input hanya untuk kode_fnab
            $validation = \Config\Services::validation();
            $validation->setRules([
                'kode_fnab' => [
                    'rules' => 'required|is_unique[fnab.kode_fnab]',
                    'errors' => [
                        'required' => 'Kode FNAB harus diisi.',
                        'is_unique' => 'Kode FNAB sudah terdaftar!',
                    ],
                ],
            ]);

            // Jika validasi gagal, kembalikan ke form dengan error
            if (!$validation->run(['kode_fnab' => $this->request->getPost('kode_fnab')])) {
                return redirect()->back()->withInput()->with('error', $validation->getErrors());
            }

            // Ambil data dari form
            $data = [
                'kode_fnab'           => $this->request->getPost('kode_fnab'),
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
                'status_fnab'         => $this->request->getPost('status_fnab'),
            ];

            // Data untuk tabel fnab
            $fnabModel = new fnabModel();
            $fnabData = [
                'kode_fnab'           => $data['kode_fnab'],
                'id_pasien'          => $data['id_pasien'],
                'unit_asal'          => $data['unit_asal'],
                'dokter_pengirim'    => $data['dokter_pengirim'],
                'tanggal_permintaan' => $data['tanggal_permintaan'],
                'tanggal_hasil'      => $data['tanggal_hasil'],
                'lokasi_spesimen'    => $data['lokasi_spesimen'],
                'tindakan_spesimen'  => $data['tindakan_spesimen'],
                'diagnosa_klinik'    => $data['diagnosa_klinik'],
                'status_fnab'         => $data['status_fnab'],
            ];

            // Simpan data fnab terlebih dahulu
            if (!$fnabModel->insert($fnabData)) {
                throw new Exception('Gagal menyimpan data fnab.');
            }

            // Ambil id_fnab yang baru saja disimpan
            $id_fnab = $fnabModel->getInsertID();
            // Data untuk tabel penerimaan
            $penerimaanData = [
                'id_fnab'            => $id_fnab,
                'status_penerimaan' => 'Belum Pemeriksaan',
            ];
            // Inisialisasi model penerimaan
            $penerimaanModel = new PenerimaanModel();

            // Simpan data ke tabel penerimaan_fnab
            if (!$penerimaanModel->insert($penerimaanData)) {
                // Jika insert gagal, tampilkan error
                return redirect()->back()->withInput()->with('errors', $penerimaanModel->errors());
            }
            // Data untuk tabel mutu
            $mutuData = [
                'id_fnab'            => $id_fnab,  // Menambahkan id_fnab yang baru
            ];
            // Simpan data ke tabel mutu
            $mutuModel = new MutuModel();
            if (!$mutuModel->insert($mutuData)) {
                throw new Exception('Gagal menyimpan data mutu.');
            }
            // Jika berhasil, redirect ke halaman dashboard
            return redirect()->to('/dashboard')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
