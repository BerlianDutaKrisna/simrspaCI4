<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SimrsModel;

class SimrsController extends BaseController
{
    protected $simrsModel;

    public function __construct()
    {
        $this->simrsModel = new SimrsModel();
    }

    public function modal_search()
    {
        $request = service('request');
        $json = $request->getJSON();

        // Sesuaikan nama key sesuai JS: "norm_simrs"
        if (!isset($json->norm_simrs)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Norm pasien tidak dikirim.'
            ]);
        }

        $norm = trim($json->norm_simrs);

        try {
            $data = $this->simrsModel->getKunjunganPasien($norm);

            if (isset($data['code']) && $data['code'] == 200 && !empty($data['data'])) {
                // Data dari model berupa array pasien (bisa banyak)
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => $data['data'] // kirim seluruh array pasien
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pasien tidak ditemukan.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
