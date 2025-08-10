<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class Koneksi extends ResourceController
{
    protected $format = 'json';

    // GET /api/koneksi
    public function index()
    {
        $ip1 = 'http://10.250.10.107';
        $ip2 = 'http://172.20.29.240';

        $status1 = $this->pingUrl($ip1);
        $status2 = $this->pingUrl($ip2);

        $message = "Status koneksi:\n";
        $message .= "$ip1 => " . ($status1 ? "Tersambung" : "Tidak tersambung") . "\n";
        $message .= "$ip2 => " . ($status2 ? "Tersambung" : "Tidak tersambung");

        if ($status1 && $status2) {
            return $this->respond([
                'status' => 'success',
                'message' => $message
            ]);
        }

        return $this->respond([
            'status' => 'error',
            'message' => $message
        ], 503); // 503 Service Unavailable
    }

    private function pingUrl(string $url): bool
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return ($httpCode >= 200 && $httpCode < 400);
    }
}
