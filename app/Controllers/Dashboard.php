<?php

namespace App\Controllers;

use App\Models\HpaModel;
use App\Models\ProsesModel\PenerimaanModel;

class Dashboard extends BaseController
{
    protected $hpaModel;
    protected $penerimaanModel;
    protected $session;

    public function __construct()
    {
        // Menggunakan layanan CodeIgniter untuk manajemen model
        $this->hpaModel = model(HpaModel::class);
        $this->penerimaanModel = model(PenerimaanModel::class);
        $this->session = session();
    }

    public function index()
    {
        // Mengambil data dari model
        $data = [
            'hpaData' => $this->hpaModel->getHpaWithRelations(),
            'countHpa' => $this->hpaModel->countHpaProcessed(),
            'countPenerimaan' => $this->penerimaanModel->countPenerimaan(),
            'id_user' => $this->session->get('id_user'),
            'nama_user' => $this->session->get('nama_user'),
            'chartData' => json_encode($this->hpaModel->getHpaChartData(), JSON_NUMERIC_CHECK)
        ];

        return view('dashboard/dashboard', $data);
    }
}
