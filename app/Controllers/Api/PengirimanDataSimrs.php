<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PengirimanDataSimrsModel;
use App\Models\KunjunganModel;

class PengirimanDataSimrs extends ResourceController
{
    protected $modelName = PengirimanDataSimrsModel::class;
    protected $format    = 'json';

    protected $endpoint;

    public function __construct()
    {
        // langsung hardcode endpoint SIMRS
        $this->endpoint = 'http://172.20.29.240/apibdrs/apibdrs/postPemeriksaan';
    }

    /**
     * Halaman index
     */
    public function index()
    {
        $allData = $this->model->getPengirimanData();

        $data = [
            'id_user'   => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'data'      => $allData
        ];
<<<<<<< HEAD

=======
        
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
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

        $client  = \Config\Services::curlrequest();
        $results = [];

        foreach ($dataBelumTerkirim as $row) {
            try {
                $response = $client->post($this->endpoint, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept'       => 'application/json'
                    ],
                    'json' => $row // langsung array, otomatis dikonversi ke JSON
                ]);

                $statusCode = $response->getStatusCode();
                $body       = $response->getBody();

                if ($statusCode === 200) {
                    // update status
                    $this->model->update($row['id'], [
                        'status'     => 'Terkirim',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    log_message('info', "[PENGIRIMAN SIMRS] BERHASIL (ID: {$row['id']}): $body");

                    $results[] = [
                        'id'       => $row['id'],
                        'status'   => 'success',
                        'payload'  => $row,
                        'response' => json_decode($body, true)
                    ];
                } else {
                    log_message('error', "[PENGIRIMAN SIMRS] Gagal (ID: {$row['id']}), StatusCode: $statusCode, Response: $body");

                    $results[] = [
                        'id'       => $row['id'],
                        'status'   => 'failed',
                        'payload'  => $row,
                        'response' => $body
                    ];
                }
            } catch (\Exception $e) {
                log_message('error', "[PENGIRIMAN SIMRS] Exception (ID: {$row['id']}): " . $e->getMessage());

                $results[] = [
                    'id'     => $row['id'],
                    'status' => 'failed',
                    'error'  => $e->getMessage(),
                    'payload' => $row
                ];
            }
        }

        return $this->respond([
            'status'        => 'done',
            'total_dikirim' => count($results),
            'detail'        => $results
        ]);
    }

    /**
     * Kirim ulang data berdasarkan idtransaksi atau norm
     */
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

            $kunjunganModel = new KunjunganModel();
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

        $row = $this->model->where('idtransaksi', $idtransaksi)->first();

        if (!$row) {
            return $this->failNotFound("Data dengan idtransaksi $idtransaksi tidak ditemukan.");
        }

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post($this->endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json'
                ],
                'json' => $row
            ]);

            $statusCode = $response->getStatusCode();
            $body       = $response->getBody();

            if ($statusCode === 200) {
                // update status pengiriman
                $this->model->where('idtransaksi', $idtransaksi)
                    ->set(['status' => 'Terkirim', 'updated_at' => date('Y-m-d H:i:s')])
                    ->update();

                // update status kunjungan juga
                $kunjunganModel = new KunjunganModel();
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
                    'status'      => 'success',
                    'idtransaksi' => $idtransaksi,
                    'response'    => json_decode($body, true)
                ]);
            }

            return $this->fail([
                'status'  => 'failed',
                'message' => 'Gagal mengirim data.',
                'error'   => "HTTP Code: $statusCode",
                'body'    => $body
            ], 500);
        } catch (\Exception $e) {
            return $this->fail([
                'status'  => 'failed',
                'message' => 'Exception saat kirim data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
