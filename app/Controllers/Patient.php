<?php

namespace App\Controllers;

use App\Models\PatientModel;
use Exception;

class Patient extends BaseController
{
    protected $PatientModel;

    // Konstruktor untuk inisialisasi PatientModel
    public function __construct()
    {
        $this->PatientModel = new PatientModel();  // Membuat instance dari PatientModel untuk dipakai di seluruh controller
    }

    // Menampilkan halaman daftar pasien
    public function index_patient()
    {
        $data['patients'] = $this->PatientModel->findAll(); // Mengambil semua data dari tabel 'patients'
        // Ambil id_user dan nama_user dari session yang sedang aktif
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        // Mengirim data ke view untuk ditampilkan
        return view('patient/index_patient', $data);
    }

    // Menampilkan halaman registrasi pasien
    public function register_patient()
    {
        // Mendapatkan nilai norm_pasien dari query string
        $norm_pasien = $this->request->getGet('norm_pasien');

        // Jika nilai norm_pasien ada, gunakan untuk prapengisian
        if ($norm_pasien) {
            // Lakukan logika yang sesuai, misalnya prapengisian form
            $data['norm_pasien'] = $norm_pasien;
        }

        // Mengambil id_user dan nama_user dari session untuk ditampilkan di form
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        // Mengirim data ke view untuk ditampilkan
        return view('patient/register_patient', $data);
    }

    // Menangani penyimpanan data pasien baru
    public function insert()
    {
        helper(['form', 'url']);

        // Validasi input
        if (!$this->validate([
            'norm_pasien' => [
                'rules'  => 'required|min_length[6]|max_length[6]|numeric|is_unique[patient.norm_pasien]',
                'errors' => [
                    'required'   => 'Norm Pasien harus diisi.',
                    'min_length' => 'Norm Pasien minimal harus 6 karakter.',
                    'max_length' => 'Norm Pasien maksimal harus 6 karakter.',
                    'numeric'    => 'Norm Pasien harus berupa angka.',
                    'is_unique'  => 'Norm Pasien sudah terdaftar!'
                ]
            ],
            'nama_pasien' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Nama Pasien harus diisi.']
            ]
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // Data yang akan disimpan
        $data = [
            'norm_pasien'         => $this->request->getPost('norm_pasien'),
            'nama_pasien'         => strtoupper($this->request->getPost('nama_pasien')),
            'alamat_pasien'       => strtoupper($this->request->getPost('alamat_pasien')),
            'tanggal_lahir_pasien' => $this->request->getPost('tanggal_lahir_pasien') ?: null,
            'jenis_kelamin_pasien' => $this->request->getPost('jenis_kelamin_pasien'),
            'status_pasien'       => $this->request->getPost('status_pasien'),
        ];

        // Simpan data ke database
        if ($this->PatientModel->save($data)) {
            return redirect()->to('/dashboard')->with('success', 'Registrasi berhasil!');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar.');
    }


    // Menampilkan halaman detail pasien
    public function delete($id_patient)
    {
        try {
            // Memastikan ID valid
            if (!$id_patient || !is_numeric($id_patient)) {
                throw new Exception("ID pasien tidak valid.");
            }
            // Menghapus data pasien menggunakan model
            $result = $this->PatientModel->deletePatient($id_patient);
            // Jika penghapusan gagal, lempar pengecualian
            if (!$result) {
                throw new Exception("Gagal menghapus pasien. Silakan coba lagi.");
            }
            // Redirect dengan pesan sukses
            return redirect()->to('/patient/index_patient')->with('success', 'Pasien berhasil dihapus.');
        } catch (Exception $e) {
            // Menangani pengecualian dan memberikan pesan error
            return redirect()->to('/patient/index_patient')->with('error', $e->getMessage());
        }
    }

    // Menampilkan form edit pengguna
    public function edit_patient($id_pasien)
    {
        $PatienModel = new PatientModel();

        // Ambil id_user dan nama_user dari session yang sedang aktif
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Ambil data pasien berdasarkan ID
        $pasien = $PatienModel->find($id_pasien);

        // Jika pasien ditemukan, tampilkan form edit
        if ($pasien) {
            // Menggabungkan data pasien dengan session data
            $data['pasien'] = $pasien;

            // Kirimkan data ke view
            return view('patient/edit_patient', $data);
        } else {
            // Jika tidak ditemukan, tampilkan pesan error
            return redirect()->to('/patient/index_patient')->with('message', [
                'error' => 'pasien tidak ditemukan.'
            ]);
        }
    }

    // Menangani update data pengguna
    public function update($id_pasien)
    {
        $PatientModel = new PatientModel();

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'norm_pasien'  => 'required',
            'nama_pasien' => 'required'
        ], [
            'norm_pasien' => [
                'required' => 'Norm harus diisi!'
            ],
            'nama_pasien' => [
                'required' => 'nama_pasien harus diisi!'
            ]
        ]);
        // Mengecek apakah validasi gagal
        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kembali ke form dengan inputan dan pesan error
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }
        // Ambil data yang dikirimkan form
        $data = [
            'norm_pasien' => $this->request->getVar('norm_pasien'),
            'nama_pasien' => $this->request->getVar('nama_pasien'),
            'alamat_pasien' => $this->request->getVar('alamat_pasien'),
            'tanggal_lahir_pasien' => $this->request->getVar('tanggal_lahir_pasien') ?: null,
            'jenis_kelamin_pasien' => $this->request->getVar('jenis_kelamin_pasien'),
            'status_pasien' => $this->request->getVar('status_pasien'),
        ];

        // Update data pasien
        $PatientModel->update($id_pasien, $data);

        // Set pesan sukses
        return redirect()->to('/patient/index_patient')->with('message', [
            'success' => 'Data pasien berhasil diperbarui.'
        ]);
    }

    // Menangani pencarian pasien
    public function search_patient()
    {
        $norm = $this->request->getPost('norm'); // Ambil input NoRM dari form
        $patientModel = new PatientModel();

        // Cari pasien berdasarkan NoRM
        $patient = $patientModel->where('norm_pasien', $norm)->first();

        if ($patient) {
            // Kirim data pasien ke view
            return view('patient_search_result', ['patient' => $patient]);
        } else {
            // Kirim pesan error ke view
            return view('patient_search_result', ['error' => 'Patient not found']);
        }
    }

    public function modal_search()
    {
        // Ambil data 'norm' yang dikirim dari frontend
        $norm_pasien = $this->request->getVar('norm');

        // Inisialisasi model
        $patientModel = new PatientModel();

        // Cari pasien berdasarkan norm_pasien
        $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();

        // Cek apakah pasien ditemukan
        if ($patient) {
            // Jika ditemukan, kirimkan data pasien dalam format JSON
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $patient
            ]);
        } else {
            // Jika tidak ditemukan, kirimkan pesan kesalahan
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pasien belum terdaftar'
            ]);
        }
    }

    public function laporan()
    {
        $keyword              = $this->request->getGet('keyword');
        $jenis_kelamin_pasien = '';
        $status_pasien        = '';

        $patientData = $this->PatientModel->findAll();

        $data = [
            'id_user'             => session()->get('id_user'),
            'nama_user'           => session()->get('nama_user'),
            'keyword'             => $keyword,
            'jenis_kelamin_pasien' => $jenis_kelamin_pasien,
            'status_pasien'       => $status_pasien,
            'patientData'         => $patientData,
        ];

        return view('patient/laporan', $data);
    }


    public function filter()
    {
        $keyword              = $this->request->getGet('keyword');
        $jenis_kelamin_pasien = $this->request->getGet('jenis_kelamin_pasien');
        $status_pasien        = $this->request->getGet('status_pasien');

        $patientData = $this->PatientModel->filterPatients($keyword, $jenis_kelamin_pasien, $status_pasien);

        $data = [
            'id_user'             => session()->get('id_user'),
            'nama_user'           => session()->get('nama_user'),
            'keyword'             => $keyword,
            'jenis_kelamin_pasien' => $jenis_kelamin_pasien,
            'status_pasien'       => $status_pasien,
            'patientData'         => $patientData,
        ];
        
        return view('patient/laporan', $data);
    }
}
