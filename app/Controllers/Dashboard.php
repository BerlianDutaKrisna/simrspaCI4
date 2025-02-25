<?php

namespace App\Controllers;

class Dashboard extends BaseController
{

    public function index()
    {
        // Pastikan sesi sudah diinisialisasi
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/'); // Redirect ke halaman login jika belum login
        }
        $data = [
            'nama_user' => $this->session->get('nama_user'),
        ];
        return view('dashboard/dashboard', $data); 
    }
}
