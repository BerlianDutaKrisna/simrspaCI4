<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $UserModel;

    // Konstruktor untuk inisialisasi UserModel
    public function __construct()
    {
        $this->UserModel = new UserModel();  // Membuat instance dari UserModel untuk dipakai di seluruh controller
    }

    // Menampilkan halaman daftar pengguna
    public function index()
    {
        // Ambil semua data pengguna dari tabel 'users'
        $data['users'] = $this->UserModel->findAll(); 

        // Ambil id_user dan nama_user dari session yang sedang aktif
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        
        // Kirim data ke view untuk ditampilkan
        return view('users/users', $data);
    }

    // Proses insert data pengguna baru (registrasi)
    public function insert()
    {
        helper(['form', 'url']); // Memanggil helper form dan url untuk mempermudah validasi dan URL
        $validation = \Config\Services::validation(); // Menyiapkan layanan validasi

        // Menetapkan aturan validasi untuk form input
        $validation->setRules([
            'nama_user' => 'required',  // Nama pengguna harus diisi
            'username'  => 'required|is_unique[users.username]',  // Username harus unik
            'password'  => 'required',  // Password harus diisi
            'password2' => 'matches[password]',  // Password2 harus sama dengan password
            'status_user' => 'required|in_list[Admin,Dokter,Analis,Belum Dipilih]' // Status user harus valid
        ], [
            'username' => [
                'is_unique' => 'Username sudah terdaftar!'  // Pesan error untuk username yang sudah ada
            ],
            'password2' => [
                'matches' => 'Konfirmasi password tidak cocok!'  // Pesan error jika password dan konfirmasi tidak cocok
            ]
        ]);

        // Mengecek apakah validasi gagal
        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kembali ke form dengan inputan dan pesan error
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }

        // Mengambil data dari form input
        $nama_user = $this->request->getPost('nama_user');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $password2 = $this->request->getPost('password2');
        $status_user = $this->request->getPost('status_user');

        // Mengecek apakah username sudah ada di database
        if ($this->UserModel->checkUsernameExists($username)) {
            return redirect()->back()->with('error', 'Username sudah terdaftar!');
        }

        // Mengecek apakah password dan konfirmasi password cocok
        if ($password !== $password2) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok!');
        }

        // Enkripsi password sebelum disimpan ke database
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Menyiapkan data untuk disimpan ke database
        $data = [
            'username' => $username,
            'password_user' => $passwordHash,  // Menyimpan password yang sudah terenkripsi
            'nama_user' => $nama_user,
            'status_user' => $status_user
        ];

        try {
            // Menyimpan data pengguna ke database
            $this->UserModel->insertUser($data);

            // Mengecek apakah data berhasil disimpan
            if ($this->UserModel->db->affectedRows() > 0) {
                // Jika berhasil, redirect ke halaman login dengan pesan sukses
                return redirect()->to('/login')->with('success', 'Registrasi berhasil!');
            } else {
                // Jika gagal, tampilkan pesan error
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar.');
            }
        } catch (\Exception $e) {
            // Menangani kesalahan jika terjadi error saat proses insert
            return redirect()->back()->with('error', 'Terjadi kesalahan internal: ' . $e->getMessage());
        }
    }
}
