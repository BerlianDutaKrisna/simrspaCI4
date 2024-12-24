<?php
namespace App\Controllers;

class Exam extends BaseController
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }
    public function register_exam()
{
    // Mendapatkan nilai norm_pasien dari query string
    $norm_pasien = $this->request->getGet('norm_pasien');

    // Inisialisasi model
    $patientModel = new \App\Models\PatientModel();

    // Jika nilai norm_pasien ada, cari data pasien berdasarkan norm_pasien
    if ($norm_pasien) {
        // Mencari data pasien berdasarkan norm_pasien
        $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();

        // Jika pasien ditemukan, kirimkan data pasien ke view
        if ($patient) {
            $data['patient'] = $patient;
        } else {
            // Jika pasien tidak ditemukan, beri pesan atau logika lain
            $data['patient'] = null; // Atau Anda bisa menambahkan pesan error
        }
    }

    dd($patient);

    // Mengambil id_user dan nama_user dari session
    $data['id_user'] = session()->get('id_user');
    $data['nama_user'] = session()->get('nama_user');

    // Mengirim data ke view untuk ditampilkan
    return view('exam/register_exam', $data);
}
}
