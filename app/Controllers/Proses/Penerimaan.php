<?php

namespace App\Controllers\Proses;
use App\Controllers\BaseController;
use App\Models\ProsesModel\PenerimaanModel;

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
        $penerimaanModel = new PenerimaanModel();

        // Mengambil data HPA beserta relasinya
        $data['penerimaanData'] = $penerimaanModel->getPenerimaanWithRelations();

        // Mengambil id_user dan nama_user dari session
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        dd($data);

        // Mengirim data ke view untuk ditampilkan
        return view('proses/penerimaan', $data);
    }
}
