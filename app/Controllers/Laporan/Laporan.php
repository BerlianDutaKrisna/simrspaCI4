<?php

namespace App\Controllers\Laporan;

use App\Controllers\BaseController;
use App\Models\HpaModel;

class Laporan extends BaseController
{
    protected $hpaModel;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
    }

    public function index_laporan() // Update nama method
    {
        $hpaModel = new HpaModel();

        $hpaData = $hpaModel->getHpaWithRelations();
        $data = [
            'hpaData' => $hpaData,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        return view('laporan/index_laporan', $data);
    }
}