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
        $simrsStatus = false;
        $simrsResponse = null;
        $simrsError = null;

        try {

            $client = \Config\Services::curlrequest();

            $response = $client->post(
                'http://10.250.10.107/apibdrs/apibdrs/postInformed',
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode($data),

                    // agar response 500 tetap bisa dibaca
                    'http_errors' => false
                ]
            );

            $simrsStatus = $response->getStatusCode() >= 200
                        && $response->getStatusCode() < 300;

            $simrsResponse = json_decode($response->getBody(), true);

            if ($simrsResponse === null) {
                $simrsResponse = $response->getBody();
            }

            log_message(
                'info',
                '[SIMRS] HTTP ' . $response->getStatusCode() .
                ' RESPONSE: ' . $response->getBody()
            );

        } catch (\Exception $e) {

            $simrsError = $e->getMessage();

            log_message(
                'error',
                '[SIMRS] Gagal kirim: ' . $simrsError
            );
        }

        // =========================
        // 4. RESPONSE KE BROWSER
        // =========================
        return $this->respond([
            'status' => true,
            'action' => $action,
            'message' => $action === 'update'
                ? 'Data berhasil diupdate'
                : 'Data berhasil disimpan',

            'simrs' => [
                'success' => $simrsStatus,
                'response' => $simrsResponse,
                'error' => $simrsError
            ]
        ]);
    }
}