<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SimrsModel;

class Pemeriksaan extends ResourceController
{
    protected $modelName = SimrsModel::class;
    protected $format    = 'json';

    public function showByNorm($norm = null)
    {
        if (empty($norm)) {
            return $this->fail([
                'status'  => 'error',
                'message' => 'Norm pasien tidak dikirim.'
            ], 422);
        }

        try {
            $data = $this->model->getPemeriksaanPasien($norm);
            if (isset($data['code']) && $data['code'] == 200 && !empty($data['data'])) {
                return $this->respond([
                    'status' => 'success',
                    'data' => $data['data'],
                ]);
            }

            return $this->failNotFound('Pemeriksaan pasien tidak ditemukan.');
        } catch (\Exception $e) {
            return $this->failServerError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateByTransaksi($id_transaksi = null)
    {
        if (empty($id_transaksi)) {
            return $this->fail([
                'status'  => 'error',
                'message' => 'ID transaksi tidak dikirim.'
            ], 422);
        }

        // Ambil data dari body request
        $input = $this->request->getRawInput();

        if (empty($input)) {
            return $this->fail([
                'status'  => 'error',
                'message' => 'Data untuk update tidak dikirim.'
            ], 422);
        }

        try {
            $update = $this->model->updatePemeriksaanByTransaksi($id_transaksi, $input);

            if ($update) {
                return $this->respond([
                    'status'  => 'success',
                    'message' => 'Data pemeriksaan berhasil diperbarui.'
                ]);
            }

            return $this->fail('Gagal memperbarui data pemeriksaan.');
        } catch (\Exception $e) {
            return $this->failServerError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
