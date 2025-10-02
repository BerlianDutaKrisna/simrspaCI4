<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class KunjunganModel extends Model
{
    protected $table            = 'kunjungan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'idtransaksi',
        'tanggal',
        'idpasien',
        'norm',
        'nama',
        'tgl_lhr',
        'pasien_usia',
        'beratbadan',
        'tinggibadan',
        'alamat',
        'jeniskelamin',
        'kota',
        'jenispasien',
        'iddokterperujuk',
        'dokterperujuk',
        'iddokterpa',
        'dokterpa',
        'pelayananasal',
        'idunitasal',
        'unitasal',
        'register',
        'pemeriksaan',
        'statuslokasi',
        'diagnosaklinik',
        'tagihan',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Endpoint URL dasar untuk kunjungan dan pemeriksaan
    protected $primaryBaseURL = "http://10.250.10.107/apibdrs/apibdrs";
    protected $backupBaseURL  = "http://172.20.29.240/apibdrs/apibdrs";

    protected $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = Services::curlrequest();
    }

    public function getKunjunganHariIni(): array
    {
        // ambil tanggal sekarang dan 3 hari ke belakang
        $tanggalAkhir  = date('Y-m-d');
        $tanggalAwal   = date('Y-m-d', strtotime('-3 days'));

        $primaryURL = "{$this->primaryBaseURL}/getKunjunganPasien/{$tanggalAwal}/{$tanggalAkhir}";
        $backupURL  = "{$this->backupBaseURL}/getKunjunganPasien/{$tanggalAwal}/{$tanggalAkhir}";

        try {
            // coba endpoint primary
            try {
                $response = $this->client->get($primaryURL);
                if ($response->getStatusCode() !== 200) {
                    throw new \RuntimeException("HTTP Error Primary: " . $response->getStatusCode());
                }
            } catch (\Exception $e) {
                // kalau gagal, coba ke backup
                $response = $this->client->get($backupURL);
                if ($response->getStatusCode() !== 200) {
                    throw new \RuntimeException("HTTP Error Backup: " . $response->getStatusCode());
                }
            }

            $result = json_decode($response->getBody(), true);
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

    public function getKunjunganHariIniTable()
    {
        return $this->where('DATE(tanggal)', date('Y-m-d'))
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }

    public function getKunjunganTable()
    {
        return $this->orderBy('tanggal', 'DESC')
            ->findAll();
    }
}
