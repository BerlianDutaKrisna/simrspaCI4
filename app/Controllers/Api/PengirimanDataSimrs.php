<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PengirimanDataSimrsModel;

class PengirimanDataSimrs extends ResourceController
{
    protected $modelName = PengirimanDataSimrsModel::class;
    protected $format    = 'json';

    public function index()
    {
        $allData = $this->model->getPengirimanData();

        $data = [
            'id_user'   => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'data'      => $allData
        ];

        return view('PengirimanData/index', $data);
    }

    public function kirim()
    {
        $dataBelumTerkirim = $this->model
            ->where('status', 'Belum Terkirim')
            ->findAll();

        if (empty($dataBelumTerkirim)) {
            log_message('info', '[PENGIRIMAN SIMRS] Tidak ada data yang perlu dikirim.');
            return $this->respond([
                'status' => 'success',
                'message' => 'Tidak ada data yang perlu dikirim.'
            ]);
        }

        $endpoint = 'https://api.simrs.local/pengiriman-data'; // ganti nanti sesuai endpoint asli
        $results = [];

        foreach ($dataBelumTerkirim as $row) {
            $payload = json_encode($row, JSON_PRETTY_PRINT);

            // ✅ log payload sebelum kirim
            log_message('info', "[PENGIRIMAN SIMRS] Payload akan dikirim:\n" . $payload);

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $this->model->update($row['id'], [
                    'status' => 'Terkirim',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                log_message('info', "[PENGIRIMAN SIMRS] BERHASIL (ID: {$row['id']}):\n" . $response);

                $results[] = [
                    'id' => $row['id'],
                    'status' => 'success',
                    'response' => json_decode($response, true)
                ];
            } else {
                log_message('error', "[PENGIRIMAN SIMRS] GAGAL (ID: {$row['id']}): " . ($curlError ?: "HTTP Code: $httpCode"));

                $results[] = [
                    'id' => $row['id'],
                    'status' => 'failed',
                    'error' => $curlError ?: 'HTTP Code: ' . $httpCode,
                    'response' => $response
                ];
            }
        }

        // ✅ log hasil akhir semua pengiriman
        log_message('info', '[PENGIRIMAN SIMRS] Rekap hasil: ' . json_encode($results, JSON_PRETTY_PRINT));

        return $this->respond([
            'status' => 'done',
            'total_dikirim' => count($results),
            'detail' => $results
        ]);
    }

    public function kirimById($idtransaksi = null)
    {
        if (!$idtransaksi) {
            log_message('error', '[PENGIRIMAN SIMRS] idtransaksi kosong saat kirimById.');
            return $this->fail([
                'status'  => 'error',
                'message' => 'idtransaksi tidak boleh kosong.'
            ], 422);
        }

        $row = $this->model->where('idtransaksi', $idtransaksi)->first();

        if (!$row) {
            log_message('error', "[PENGIRIMAN SIMRS] Data dengan idtransaksi $idtransaksi tidak ditemukan.");
            return $this->failNotFound("Data dengan idtransaksi $idtransaksi tidak ditemukan.");
        }

        $endpoint = 'https://api.simrs.local/pengiriman-data'; // ganti nanti sesuai endpoint asli
        $payload = json_encode($row, JSON_PRETTY_PRINT);

        log_message('info', "[PENGIRIMAN SIMRS] Payload akan dikirim (BY ID $idtransaksi):\n" . $payload);

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $this->model->where('idtransaksi', $idtransaksi)
                ->set(['status' => 'Terkirim', 'updated_at' => date('Y-m-d H:i:s')])
                ->update();

            log_message('info', "[PENGIRIMAN SIMRS] BERHASIL (BY ID $idtransaksi):\n" . $response);

            return $this->respond([
                'status' => 'success',
                'idtransaksi' => $idtransaksi,
                'response' => json_decode($response, true)
            ]);
        }

        log_message('error', "[PENGIRIMAN SIMRS] GAGAL (BY ID $idtransaksi): " . ($curlError ?: "HTTP Code: $httpCode"));

        return $this->fail([
            'status' => 'failed',
            'message' => 'Gagal mengirim data.',
            'error' => $curlError ?: "HTTP Code: $httpCode"
        ], 500);
    }
}
