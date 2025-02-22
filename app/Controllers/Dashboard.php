<?php

namespace App\Controllers;

use App\Models\HpaModel;

class Dashboard extends BaseController
{
    protected $hpaModel;
    protected $session;

    public function __construct()
    {
        $this->hpaModel = model(HpaModel::class);
        $this->session = session();
    }

    public function index()
    {
        $data = [
            'hpaData' => $this->hpaModel->getHpaWithRelations(),
            'countHpa' => $this->hpaModel->countHpaProcessed(),
            'countPenerimaan' => $this->hpaModel->countPenerimaan(),
            'countPengirisan' => $this->hpaModel->countPengirisan(),
            'countPemotongan' => $this->hpaModel->countPemotongan(),
            'countPemprosesan' => $this->hpaModel->countPemprosesan(),
            'countPenanaman' => $this->hpaModel->countPenanaman(),
            'countPemotonganTipis' => $this->hpaModel->countPemotonganTipis(),
            'countPewarnaan' => $this->hpaModel->countPewarnaan(),
            'countPembacaan' => $this->hpaModel->countPembacaan(),
            'countPenulisan' => $this->hpaModel->countPenulisan(),
            'countPemverifikasi' => $this->hpaModel->countPemverifikasi(),
            'countAutorized' => $this->hpaModel->countAutorized(),
            'countPencetakan' => $this->hpaModel->countPencetakan(),
            'id_user' => $this->session->get('id_user'),
            'nama_user' => $this->session->get('nama_user'),
            'chartData' => json_encode($this->hpaModel->getHpaChartData(), JSON_NUMERIC_CHECK)
        ];

        return view('dashboard/dashboard', $data);
    }
}
