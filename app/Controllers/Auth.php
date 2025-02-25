<?php

namespace App\Controllers;

use App\Models\UsersModel; 
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Services;

class Auth extends BaseController
{
    protected $UsersModel;

    public function __construct()
    {
        $this->UsersModel = new UsersModel();
    }

    public function index(): string
    {
        return view('auth/login');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            // Validasi input
            $validationRules = [
                'username' => 'required',
                'password' => 'required',
            ];

            if (!$this->validate($validationRules)) {
                session()->setFlashdata('error', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }

            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            try {
                $user = $this->UsersModel->where('username', $username)->first();

                if ($user && password_verify($password, $user['password_user'])) {
                    // Regenerate session ID
                    $this->session->regenerate();

                    $this->session->set([
                        'id_user' => $user['id_user'],
                        'nama_user' => $user['nama_user'],
                        'logged_in' => true,
                    ]);
                    return redirect()->to('dashboard');
                } else {
                    // Pesan kesalahan umum
                    session()->setFlashdata('error', ['Username atau password tidak valid.']);
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                // Log error (gunakan logger CodeIgniter)
                log_message('error', '[ERROR] {exception}', ['exception' => $e]);
                session()->setFlashdata('error', ['Terjadi kesalahan. Silakan coba lagi nanti.']);
                return redirect()->back();
            }
        }
        return redirect()->to('login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah logout.');
    }
}
