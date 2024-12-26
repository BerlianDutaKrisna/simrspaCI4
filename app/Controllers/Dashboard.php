<?php
namespace App\Controllers;
use App\Models\HpaModel;

class Dashboard extends BaseController
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }
    public function index()
    {
        $hpaModel = new HpaModel();

        // Mengambil data HPA beserta relasinya
        $data['hpaData'] = $hpaModel->getHpaWithRelations();
        // Mengambil id_user dan nama_user dari session
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        // Mengirim data ke view untuk ditampilkan
        return view('dashboard/dashboard', $data);
    }
}
