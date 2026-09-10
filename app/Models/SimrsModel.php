<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class SimrsModel extends Model
{
    protected $client;
    protected $primaryBaseURL = "http://172.20.29.240/apibdrs/apibdrs";

    public function __construct()
    {
        parent::__construct();
        $this->client = Services::curlrequest();
    }

    /**
     * Ambil data dengan cache (tanpa backup)
     */
    protected function getDataWithCache(string $cacheKey, string $primaryURL): array
    {
        $cache = cache();

        // 1. Cek cache dulu
        if ($cached = $cache->get($cacheKey)) {
            return is_array($cached) ? $cached : [];
        }

        // 2. Coba ambil dari primary API
        try {
            $response = $this->client->get($primaryURL, ['timeout' => 3, 'connect_timeout' => 2]);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true) ?? [];
                $cache->save($cacheKey, $data, 300); // simpan cache 5 menit
                return $data;
            }
        } catch (\Exception $e) {
            log_message('error', "[SIMRS] Primary API error: " . $e->getMessage());
        }

        // 3. Fallback tetap array
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

        $result = $this->getDataWithCache($cacheKey, $primaryURL);

        if (
            isset($result['code']) && $result['code'] == 200 &&
            isset($result['data']) && is_array($result['data'])
        ) {
            // Normalize each permintaan entry for easier consumption by controllers/views
            $entries = array_values($result['data']);
            foreach ($entries as $i => $entry) {
                if (is_array($entry)) {
                    $entries[$i] = $this->normalizePermintaanEntry($entry);
                }
            }

            return [
                'code' => 200,
                'data' => $entries,
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

        $result = $this->getDataWithCache($cacheKey, $primaryURL);

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

        $result = $this->getDataWithCache($cacheKey, $primaryURL);

        if (
            isset($result['code']) && $result['code'] == 200 &&
            isset($result['data']) && is_array($result['data'])
        ) {
            $entries = array_values($result['data']);
            foreach ($entries as $i => $entry) {
                if (is_array($entry)) {
                    $entries[$i] = $this->normalizePermintaanEntry($entry);
                }
            }

            return [
                'code' => 200,
                'data' => $entries,
            ];
        }

        return [
            'code' => $result['code'] ?? 500,
            'data' => [],
        ];
    }

    /**
     * Normalize a permintaan entry from SIMRS API:
     * - decode `formrm_json` into `formrm`
     * - normalize base64 images (canvas, signatures)
     * - promote common fields from `formrm` to top-level when missing
     */
    protected function normalizePermintaanEntry(array $entry): array
    {
        // Decode formrm_json if present
        if (!empty($entry['formrm_json']) && is_string($entry['formrm_json'])) {
            $decoded = json_decode($entry['formrm_json'], true);
            if (is_array($decoded)) {
                $entry['formrm'] = $decoded;
            }
        }

        // Struktur API: canvas & signature ada di dalam formrm_json > permintaanpatologianatomi_data.
        // Promote nested object ini ke top-level entry agar proses normalisasi berikutnya bekerja.
        if (
            empty($entry['permintaanpatologianatomi_data']) &&
            !empty($entry['formrm']['permintaanpatologianatomi_data']) &&
            is_array($entry['formrm']['permintaanpatologianatomi_data'])
        ) {
            $entry['permintaanpatologianatomi_data'] = $entry['formrm']['permintaanpatologianatomi_data'];
        }

        // Helper to extract/clean image-like values
        $cleanImage = function ($val) {
            if (empty($val)) return '';

            // Step 1: Unescape dulu (JSON encoding dari API sering menghasilkan \/ atau \\/)
            $val = trim($val, "\"' \t\n\r");
            $val = stripslashes($val);
            $val = str_replace('\\/', '/', $val);
            $val = str_replace('\\\\/', '/', $val);

            // Step 2: Ekstrak data URI setelah cleanup (gunakan delimiter # agar tidak konflik dengan /)
            if (preg_match('#(data:image/[a-zA-Z0-9+]+;base64,[A-Za-z0-9+/=\s]+)#', $val, $m)) {
                $val = $m[1];
            }

            // Step 3: Hapus whitespace dari string base64
            $val = preg_replace('/\s+/', '', $val);

            // Step 4: Jika raw base64 tanpa prefix data URI, tambahkan prefix PNG
            if (strpos($val, 'data:image') !== 0 && preg_match('/^[A-Za-z0-9+\/=]+$/', $val)) {
                $val = 'data:image/png;base64,' . $val;
            }

            return $val;
        };

        // Normalize canvas fields (several possible keys)
        $canvasKeys = ['canvasPatologianatomi', 'canvas_patologianatomi', 'canvas'];
        foreach ($canvasKeys as $k) {
            if (!empty($entry[$k])) {
                $entry[$k] = $cleanImage($entry[$k]);
            } elseif (!empty($entry['formrm'][$k] ?? null)) {
                $entry[$k] = $cleanImage($entry['formrm'][$k]);
            }
        }

        // If API nests permintaanpatologianatomi_data, extract images/signature from there
        if (!empty($entry['permintaanpatologianatomi_data']) && is_array($entry['permintaanpatologianatomi_data'])) {
            $nested = $entry['permintaanpatologianatomi_data'];
            // common nested keys
            foreach (['dokterSignatureRequest', 'dokter_signature_request', 'canvasPatologianatomi', 'canvas_patologianatomi'] as $nk) {
                if (!empty($nested[$nk])) {
                    // place normalized value at top-level using same key name
                    $entry[$nk] = $cleanImage($nested[$nk]);
                }
            }
            // also flatten any other nested base64-like values
            foreach ($nested as $nk => $nv) {
                if (is_string($nv) && (stripos($nk, 'signature') !== false || stripos($nk, 'canvas') !== false || stripos($nv, 'data:image') !== false)) {
                    $entry[$nk] = $cleanImage($nv);
                }
            }
        }

        // Normalize signature-like fields
        foreach ($entry as $k => $v) {
            if (stripos($k, 'signature') !== false || stripos($k, 'sign') !== false) {
                $entry[$k] = $cleanImage($v);
            }
        }
        // also check formrm for signature
        if (!empty($entry['formrm']) && is_array($entry['formrm'])) {
            foreach ($entry['formrm'] as $k => $v) {
                if ((stripos($k, 'signature') !== false || stripos($k, 'sign') !== false) && empty($entry[$k])) {
                    $entry[$k] = $cleanImage($v);
                }
            }
        }

        // Promote some common patient fields from formrm to top-level if missing
        if (!empty($entry['formrm']) && is_array($entry['formrm'])) {
            foreach (['no_rm', 'nama', 'tgl_lahir', 'jenis_kelamin', 'alamat', 'status_lokalis', 'diagnosis_klinik', 'dpjp_awal'] as $f) {
                if (empty($entry[$f]) && !empty($entry['formrm'][$f])) {
                    $entry[$f] = $entry['formrm'][$f];
                }
            }
        }

        // Promote field klinis dari dalam permintaanpatologianatomi_data ke top-level jika masih kosong
        // (API meletakkan dpjp_awal, status_lokalis, dll. di dalam nested object ini)
        if (!empty($entry['permintaanpatologianatomi_data']) && is_array($entry['permintaanpatologianatomi_data'])) {
            $nested = $entry['permintaanpatologianatomi_data'];
            $klinisFields = [
                'dpjp_awal', 'dpjp_awal_id', 'status_lokalis', 'diagnosis_klinik', 'lain_lain_lab_radiologi', 'ruang_rawat', 'pegawai_nama', 'consentDateRequest', 'consentTimeRequest',
                'spesimen_histopatologi', 'spesimen_sitologi', 'spesimen_imunohistokimia', 'spesimen_patologi_molekuler', 'spesimen_pewarnaan_lain', 'permintaan_review',
                'spesimen_lokasi', 'spesimen_tindakan'
            ];
            foreach ($klinisFields as $f) {
                if (empty($entry[$f]) && !empty($nested[$f])) {
                    $entry[$f] = $nested[$f];
                }
            }
        }

        // Format tanggal consentDateRequest
        $consent_date = $entry['consentDateRequest'] ?? date('Y-m-d');
        $nama_bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
            '04' => 'April', '05' => 'Mei', '06' => 'Juni', 
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September', 
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $tgl_parts = explode('-', $consent_date);
        $entry['formatted_consent_date'] = count($tgl_parts) == 3 
            ? $tgl_parts[2] . ' ' . ($nama_bulan[$tgl_parts[1]] ?? $tgl_parts[1]) . ' ' . $tgl_parts[0] 
            : $consent_date;

        return $entry;
    }

    /**
     * Ambil data permintaan berdasarkan norm dari API SIMRS (override base URL)
     */
    public function getPermintaan(string $norm): array
    {
        $cacheKey   = "permintaan_{$norm}";
        $primaryURL = "http://10.250.10.107/apibdrs/apibdrs/getPermintaan/{$norm}";

        $result = $this->getDataWithCache($cacheKey, $primaryURL);

        if (
            isset($result['code']) && $result['code'] == 200 &&
            isset($result['data']) && is_array($result['data'])
        ) {
            // Normalize setiap entry: ubah base64 menjadi Data URI siap pakai,
            // decode formrm_json, dan promosikan field umum ke top-level.
            $entries = array_values($result['data']);
            foreach ($entries as $i => $entry) {
                if (is_array($entry)) {
                    $entries[$i] = $this->normalizePermintaanEntry($entry);
                }
            }

            return [
                'code' => 200,
                'data' => $entries,
            ];
        }

        return [
            'code' => $result['code'] ?? 500,
            'data' => [],
        ];
    }
}
