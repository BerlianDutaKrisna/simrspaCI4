<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SimrsModel;

class Kunjungan extends ResourceController
{
    protected $modelName = SimrsModel::class;
    protected $format    = 'json';

    public function show($norm = null)
    {
        if (empty($norm)) {
            return $this->fail([
                'status'  => 'error',
                'message' => 'Norm pasien tidak dikirim.'
            ], 422);
        }

        try {
            $data = $this->model->getKunjunganPasien($norm);

            if (isset($data['code']) && $data['code'] == 200 && !empty($data['data'])) {
                return $this->respond([
                    'status' => 'success',
                    'data'   => $data['data']
                ]);
            }

            return $this->failNotFound('Pasien tidak ditemukan.');
        } catch (\Exception $e) {
            return $this->failServerError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
