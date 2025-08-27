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

    public function indexAll()
    {
        $allData = $this->model->getKunjunganTable();

        $data = [
            'id_user'   => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'data'      => $allData
        ];

        return view('Kunjungan/index_all', $data);
    }

    public function modal_search($norm = null)
    {
        if (!$norm) {
            return $this->respond([
                'status' => 'error',
                'message' => 'No RM pasien tidak diberikan'
            ], 400);
        }

        // Cari pasien berdasarkan kolom 'norm' di tabel 'kunjungan'
        $kunjungan = $this->model->where('norm', $norm)->findAll(); // gunakan findAll() untuk array

        if (!empty($kunjungan)) {
            return $this->respond([
                'status' => 'success',
                'data' => $kunjungan // langsung kirim array pasien
            ]);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'Pasien belum mendaftar pada loket / Server mati'
            ], 404);
        }
    }

    // POST API DARI SIMRS
    public function store()
    {
        $input = $this->request->getJSON(true);

        if (!$input || !is_array($input)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Tidak ada data yang dikirim'
            ], 400);
        }

        // Daftar harga berdasarkan jenis pemeriksaan
        $hargaPemeriksaan = [
            'KEROKAN*'                        => 589000,
            'EKSTERPASI TUMOR JINAK*'         => 911000,
            'KOLEKSISTEKTOMI*'                => 764000,
            'KISTEKTOMI*'                     => 756000,
            'TIROIDEKTOMI*'                   => 913000,
            'TAH - BSO*'                      => 1343000,
            'MASTEKTOMI*'                     => 1498000,
            'MIOMEKTOMI*'                     => 779000,
            'OMENTEKTOMI*'                    => 720000,
            'Reseksi Usus'                    => 1371000,
            'Biopsi beberapa tempat'          => 707000,
            'Kerokan + Biopsi'                => 586000,
            'APPENDIKTOMI*'                   => 758000,
            'BIOPSI*'                         => 589000,
            'Potong Beku (VC) biasa'          => 1250000,
            'FNAB*'                           => 667000,
            'FNAB DENGAN TUNTUTAN CT SCAN *'  => 960000,
            'SITOLOGI*'                       => 433000,
            'Pap Smear'                       => 228000,
            'IMMUNOHISTOKIMIA per Antibody'   => 564000,
            'PEMBANCAAN ULANG (REVISI HASIL)' => 250000,
        ];

        $toInsert = [];
        $updateCount = 0;
        $insertCount = 0;

        foreach ($input as $row) {
            if (!isset($row['idtransaksi'])) continue;

            // Cek apakah data dengan idtransaksi sudah ada
            $exists = $this->model->where('idtransaksi', $row['idtransaksi'])->first();

            // Tentukan status berdasarkan ada/tidaknya hasil
            $row['status'] = empty($row['hasil']) ? 'Belum Terdaftar' : 'Terdaftar';

            // Tentukan nilai tagihan
            $tagihan = 0;
            if (!empty($row['pemeriksaan']) && isset($hargaPemeriksaan[$row['pemeriksaan']])) {
                $tagihan = $hargaPemeriksaan[$row['pemeriksaan']];
            } else {
                $tagihan = $row['tagihan'] ?? 0;
            }

            // Susun data untuk insert/update
            $data = [
                'idtransaksi'      => $row['idtransaksi'],
                'tanggal'          => $row['tanggal'] ?? null,
                'idpasien'         => $row['idpasien'] ?? null,
                'norm'             => $row['norm'] ?? null,
                'nama'             => $row['nama'] ?? null,
                'tgl_lhr'          => $row['tgl_lhr'] ?? null,
                'pasien_usia'      => $row['pasien_usia'] ?? null,
                'beratbadan'       => $row['beratbadan'] ?? null,
                'tinggibadan'      => $row['tinggibadan'] ?? null,
                'alamat'           => $row['alamat'] ?? null,
                'jeniskelamin'     => $row['jeniskelamin'] ?? null,
                'kota'             => $row['kota'] ?? null,
                'jenispasien'      => $row['jenispasien'] ?? null,
                'iddokterperujuk'  => $row['iddokterperujuk'] ?? null,
                'dokterperujuk'    => $row['dokterperujuk'] ?? null,
                'iddokterpa'       => $row['iddokterpa'] ?? null,
                'dokterpa'         => $row['dokterpa'] ?? null,
                'pelayananasal'    => $row['pelayananasal'] ?? null,
                'idunitasal'       => $row['idunitasal'] ?? null,
                'unitasal'         => $row['unitasal'] ?? null,
                'register'         => $row['register'] ?? null,
                'pemeriksaan'      => $row['pemeriksaan'] ?? null,
                'statuslokasi'     => $row['statuslokasi'] ?? null,
                'diagnosaklinik'   => $row['diagnosaklinik'] ?? null,
                'tagihan'          => $tagihan,
                'status'           => $row['status'],
            ];

            // Update atau Insert
            if ($exists) {
                $this->model->update($exists['id'], $data);
                $updateCount++;
            } else {
                $toInsert[] = $data;
            }
        }

        // Jika ada data baru, lakukan insert batch
        if (!empty($toInsert)) {
            $this->model->insertBatch($toInsert);
            $insertCount = count($toInsert);
        }

        return $this->respond([
            'status'       => 'success',
            'inserted'     => $insertCount,
            'updated'      => $updateCount,
            'total_data'   => count($input),
            'message'      => "{$insertCount} data baru berhasil ditambahkan, {$updateCount} data diperbarui"
        ], 200);
    }

    // DELETE API DARI SIMRS
    public function delete($idtransaksi = null)
    {
        if (!$idtransaksi) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'ID transaksi tidak diberikan'
            ], 400);
        }

        $record = $this->model->where('idtransaksi', $idtransaksi)->first();

        if (!$record) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $this->model->delete($record['id']);
        return $this->respond([
            'status'  => 'success',
            'message' => "Data dengan idtransaksi $idtransaksi berhasil dihapus"
        ], 200);
    }

    // UPDATE API DARI SIMRS
    public function update($idtransaksi = null)
    {
        // Cek apakah idtransaksi diberikan
        if (!$idtransaksi) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'ID transaksi tidak diberikan'
            ], 400);
        }

        // Ambil JSON dari body
        $input = $this->request->getJSON(true);

        if (!$input || !is_array($input)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Tidak ada data yang dikirim'
            ], 400);
        }

        // Daftar harga berdasarkan jenis pemeriksaan
        $hargaPemeriksaan = [
            'KEROKAN*'                        => 589000,
            'EKSTERPASI TUMOR JINAK*'         => 911000,
            'KOLEKSISTEKTOMI*'                => 764000,
            'KISTEKTOMI*'                     => 756000,
            'TIROIDEKTOMI*'                   => 913000,
            'TAH - BSO*'                      => 1343000,
            'MASTEKTOMI*'                     => 1498000,
            'MIOMEKTOMI*'                     => 779000,
            'OMENTEKTOMI*'                    => 720000,
            'Reseksi Usus'                    => 1371000,
            'Biopsi beberapa tempat'          => 707000,
            'Kerokan + Biopsi'                => 586000,
            'APPENDIKTOMI*'                   => 758000,
            'BIOPSI*'                         => 589000,
            'Potong Beku (VC) biasa'          => 1250000,
            'FNAB*'                           => 667000,
            'FNAB DENGAN TUNTUTAN CT SCAN *'  => 960000,
            'SITOLOGI*'                       => 433000,
            'Pap Smear'                       => 228000,
            'IMMUNOHISTOKIMIA per Antibody'   => 564000,
            'PEMBANCAAN ULANG (REVISI HASIL)' => 250000,
        ];

        // Cari record berdasarkan idtransaksi
        $record = $this->model->where('idtransaksi', $idtransaksi)->first();

        if (!$record) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Tentukan status berdasarkan ada/tidaknya hasil
        $input['status'] = empty($input['hasil']) ? 'Belum Terdaftar' : 'Terdaftar';

        // Tentukan nilai tagihan
        $tagihan = 0;
        if (!empty($input['pemeriksaan']) && isset($hargaPemeriksaan[$input['pemeriksaan']])) {
            $tagihan = $hargaPemeriksaan[$input['pemeriksaan']];
        } else {
            $tagihan = $input['tagihan'] ?? $record['tagihan'];
        }

        // Susun data update
        $data = [
            'tanggal'         => $input['tanggal'] ?? $record['tanggal'],
            'idpasien'        => $input['idpasien'] ?? $record['idpasien'],
            'norm'            => $input['norm'] ?? $record['norm'],
            'nama'            => $input['nama'] ?? $record['nama'],
            'tgl_lhr'         => $input['tgl_lhr'] ?? $record['tgl_lhr'],
            'pasien_usia'     => $input['pasien_usia'] ?? $record['pasien_usia'],
            'beratbadan'      => $input['beratbadan'] ?? $record['beratbadan'],
            'tinggibadan'     => $input['tinggibadan'] ?? $record['tinggibadan'],
            'alamat'          => $input['alamat'] ?? $record['alamat'],
            'jeniskelamin'    => $input['jeniskelamin'] ?? $record['jeniskelamin'],
            'kota'            => $input['kota'] ?? $record['kota'],
            'jenispasien'     => $input['jenispasien'] ?? $record['jenispasien'],
            'iddokterperujuk' => $input['iddokterperujuk'] ?? $record['iddokterperujuk'],
            'dokterperujuk'   => $input['dokterperujuk'] ?? $record['dokterperujuk'],
            'iddokterpa'      => $input['iddokterpa'] ?? $record['iddokterpa'],
            'dokterpa'        => $input['dokterpa'] ?? $record['dokterpa'],
            'pelayananasal'   => $input['pelayananasal'] ?? $record['pelayananasal'],
            'idunitasal'      => $input['idunitasal'] ?? $record['idunitasal'],
            'unitasal'        => $input['unitasal'] ?? $record['unitasal'],
            'register'        => $input['register'] ?? $record['register'],
            'pemeriksaan'     => $input['pemeriksaan'] ?? $record['pemeriksaan'],
            'statuslokasi'    => $input['statuslokasi'] ?? $record['statuslokasi'],
            'diagnosaklinik'  => $input['diagnosaklinik'] ?? $record['diagnosaklinik'],
            'hasil'           => $input['hasil'] ?? $record['hasil'],
            'diagnosapatologi' => $input['diagnosapatologi'] ?? $record['diagnosapatologi'],
            'mutusediaan'     => $input['mutusediaan'] ?? $record['mutusediaan'],
            'tagihan'         => $tagihan,
            'status'          => $input['status'] ?? $record['status']
        ];

        // Update data
        $this->model->update($record['id'], $data);

        return $this->respond([
            'status'  => 'success',
            'message' => "Data dengan idtransaksi $idtransaksi berhasil diperbarui"
        ], 200);
    }

    public function getKunjunganHariIni()
    {
        try {
            $data = $this->model->getKunjunganHariIni();

            if (($data['code'] ?? null) == 200 && !empty($data['data'])) {

                $kunjunganDB = new \App\Models\KunjunganModel();
                $toInsert = [];

                // Mapping pemeriksaan → tagihan
                $pembayaranMapping = [
                    'KEROKAN*'                        => 589000,
                    'EKSTERPASI TUMOR JINAK*'         => 911000,
                    'KOLEKSISTEKTOMI*'                => 764000,
                    'KISTEKTOMI*'                     => 756000,
                    'TIROIDEKTOMI*'                   => 913000,
                    'TAH - BSO*'                      => 1343000,
                    'MASTEKTOMI*'                     => 1498000,
                    'MIOMEKTOMI*'                     => 779000,
                    'OMENTEKTOMI*'                    => 720000,
                    'Reseksi Usus'                    => 1371000,
                    'Biopsi beberapa tempat'          => 707000,
                    'Kerokan + Biopsi'                => 586000,
                    'APPENDIKTOMI*'                   => 758000,
                    'BIOPSI*'                         => 589000,
                    'Potong Beku (VC) biasa'          => 1250000,
                    'FNAB*'                           => 667000,
                    'FNAB DENGAN TUNTUTAN CT SCAN *'  => 960000,
                    'SITOLOGI*'                       => 433000,
                    'Pap Smear'                       => 228000,
                    'IMMUNOHISTOKIMIA per Antibody'   => 564000,
                    'PEMBANCAAN ULANG (REVISI HASIL)' => 250000,
                ];

                foreach ($data['data'] as $row) {
                    try {
                        if (!isset($row['idtransaksi'])) continue;

                        $idtransaksi = $row['idtransaksi'];

                        // Cek apakah idtransaksi sudah ada
                        $exists = $kunjunganDB->where('idtransaksi', $idtransaksi)->first();

                        // Tentukan tagihan
                        $pemeriksaan = $row['pemeriksaan'] ?? '';
                        $tagihan = 0;
                        foreach ($pembayaranMapping as $key => $value) {
                            if (stripos($pemeriksaan, $key) !== false) {
                                $tagihan = $value;
                                break;
                            }
                        }

                        // Siapkan data untuk insert
                        $rowData = [
                            'idtransaksi'      => $idtransaksi,
                            'tanggal'          => $row['tanggal'] ?? null,
                            'idpasien'         => $row['idpasien'] ?? null,
                            'norm'             => $row['norm'] ?? null,
                            'nama'             => $row['nama'] ?? null,
                            'tgl_lhr'          => $row['tgl_lhr'] ?? null,
                            'pasien_usia'      => $row['pasien_usia'] ?? null,
                            'beratbadan'       => $row['beratbadan'] ?? null,
                            'tinggibadan'      => $row['tinggibadan'] ?? null,
                            'alamat'           => $row['alamat'] ?? null,
                            'jeniskelamin'     => $row['jeniskelamin'] ?? null,
                            'kota'             => $row['kota'] ?? null,
                            'jenispasien'      => $row['jenispasien'] ?? null,
                            'iddokterperujuk'  => $row['iddokterperujuk'] ?? null,
                            'dokterperujuk'    => $row['dokterperujuk'] ?? null,
                            'iddokterpa'       => $row['iddokterpa'] ?? null,
                            'dokterpa'         => $row['dokterpa'] ?? null,
                            'pelayananasal'    => $row['pelayananasal'] ?? null,
                            'idunitasal'       => $row['idunitasal'] ?? null,
                            'unitasal'         => $row['unitasal'] ?? null,
                            'register'         => $row['register'] ?? null,
                            'pemeriksaan'      => $pemeriksaan,
                            'status'           => (empty($row['hasil']) ? 'Belum Terdaftar' : 'Terdaftar'), // <- kondisi hasil
                            'diagnosaklinik'   => $row['diagnosaklinik'] ?? null,
                            'diagnosapatologi' => $row['diagnosapatologi'] ?? null,
                            'mutusediaan'      => $row['mutusediaan'] ?? null,
                            'tagihan'          => $tagihan,
                        ];

                        if (!$exists) {
                            $toInsert[] = $rowData;
                        } else {
                            // Update tagihan jika sudah ada
                            $kunjunganDB->where('idtransaksi', $idtransaksi)
                                ->set('tagihan', $tagihan)
                                ->update();
                        }
                    } catch (\Exception $e) {
                        log_message('error', "Gagal memproses idtransaksi {$row['idtransaksi']}: {$e->getMessage()}");
                    }
                }

                $insertCount = 0;
                if (!empty($toInsert)) {
                    $kunjunganDB->insertBatch($toInsert);
                    $insertCount = count($toInsert);
                }

                return $this->respond([
                    'status'    => 'success',
                    'inserted'  => $insertCount,
                    'total_api' => count($data['data']),
                    'message'   => "{$insertCount} data baru berhasil dimasukkan"
                ]);
            }

            return $this->respond([
                'status' => 'success',
                'data'   => 'Data kunjungan hari ini tidak ditemukan'
            ]);
        } catch (\Exception $e) {
            log_message('error', "Sinkronisasi gagal: {$e->getMessage()}");
            return $this->failServerError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
