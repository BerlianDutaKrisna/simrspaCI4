<?php
namespace App\Controllers;

use App\Models\PatientModel;

class Patient extends BaseController
{
    protected $PatientModel;

    // Konstruktor untuk inisialisasi PatientModel
    public function __construct()
    {
        $this->PatientModel = new PatientModel();  // Membuat instance dari PatientModel untuk dipakai di seluruh controller
    }

    public function index()
    {
        $data['patients'] = $this->PatientModel->findAll(); // Mengambil semua data dari tabel 'patients'
        // Mengambil id_user dan nama_user dari session
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        // Mengirim data ke view untuk ditampilkan
        return view('patient/index_patient', $data);
    }
    // Menangani pencarian pasien berdasarkan nomor registrasi (norm_pasien)
    public function searchPatient()
    {
        $norm = $this->request->getPost('norm'); // Ambil data dari AJAX
        // Cari data pasien berdasarkan norm_pasien
        $patient = $this->PatientModel->where('norm_pasien', $norm)->first();
        
        // Jika data ditemukan, kembalikan JSON sukses
        if ($patient) {
            return $this->response->setJSON(['success' => true, 'data' => $patient]);
        }
        
        // Jika data tidak ditemukan, kembalikan JSON error
        return $this->response->setJSON(['success' => false, 'message' => 'Patient not found.']);
    }

    // Menampilkan halaman registrasi pasien
    public function register_patient()
    {
        // Mengambil id_user dan nama_user dari session untuk ditampilkan di form
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        // Mengirim data ke view untuk ditampilkan
        return view('patient/register_patient', $data);
    }

    // Menangani penyimpanan data pasien baru
    public function insert()
    {
        helper(['form', 'url']);  // Memanggil helper form dan url untuk mempermudah validasi dan URL
        $validation = \Config\Services::validation();  // Menyiapkan layanan validasi bawaan CodeIgniter
        
        // Menetapkan aturan validasi
        $validation->setRules([
            'norm_pasien' => [
                'rules' => 'required|min_length[6]|max_length[6]|numeric|is_unique[patient.norm_pasien]',
                'errors' => [
                    'required' => 'Norm Pasien harus diisi.',
                    'min_length' => 'Norm Pasien minimal harus 6 karakter.',
                    'max_length' => 'Norm Pasien maksimal 6 karakter.',
                    'numeric' => 'Norm Pasien harus berupa angka.',
                    'is_unique' => 'Norm Pasien sudah terdaftar!'
                ]
            ],
            'nama_pasien' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Pasien harus diisi.'
                ]
            ]
        ]);

        // Mengecek apakah validasi gagal
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->getErrors());  // Jika gagal, kembalikan dengan pesan error
        }

        // Mengecek apakah Norm Pasien sudah terdaftar
        $norm_pasien = $this->request->getPost('norm_pasien');
        if ($this->PatientModel->checkNormExists($norm_pasien)) {
            return redirect()->back()->with('error', 'Norm sudah terdaftar!');  // Jika Norm sudah ada, tampilkan pesan error
        }

        // Menyiapkan data pasien yang akan disimpan
        $data = [
            'norm_pasien' => $this->request->getPost('norm_pasien'),
            'nama_pasien' => $this->request->getPost('nama_pasien'),
            'alamat_pasien' => $this->request->getPost('alamat_pasien'),
            'tanggal_lahir_pasien' => $this->request->getPost('tanggal_lahir_pasien'),
            'jenis_kelamin_pasien' => $this->request->getPost('jenis_kelamin_pasien'),
            'status_pasien' => $this->request->getPost('status_pasien'),
        ];
        try {
            // Menyimpan data pasien ke database
            $this->PatientModel->insertPatient($data);
            
            // Mengecek apakah data berhasil disimpan
            if ($this->PatientModel->db->affectedRows() > 0) {
                return redirect()->to('/dashboard')->with('success', 'Registrasi berhasil!');  // Jika berhasil, redirect ke halaman dashboard dengan pesan sukses
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar.');  // Jika gagal, tampilkan pesan error
            }
        } catch (\Exception $e) {
            // Menangani kesalahan jika terjadi error saat proses insert
            return redirect()->back()->with('error', 'Terjadi kesalahan internal: ' . $e->getMessage());  // Pesan error jika ada exception
        }
    }
}
