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

    public function index()
    {
        $UserModel = new UserModel();
        $data['users'] = $UserModel->findAll(); // Mengambil semua data dari tabel 'users'
        // Mengambil id_user dan nama_user dari session
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        // Mengirim data ke view untuk ditampilkan
        return view('users/users', $data);
    }
    // Proses insert data pengguna baru (registrasi)
    public function insert()
    {
        helper(['form', 'url']);  // Memanggil helper form dan url untuk mempermudah validasi dan URL
        $validation = \Config\Services::validation();  // Menyiapkan layanan validasi bawaan CodeIgniter
        $this->request->getMethod() === 'POST';  // Memastikan Var yang digunakan adalah POST
            // Menetapkan aturan validasi untuk form
            $validation->setRules([
                'nama_user' => 'required',  // Nama harus diisi
                'username'  => 'required|is_unique[users.username]',  // Username harus unik
                'password'  => 'required',  // Password harus diis
                'password2' => 'matches[password]',  // Password2 harus sama dengan password
                'status_user' => 'required|in_list[Admin,Dokter,Analis,Belum Dipilih]'   // Status user harus diisi
            ]);
            if (!$validation->withRequest($this->request)->run()) {  // Mengecek apakah validasi gagal
                return redirect()->back()->withInput()->with('error', $validation->getErrors());  // Jika gagal, kembalikan dengan pesan error
            }
            // Mengambil data dari form
            $nama_user = $this->request->getPost('nama_user');  // Nama pengguna
            $username = $this->request->getPost('username');  // Username pengguna
            $password = $this->request->getPost('password');  // Password pengguna
            $password2 = $this->request->getPost('password2');  // Konfirmasi password
            $status_user = $this->request->getPost('status_user');  // Status pengguna
            // Mengecek apakah username sudah ada di database
            if ($this->UserModel->checkUsernameExists($username)) {
                return redirect()->back()->with('error', 'Username sudah terdaftar!');  // Jika username sudah ada, tampilkan pesan error
            }
            // Mengecek apakah password dan konfirmasi password cocok
            if ($password !== $password2) {
                return redirect()->back()->with('error', 'Konfirmasi password tidak cocok!');  // Jika password dan konfirmasi tidak cocok, tampilkan pesan error
            }
            // Enkripsi password sebelum disimpan ke database
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);  // Menggunakan bcrypt untuk mengenkripsi password
            // Menyiapkan data untuk disimpan ke database
            $data = [
                'username' => $username,
                'password_user' => $passwordHash,  // Menyimpan password yang sudah terenkripsi
                'nama_user' => $nama_user,
                'status_user' => $status_user
            ];
            try {
                $this->UserModel->insertUser($data);  // Menyimpan data pengguna ke database
                // Mengecek apakah data berhasil disimpan
                if ($this->UserModel->db->affectedRows() > 0) {
                    return redirect()->to('/login')->with('success', 'Registrasi berhasil!');  // Jika berhasil, redirect ke halaman login dengan pesan sukses
                } else {
                    return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar.');  // Jika gagal, tampilkan pesan error
                }
            } catch (\Exception $e) {
                // Menangani kesalahan jika terjadi error saat proses insert
                return redirect()->back()->with('error', 'Terjadi kesalahan internal: ' . $e->getMessage());  // Pesan error jika ada exception
            }
    }
}
