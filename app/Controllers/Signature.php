<?php

namespace App\Controllers;

use App\Models\SignatureModel;
use CodeIgniter\RESTful\ResourceController;

class Signature extends ResourceController
{
    protected $modelName = SignatureModel::class;
    protected $format    = 'json';

    public function save()
{
    $data = $this->request->getJSON(true);

    if (!$data) {
        return $this->fail('Data kosong');
    }

    // =========================
    // 1. NORMALISASI DATA KOSONG
    // =========================
    $fieldsToClean = [
        'dokter_pelaksana',
        'pemberi_informasi',
        'hubungan_dengan_pasien',
        'nama_hubungan_pasien'
    ];

    foreach ($fieldsToClean as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '' || strpos($data[$field], '____') !== false) {
            $data[$field] = "";
        }
    }

    // =========================
    // 2. CEK EXISTING DATA
    // =========================
    $existing = $this->model
        ->where('id_transaksi', $data['id_transaksi'])
        ->first();

    if ($existing) {
        // UPDATE
        $this->model
            ->where('id_transaksi', $data['id_transaksi'])
            ->set($data)
            ->update();

        $action = 'update';
    } else {
        // INSERT
        $this->model->insert($data);
        $action = 'insert';
    }

    // =========================
    // 3. KIRIM KE SIMRS
    // =========================
    try {
        $client = \Config\Services::curlrequest();

        $response = $client->post(
            'kirimke_simrs', // Ganti dengan URL endpoint SIMRS yang sesuai
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body'    => json_encode($data)
            ]
        );

        $responseBody = $response->getBody();

        log_message('info', '[SIMRS] Response: ' . $responseBody);

        session()->setFlashdata('simrs_payload', json_encode($data, JSON_PRETTY_PRINT));
        session()->setFlashdata('simrs_response', $responseBody);

    } catch (\Exception $e) {

        log_message('error', '[SIMRS] Gagal kirim: ' . $e->getMessage());

        session()->setFlashdata('simrs_error', $e->getMessage());
    }

    // =========================
    // 4. RESPONSE
    // =========================
    return $this->respond([
        'status' => 'success',
        'action' => $action,
        'message' => $action === 'update' ? 'Data berhasil diupdate' : 'Data berhasil disimpan'
    ]);
}
}