<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Services;
use CodeIgniter\Cache\CacheInterface;

class SimrsModel extends Model
{
    protected $client;
    protected $cache;

    // Endpoint URL dasar untuk kunjungan dan pemeriksaan
    protected $primaryBaseURL = "http://10.250.10.107/apibdrs/apibdrs";
    protected $backupBaseURL = "http://172.20.29.240/apibdrs/apibdrs";

    public function __construct()
    {
        parent::__construct();
        $this->client = Services::curlrequest();
        $this->cache = Services::cache();
    }

    protected function getDataWithCache(string $cacheKey, string $primaryURL, string $backupURL, int $cacheTTL = 600): array
    {
        // Cek cache dulu
        $result = $this->cache->get($cacheKey);

        if (!$result) {
            try {
                $response = $this->client->get($primaryURL);
                if ($response->getStatusCode() !== 200) {
                    throw new \RuntimeException("HTTP Error Primary: " . $response->getStatusCode());
                }
            } catch (\Exception $e) {
                // Coba ke backup
                try {
                    $response = $this->client->get($backupURL);
                    if ($response->getStatusCode() !== 200) {
                        throw new \RuntimeException("HTTP Error Backup: " . $response->getStatusCode());
                    }
                } catch (\Exception $ex) {
                    throw new \RuntimeException("Gagal mengakses kedua endpoint: Primary({$e->getMessage()}), Backup({$ex->getMessage()})");
                }
            }

            $result = json_decode($response->getBody(), true);

            // Simpan ke cache
            $this->cache->save($cacheKey, $result, $cacheTTL);
        }

        return $result;
    }

    public function getPemeriksaanPasien(string $norm): array
    {
        $cacheKey = "pemeriksaan_{$norm}";
        $primaryURL = "{$this->primaryBaseURL}/getPemeriksaanPasien/{$norm}";
        $backupURL = "{$this->backupBaseURL}/getPemeriksaanPasien/{$norm}";

        try {
            $result = $this->getDataWithCache($cacheKey, $primaryURL, $backupURL);
        } catch (\Exception $e) {
            throw new \RuntimeException("Gagal mengambil data pemeriksaan: " . $e->getMessage());
        }

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
        $tanggalAwal = date('Y-m-d', strtotime('-3 days'));
        $cacheKey = "kunjungan_all_{$tanggalAwal}_{$tanggalAkhir}";

        $primaryURL = "{$this->primaryBaseURL}/getKunjunganPasien/{$tanggalAwal}/{$tanggalAkhir}";
        $backupURL = "{$this->backupBaseURL}/getKunjunganPasien/{$tanggalAwal}/{$tanggalAkhir}";

        try {
            $result = $this->getDataWithCache($cacheKey, $primaryURL, $backupURL);
        } catch (\Exception $e) {
            throw new \RuntimeException("Gagal mengambil data kunjungan: " . $e->getMessage());
        }

        if (
            isset($result['code']) && $result['code'] == 200 &&
            isset($result['data']) && is_array($result['data'])
        ) {
            $filtered = array_filter($result['data'], fn($item) => isset($item['norm']) && $item['norm'] === $norm);

            // Jika data tidak ditemukan, coba refresh cache ulang
            if (empty($filtered)) {
                $this->cache->delete($cacheKey); // hapus cache lama
                $result = $this->getDataWithCache($cacheKey, $primaryURL, $backupURL);
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
    }

    public function getKunjunganHariIni(): array
    {
        $tanggal = date('Y-m-d'); // hanya hari ini
        $cacheKey = "kunjungan_all_{$tanggal}";

        $primaryURL = "{$this->primaryBaseURL}/getKunjunganPasien/{$tanggal}/{$tanggal}";
        $backupURL  = "{$this->backupBaseURL}/getKunjunganPasien/{$tanggal}/{$tanggal}";

        try {
            $result = $this->getDataWithCache($cacheKey, $primaryURL, $backupURL);
        } catch (\Exception $e) {
            throw new \RuntimeException("Gagal mengambil data kunjungan: " . $e->getMessage());
        }

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
