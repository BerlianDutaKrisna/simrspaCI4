<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class SimrsModel extends Model
{
    protected $client;
    protected $primaryBaseURL = "http://172.20.29.240/apibdrs/apibdrs";
    protected $backupBaseURL  = "http://10.250.10.107/apibdrs/apibdrs";

    public function __construct()
    {
        parent::__construct();
        $this->client = Services::curlrequest();
    }

    /**
     * Ambil data dengan cache dan fallback
     */
    protected function getDataWithCache(string $cacheKey, string $primaryURL, string $backupURL): array
    {
        $cache = cache();

        // 1. Cek cache dulu
        if ($cached = $cache->get($cacheKey)) {
            return $cached;
        }

        // 2. Coba ambil dari primary API
        try {
            $response = $this->client->get($primaryURL, ['timeout' => 3, 'connect_timeout' => 2]);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $cache->save($cacheKey, $data, 300); // simpan cache 5 menit
                return $data;
            }
        } catch (\Exception $e) {
            // gagal ambil dari primary
        }

        // 3. Coba ambil dari backup API
        try {
            $response = $this->client->get($backupURL, ['timeout' => 3, 'connect_timeout' => 2]);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $cache->save($cacheKey, $data, 300);
                return $data;
            }
        } catch (\Exception $e) {
            // gagal ambil dari backup juga
        }

        return [
            'code' => 500,
            'data' => [],
            'message' => 'Gagal mengambil data dari API maupun cache'
        ];
    }

    public function getPemeriksaanPasien(string $norm): array
    {
        $cacheKey   = "pemeriksaan_{$norm}";
        $primaryURL = "{$this->primaryBaseURL}/getPemeriksaanPasien/{$norm}";
        $backupURL  = "{$this->backupBaseURL}/getPemeriksaanPasien/{$norm}";

        $result = $this->getDataWithCache($cacheKey, $primaryURL, $backupURL);

        if (
            isset($result['code']) && $result['code'] == 200 &&
            isset($result['data']) && is_array($result['data'])
        ) {
            return [
                'code' => 200,
                'data' => array_values($result['data']),
            ];
        }

        return [
            'code' => $result['code'] ?? 500,
            'data' => [],
        ];
    }

    public function getKunjunganPasien(string $norm): array
    {
        $tanggalAkhir = date('Y-m-d');
        $tanggalAwal  = date('Y-m-d', strtotime('-3 days'));

        $cacheKey   = "kunjungan_{$norm}_{$tanggalAwal}_{$tanggalAkhir}";
        $primaryURL = "{$this->primaryBaseURL}/getKunjunganPasien/{$tanggalAwal}/{$tanggalAkhir}";
        $backupURL  = "{$this->backupBaseURL}/getKunjunganPasien/{$tanggalAwal}/{$tanggalAkhir}";

        $result = $this->getDataWithCache($cacheKey, $primaryURL, $backupURL);

        if (
            isset($result['code']) && $result['code'] == 200 &&
            isset($result['data']) && is_array($result['data'])
        ) {
            $filtered = array_filter($result['data'], fn($item) => isset($item['norm']) && $item['norm'] === $norm);

            return [
                'code' => 200,
                'data' => array_values($filtered),
            ];
        }

        return [
            'code' => $result['code'] ?? 500,
            'data' => [],
        ];
    }

    public function getKunjunganHariIni(): array
    {
        $tanggal = date('Y-m-d');

        $cacheKey   = "kunjungan_hari_ini_{$tanggal}";
        $primaryURL = "{$this->primaryBaseURL}/getKunjunganPasien/{$tanggal}/{$tanggal}";
        $backupURL  = "{$this->backupBaseURL}/getKunjunganPasien/{$tanggal}/{$tanggal}";

        $result = $this->getDataWithCache($cacheKey, $primaryURL, $backupURL);

        if (
            isset($result['code']) && $result['code'] == 200 &&
            isset($result['data']) && is_array($result['data'])
        ) {
            return [
                'code' => 200,
                'data' => array_values($result['data']),
            ];
        }

        return [
            'code' => $result['code'] ?? 500,
            'data' => [],
        ];
    }
}
