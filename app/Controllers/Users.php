<?php

namespace App\Controllers;

use App\Models\UsersModel;
use Exception;

class Users extends BaseController
{
    protected $UsersModel;

    // Konstruktor untuk inisialisasi UsersModel
    public function __construct()
    {
        $this->UsersModel = new UsersModel();  // Membuat instance dari UsersModel untuk dipakai di seluruh controller
    }

    // Menampilkan halaman daftar users
    public function index_users()
    {
        // Ambil semua data users dari tabel 'users'
        $data['users'] = $this->UsersModel->findAll();

        // Ambil id_user dan nama_user dari session yang sedang aktif
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Kirim data ke view untuk ditampilkan
        return view('users/index_users', $data);
    }
    // Menampilkan halaman form registrasi users
    public function register_users()
    {
        session()->destroy(); // Menghapus semua session
        // Kirim data ke view untuk ditampilkan
        return view('users/register_users');
    }
    // Proses insert data users baru (registrasi)
    public function insert()
    {
        helper(['form', 'url']);

        $validation = \Config\Services::validation();

        $validation->setRules([
            'nama_user'   => 'required',
            'username'    => 'required|is_unique[users.username]',
            'password'    => 'required',
            'password2'   => 'required|matches[password]',
            'status_user' => 'required|in_list[Admin,Dokter,Analis,Belum Dipilih]'
        ], [
            'username' => [
                'is_unique' => 'Username sudah terdaftar!'
            ],
            'password2' => [
                'matches' => 'Konfirmasi password tidak cocok!'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }

        $data = [
            'username'      => $this->request->getPost('username'),
            'password_user' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nama_user'     => $this->request->getPost('nama_user'),
            'status_user'   => $this->request->getPost('status_user')
        ];

        try {
            $this->UsersModel->insert($data);

            return redirect()->to('/')->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan internal: ' . $e->getMessage());
        }
    }

    public function delete($id_users)
    {
        try {
            // Memastikan ID valid
            if (!$id_users || !is_numeric($id_users)) {
                throw new Exception("ID user tidak valid.");
            }
            // Menghapus data user menggunakan model
            $result = $this->UsersModel->deleteUsers($id_users);
            // Jika penghapusan gagal, lempar pengecualian
            if (!$result) {
                throw new Exception("Gagal menghapus user. Silakan coba lagi.");
            }
            // Redirect dengan pesan sukses
            return redirect()->to('/users/index_users')->with('success', 'User berhasil dihapus.');
        } catch (Exception $e) {
            // Menangani pengecualian dan memberikan pesan error
            return redirect()->to('/users/index_users')->with('error', $e->getMessage());
        }
    }

    // Menampilkan form edit pengguna
    public function edit_users($id_users)
    {
        $UsersModel = new UsersModel();

        // Ambil id_user dan nama_user dari session yang sedang aktif
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Ambil data user berdasarkan ID
        $user = $UsersModel->find($id_users);

        // Jika user ditemukan, tampilkan form edit
        if ($user) {
            // Menggabungkan data user dengan session data
            $data['user'] = $user;

            // Kirimkan data ke view
            return view('users/edit_users', $data);
        } else {
            // Jika tidak ditemukan, tampilkan pesan error
            return redirect()->to('/users/index_users')->with('message', [
                'error' => 'User tidak ditemukan.'
            ]);
        }
    }
    // Menangani update data pengguna
    public function update($id_user)
    {
        $UsersModel = new UsersModel();

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_user' => 'required',  // Nama users harus diisi
            'username'  => 'required|is_unique[users.username]'  // Username harus unik
        ], [
            'nama_user' => [
                'required' => 'Nama user harus diisi!'  // Pesan error jika password dan konfirmasi tidak cocok
            ],
            'username' => [
                'is_unique' => 'Username sudah terdaftar!'  // Pesan error untuk username yang sudah ada
            ]
        ]);
        // Mengecek apakah validasi gagal
        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kembali ke form dengan inputan dan pesan error
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }
        // Ambil data yang dikirimkan form
        $data = [
            'username' => $this->request->getVar('username'),
            'nama_user' => $this->request->getVar('nama_user'),
            'status_user' => $this->request->getVar('status_user'),
        ];

        // Update data user
        $UsersModel->update($id_user, $data);

        // Set pesan sukses
        return redirect()->to('/users/index_users')->with('message', [
            'success' => 'Data pengguna berhasil diperbarui.'
        ]);
    }

    public function laporan()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $bulan = is_numeric($bulan) ? (int)$bulan : null;
        $tahun = is_numeric($tahun) ? (int)$tahun : null;

        $usersData = $this->UsersModel->getTotalPekerjaanPerUser($bulan, $tahun);

        $data = [
            'id_user'    => session()->get('id_user'),
            'nama_user'  => session()->get('nama_user'),
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'analis'     => array_filter($usersData, fn($row) => strtolower($row['status_user']) === 'analis'),
            'dokter'     => array_filter($usersData, fn($row) => strtolower($row['status_user']) === 'dokter'),
        ];

        return view('users/laporan', $data);
    }
}
