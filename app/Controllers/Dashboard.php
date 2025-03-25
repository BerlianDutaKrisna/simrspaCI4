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
        $data = [
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),
            'chartData' => json_encode([
                'hpa' => $this->hpaModel->getHpaChartData(),
                'frs' => $this->frsModel->getfrsChartData(),
                'srs' => $this->srsModel->getsrsChartData(),
                'ihc' => $this->ihcModel->getihcChartData()
            ], JSON_NUMERIC_CHECK),
            'pieChartData' => json_encode([
                'hpa' => $this->hpaModel->getTotalHpa(),
                'frs' => $this->frsModel->getTotalFrs(),
                'srs' => $this->srsModel->getTotalSrs(),
                'ihc' => $this->ihcModel->getTotalIhc()
            ], JSON_NUMERIC_CHECK)
        ];

        return view('dashboard/dashboard', $data);
    }
}
