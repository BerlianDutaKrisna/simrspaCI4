<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;
use CodeIgniter\Cache\CacheInterface;

class SimrsModel extends Model
{
    protected $client;
    protected $cache;

    public function __construct()
    {
        parent::__construct();
        $this->client = Services::curlrequest();
        $this->cache = Services::cache();
    }

    public function getPemeriksaanPasien(string $norm): array
    {
        $apiURL = "http://10.250.10.107/apibdrs/apibdrs/getPemeriksaanPasien/{$norm}";

        try {
            $response = $this->client->get($apiURL);
            $statusCode = $response->getStatusCode();

            if ($statusCode !== 200) {
                throw new \RuntimeException("HTTP Error: $statusCode");
            }

            $result = json_decode($response->getBody(), true);

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
        } catch (\Exception $e) {
            throw new \RuntimeException("Gagal mengambil data pemeriksaan: " . $e->getMessage());
        }
    }

    public function getKunjunganPasien(string $norm): array
    {
        $tanggalAkhir = date('Y-m-d');
        $tanggalAwal = date('Y-m-d', strtotime('-7 days'));
        $cacheKey = "kunjungan_all_{$tanggalAwal}_{$tanggalAkhir}";

        try {
            // Ambil dari cache
            $result = $this->cache->get($cacheKey);

            // Jika cache tidak ada, ambil dari API dan simpan ke cache
            if (!$result) {
                $result = $this->refreshKunjunganCache($tanggalAwal, $tanggalAkhir, $cacheKey);
            }

            // Cek dan filter data berdasarkan norm
            if (
                isset($result['code']) && $result['code'] == 200 &&
                isset($result['data']) && is_array($result['data'])
            ) {
                $filtered = array_filter($result['data'], fn($item) => isset($item['norm']) && $item['norm'] === $norm);

                // ✅ Jika data norm tidak ditemukan, coba refresh cache
                if (empty($filtered)) {
                    $result = $this->refreshKunjunganCache($tanggalAwal, $tanggalAkhir, $cacheKey);

                    if (
                        isset($result['code']) && $result['code'] == 200 &&
                        isset($result['data']) && is_array($result['data'])
                    ) {
                        $filtered = array_filter($result['data'], fn($item) => isset($item['norm']) && $item['norm'] === $norm);
                    }
                }

                return [
                    'code' => 200,
                    'data' => array_values($filtered),
                ];
            }

            return [
                'code' => $result['code'] ?? 500,
                'data' => [],
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException("Gagal mengambil data kunjungan: " . $e->getMessage());
        }
    }

    private function refreshKunjunganCache(string $tanggalAwal, string $tanggalAkhir, string $cacheKey): array
    {
        $apiURL = "http://10.250.10.107/apibdrs/apibdrs/getKunjunganPasien/{$tanggalAwal}/{$tanggalAkhir}";

        $response = $this->client->get($apiURL);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException("HTTP Error: " . $response->getStatusCode());
        }

        $result = json_decode($response->getBody(), true);

        // Simpan hasil terbaru ke cache (10 menit)
        $this->cache->save($cacheKey, $result, 600);

        return $result;
    }
}
