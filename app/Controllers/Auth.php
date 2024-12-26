<?php

namespace App\Controllers;

use App\Models\UsersModel;  // Menggunakan model UsersModel untuk akses data pengguna
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Services;

class Auth extends BaseController
{
    protected $UsersModel;

    // Konstruktor untuk inisialisasi UsersModel
    public function __construct()
    {
        $this->UsersModel = new UsersModel();  // Membuat instance dari UsersModel untuk dipakai di seluruh controller
    }

    // Menampilkan halaman login
    public function index(): string
    {
        return view('auth/login');  // Mengembalikan view login
    }

    // Fungsi login
    // Fungsi login
    public function login()
    {
        // Validasi form
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Cek apakah username ada di database
            $user = $this->UsersModel->where('username', $username)->first();

            // Cek password dan validasi login
            if ($user && password_verify($password, $user['password_user'])) {
                // Jika login berhasil, simpan id_user dan nama_user ke dalam session
                session()->set('id_user', $user['id_user']);
                session()->set('nama_user', $user['nama_user']);

                // Ambil data dari session
                $data['id_user'] = session()->get('id_user');
                $data['nama_user'] = session()->get('nama_user');

                // Redirect ke halaman dashboard setelah login
                return redirect()->to('/dashboard'); // Redirect ke route 'dashboard'
            } else {
                // Jika username atau password salah
                session()->setFlashdata('error', ['Username atau Password salah.']);
                return redirect()->back();
            }
        }

        return redirect()->to('login'); // Redirect jika bukan method POST
    }


    // Fungsi logout
    public function logout()
    {
        // Menghapus session yang terkait dengan user
        session()->destroy(); // Menghapus semua session
        // Redirect ke halaman login setelah logout
        return redirect()->to('login')->with('success', ['Anda telah logout.']);
    }
}
