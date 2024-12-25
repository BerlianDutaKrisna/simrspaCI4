<?php

namespace App\Controllers\Proses;
use App\Controllers\BaseController;
use App\Models\HpaModel;

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

        // Mengambil data hpa dengan status_hpa = "Belum Diproses" dan join dengan data pasien
        $hpaModel = new HpaModel();

        // Query untuk mengambil data hpa dan informasi pasien
        $data['hpa'] = $hpaModel->select('hpa.*, patient.nama_pasien, patient.norm_pasien')
        ->join('patient', 'patient.id_pasien = hpa.id_pasien', 'left')
        ->where('hpa.status_hpa', 'Belum Diproses')
        ->findAll();

        // Mengirim data ke view untuk ditampilkan
        return view('proses/penerimaan', $data);

        // Mengirim data ke view untuk ditampilkan
        return view('proses/penerimaan', $data);
    }
}
