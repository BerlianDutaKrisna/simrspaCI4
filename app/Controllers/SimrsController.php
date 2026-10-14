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

        $norm = trim($json->norm_simrs ?? $json->norm ?? '');
        if (empty($norm)) {
            $norm = trim((string)($request->getVar('norm_simrs') ?? $request->getVar('norm') ?? ''));
        }

        if (empty($norm)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Norm pasien tidak dikirim.'
            ]);
        }


        try {
            $data = $this->simrsModel->getKunjunganPasienByNorm($norm);

            if (isset($data['code']) && $data['code'] == 200 && !empty($data['data'])) {
                // Data dari model berupa array pasien (bisa banyak)
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => $data['data'] // kirim seluruh array kunjungan pasien
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $data['message'] ?? 'Data kunjungan pasien tidak ditemukan.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function permintaan($norm = null)
    {
        $request = service('request');

        if (empty($norm)) {
            $json = $request->getJSON();
            if ($json && isset($json->norm)) {
                $norm = trim($json->norm);
            } else {
                $norm = $request->getGet('norm') ?? $request->getPost('norm');
            }
        }

        if (empty($norm)) {
            return $this->response->setStatusCode(400)->setJSON([
                'code' => 400,
                'message' => 'Parameter norm dibutuhkan'
            ]);
        }

        try {
            $result = $this->simrsModel->getPermintaan($norm);
            return $this->response->setStatusCode($result['code'] ?? 500)->setJSON($result);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'code' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
