<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\KunjunganModel;

class Kunjungan extends ResourceController
{
    protected $modelName = KunjunganModel::class;
    protected $format    = 'json';

    public function index()
    {
        $allData = $this->model->getKunjunganHariIniTable();

        $data = [
            'id_user'   => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'data'      => $allData
        ];

        return view('Kunjungan/index', $data);
    }

    public function search_patient()
    {
        $norm = $this->request->getPost('norm');

        // Langsung pakai $this->model
        $patient = $this->model
            ->where('norm', $norm)
            ->orderBy('tanggal', 'DESC')
            ->first();

        if ($patient) {
            return view('patient_search_result', ['patient' => $patient]);
        } else {
            return view('patient_search_result', ['error' => 'Patient not found']);
        }
    }

    public function getKunjunganHariIni()
    {
        try {
            $data = $this->model->getKunjunganHariIni();

            if (isset($data['code']) && $data['code'] == 200 && !empty($data['data'])) {

                // Load model kunjungan DB
                $kunjunganDB = new \App\Models\KunjunganModel();

                $insertCount = 0;
                foreach ($data['data'] as $row) {
                    // Pastikan idtransaksi ada di data API
                    if (!isset($row['idtransaksi'])) {
                        continue;
                    }

                    // Cek apakah idtransaksi sudah ada
                    $exists = $kunjunganDB
                        ->where('idtransaksi', $row['idtransaksi'])
                        ->first();

                    if (!$exists) {
                        // Insert data baru
                        $kunjunganDB->insert([
                            'idtransaksi'        => $row['idtransaksi'],
                            'tanggal'            => $row['tanggal'] ?? null,
                            'idpasien'           => $row['idpasien'] ?? null,
                            'norm'               => $row['norm'] ?? null,
                            'nama'               => $row['nama'] ?? null,
                            'tgl_lhr'            => $row['tgl_lhr'] ?? null,
                            'pasien_usia'        => $row['pasien_usia'] ?? null,
                            'beratbadan'         => $row['beratbadan'] ?? null,
                            'tinggibadan'        => $row['tinggibadan'] ?? null,
                            'alamat'             => $row['alamat'] ?? null,
                            'jeniskelamin'       => $row['jeniskelamin'] ?? null,
                            'kota'               => $row['kota'] ?? null,
                            'jenispasien'        => $row['jenispasien'] ?? null,
                            'iddokterperujuk'    => $row['iddokterperujuk'] ?? null,
                            'dokterperujuk'      => $row['dokterperujuk'] ?? null,
                            'iddokterpa'         => $row['iddokterpa'] ?? null,
                            'dokterpa'           => $row['dokterpa'] ?? null,
                            'pelayananasal'      => $row['pelayananasal'] ?? null,
                            'idunitasal'         => $row['idunitasal'] ?? null,
                            'unitasal'           => $row['unitasal'] ?? null,
                            'register'           => $row['register'] ?? null,
                            'pemeriksaan'        => $row['pemeriksaan'] ?? null,
                            'responsetime'       => $row['responsetime'] ?? null,
                            'statuslokasi'       => $row['statuslokasi'] ?? null,
                            'diagnosaklinik'     => $row['diagnosaklinik'] ?? null,
                            'hasil'              => $row['hasil'] ?? null,
                            'diagnosapatologi'   => $row['diagnosapatologi'] ?? null,
                            'mutusediaan'        => $row['mutusediaan'] ?? null,
                        ]);
                        $insertCount++;
                    }
                }

                return $this->respond([
                    'status'       => 'success',
                    'inserted'     => $insertCount,
                    'total_api'    => count($data['data']),
                    'message'      => "{$insertCount} data baru berhasil dimasukkan"
                ]);
            }

            return $this->respond([
                'status' => 'success',
                'data'   => 'Data kunjungan hari ini tidak ditemukan'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
