<?php

namespace App\Controllers;

use App\Models\SignatureModel;
use CodeIgniter\RESTful\ResourceController;

class Signature extends ResourceController
{
    protected $modelName = SignatureModel::class;
    protected $format    = 'json';

    /**
     * Simpan data signature dari API
     */
    public function save()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->fail('Data tidak ditemukan');
        }

        // Optional: set waktu signature otomatis
        $data['dateTimeSignature'] = date('Y-m-d H:i:s');

        try {
            $this->model->insert($data);

            return $this->respond([
                'status' => 'success',
                'message' => 'Data signature berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}