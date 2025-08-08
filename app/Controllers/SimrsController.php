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

    /**
     * Endpoint untuk mencari kunjungan pasien berdasarkan norm (dikirim via JSON POST)
     * URL: /simrs/modal_search
     */
    public function modal_search()
    {
        $request = service('request');
        $json = $request->getJSON();

        if (!isset($json->norm)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Norm tidak dikirim.'
            ]);
        }

        $norm = $json->norm;

        try {
            $data = $this->simrsModel->getKunjunganPasien($norm);

            if (isset($data['code']) && $data['code'] == 200 && !empty($data['data'])) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => $data['data']
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pasien tidak ditemukan.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
