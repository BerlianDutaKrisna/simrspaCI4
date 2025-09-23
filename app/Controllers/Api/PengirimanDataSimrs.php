<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PengirimanDataSimrsModel;
use App\Models\KunjunganModel;

class PengirimanDataSimrs extends ResourceController
{
    protected $modelName = PengirimanDataSimrsModel::class;
    protected $format    = 'json';

    /**
     * Tampilkan halaman index untuk pengiriman data SIMRS
     */
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

    /**
     * Kirim semua data dengan status "Belum Terkirim"
     */
    public function kirim()
    {
        $dataBelumTerkirim = $this->model
            ->where('status', 'Belum Terkirim')
            ->findAll();

        if (empty($dataBelumTerkirim)) {
            log_message('info', '[PENGIRIMAN SIMRS] Tidak ada data yang perlu dikirim.');
            return $this->respond([
                'status'  => 'success',
                'message' => 'Tidak ada data yang perlu dikirim.'
            ]);
        }

        $endpoint = 'https://api.simrs.local/pengiriman-data'; // TODO: ganti endpoint asli
        $results  = [];

        foreach ($dataBelumTerkirim as $row) {
            $payload = json_encode($row, JSON_PRETTY_PRINT);

            log_message('info', "[PENGIRIMAN SIMRS] Payload akan dikirim:\n" . $payload);

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            $response   = curl_exec($ch);
            $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError  = curl_error($ch);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $this->model->update($row['id'], [
                    'status'     => 'Terkirim',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                log_message('info', "[PENGIRIMAN SIMRS] BERHASIL (ID: {$row['id']}):\n" . $response);

                $results[] = [
                    'id'      => $row['id'],
                    'status'  => 'success',
                    'payload' => $row,
                    'response' => json_decode($response, true)
                ];
            } else {
                log_message(
                    'error',
                    "[PENGIRIMAN SIMRS] GAGAL (ID: {$row['id']}): " . ($curlError ?: "HTTP Code: $httpCode")
                );

                $results[] = [
                    'id'      => $row['id'],
                    'status'  => 'failed',
                    'error'   => $curlError ?: 'HTTP Code: ' . $httpCode,
                    'payload' => $row,
                    'response' => $response
                ];
            }
        }

        log_message('info', '[PENGIRIMAN SIMRS] Rekap hasil: ' . json_encode($results, JSON_PRETTY_PRINT));

        return $this->respond([
            'status'        => 'done',
            'total_dikirim' => count($results),
            'detail'        => $results
        ]);
    }

    public function kirimById($idtransaksi = null)
    {
        if (!$idtransaksi) {
            $norm = $this->request->getPost('norm');

            if (!$norm) {
                return $this->fail([
                    'status'  => 'error',
                    'message' => 'idtransaksi atau norm harus diisi.'
                ], 422);
            }

            // cari idtransaksi dari norm (lokal DB)
            $kunjunganModel = new \App\Models\KunjunganModel();
            $kunjungan = $kunjunganModel
                ->where('norm', $norm)
                ->orderBy('tanggal', 'DESC')
                ->first();

            if ($kunjungan && isset($kunjungan['idtransaksi'])) {
                $idtransaksi = $kunjungan['idtransaksi'];
            } else {
                return $this->failNotFound("idtransaksi untuk norm {$norm} tidak ditemukan.");
            }
        }

        // --- lanjut proses kirim ulang ---
        $row = $this->model->where('idtransaksi', $idtransaksi)->first();

        if (!$row) {
            return $this->failNotFound("Data dengan idtransaksi $idtransaksi tidak ditemukan.");
        }

        $endpoint = 'https://api.simrs.local/pengiriman-data'; // endpoint SIMRS
        $payload = json_encode($row, JSON_PRETTY_PRINT);

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
            // update status pengiriman
            $this->model->where('idtransaksi', $idtransaksi)
                ->set(['status' => 'Terkirim', 'updated_at' => date('Y-m-d H:i:s')])
                ->update();

            // 🔥 tambahan: update status kunjungan jika ada id_transaksi
            $kunjunganModel = new \App\Models\KunjunganModel();
            $kunjungan = $kunjunganModel
                ->select('register')
                ->where('idtransaksi', $idtransaksi)
                ->first();

            if ($kunjungan && !empty($kunjungan['register'])) {
                $register = $kunjungan['register'];

                $kunjunganModel
                    ->where('register', $register)
                    ->set(['status' => 'Terdaftar'])
                    ->update();

                log_message('info', "[PENGIRIMAN SIMRS] Update status kunjungan register {$register} -> Terdaftar");
            }

            return $this->respond([
                'status' => 'success',
                'idtransaksi' => $idtransaksi,
                'response' => json_decode($response, true)
            ]);
        }

        return $this->fail([
            'status' => 'failed',
            'message' => 'Gagal mengirim data.',
            'error' => $curlError ?: "HTTP Code: $httpCode"
        ], 500);
    }
}
