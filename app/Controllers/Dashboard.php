<?php

namespace App\Controllers;

use App\Models\Hpa\hpaModel;

class Dashboard extends BaseController
{
    protected $hpaModel;

    public function __construct()
    {
        $this->hpaModel = new hpaModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        // Pastikan sesi sudah diinisialisasi
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/'); // Redirect ke halaman login jika belum login
        }

        // Ambil data dari sesi dan hitung
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'chartData' => json_encode($this->hpaModel->getHpaChartData(), JSON_NUMERIC_CHECK)
        ];

        return view('dashboard/dashboard', $data);
    }
}
