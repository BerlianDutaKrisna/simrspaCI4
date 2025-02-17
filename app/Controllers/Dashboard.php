<?php

namespace App\Controllers;

use App\Models\HpaModel;
use App\Models\ProsesModel\PenerimaanModel;


class Dashboard extends BaseController
{
    public function index()
    {

        $hpaModel = new HpaModel();
        $penerimaanModel = new PenerimaanModel();
        $hpaData = $hpaModel->getHpaWithRelations();
        $countHpaProcessed = $hpaModel->countHpaProcessed();
        $countPenerimaan = $penerimaanModel->countPenerimaan();
        $chartData = $hpaModel->getHpaChartData();
        $chartDataJson = json_encode($chartData, JSON_NUMERIC_CHECK);

        $data = [
            'hpaData' => $hpaData,
            'countHpa' => $countHpaProcessed,
            'countPenerimaan' => $countPenerimaan,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'chartData' => $chartDataJson // Data dalam format JSON
        ];
        return view('dashboard/dashboard', $data);
    }
}
