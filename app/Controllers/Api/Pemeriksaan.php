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

        // Ambil data dari body request, bisa pakai getRawInput() atau getJSON() jika kiriman JSON
        $input = $this->request->getJSON(true) ?? $this->request->getRawInput();

        if (empty($input)) {
            return $this->fail([
                'status'  => 'error',
                'message' => 'Data untuk update tidak dikirim.'
            ], 422);
        }

        // Filter hanya field yang boleh diupdate
        $allowedFields = ['id_pemverifikasi_hpa', 'id_authorized_hpa', 'id_pencetakan_hpa', 'print_hpa', 'tanggal_transaksi', 'no_register'];
        $filteredInput = array_filter(
            $input,
            fn($key) => in_array($key, $allowedFields),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($filteredInput)) {
            return $this->fail([
                'status'  => 'error',
                'message' => 'Tidak ada data valid untuk diperbarui.'
            ], 422);
        }

        try {
            $update = $this->model->updatePemeriksaanByTransaksi($id_transaksi, $filteredInput);

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
