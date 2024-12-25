<?php

namespace App\Controllers;

class Penerimaan extends BaseController
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }
    public function index_penerimaan()
    {
        // Mengambil id_user dan nama_user dari session
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        // Mengirim data ke view untuk ditampilkan
        return view('pemeriksaan/penerimaan', $data);
    }
}
