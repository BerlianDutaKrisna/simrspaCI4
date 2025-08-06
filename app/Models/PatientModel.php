<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'patient';
    protected $primaryKey = 'id_pasien';
    protected $returnType = 'array';
    protected $allowedFields = [
        'norm_pasien',
        'nama_pasien',
        'alamat_pasien',
        'tanggal_lahir_pasien',
        'jenis_kelamin_pasien',
        'status_pasien',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengecek apakah NORM sudah ada di database
    public function checkNormExists($norm_pasien)
    {
        return $this->where('norm_pasien', $norm_pasien)->countAllResults() > 0;
    }
    public function deletePatient($id_patient)
    {
        try {
            // Menghapus data berdasarkan ID
            return $this->db->table($this->table)->delete(['id_pasien' => $id_patient]);
        } catch (\Throwable $e) {
            // Menangkap error jika terjadi
            log_message('error', 'Error saat menghapus data pasien: ' . $e->getMessage());
            return false;
        }
    }
    // Mencari pasien berdasarkan norm_pasien
    public function searchByNorm($norm_pasien)
    {
        return $this->where('norm_pasien', $norm_pasien)->first();
    }

    // Mengambil data pasien dengan relasi
    public function getPatientWithRelations()
    {
        $query = "
        SELECT p.id_pasien, 
            p.norm_pasien, 
            p.nama_pasien, 
            p.alamat_pasien, 
            p.tanggal_lahir_pasien, 
            p.jenis_kelamin_pasien, 
            hpa.unit_asal, hpa.dokter_pengirim, hpa.diagnosa_klinik, 
            hpa.kode_hpa AS kode_pemeriksaan, hpa.tanggal_permintaan, 'HPA' AS jenis_pemeriksaan,
            hpa.hasil_hpa AS hasil, hpa.status_hpa AS status, hpa.penerima_hpa AS penerima, hpa.tanggal_penerima
        FROM patient p
        INNER JOIN hpa ON hpa.id_pasien = p.id_pasien 
        WHERE hpa.tanggal_permintaan BETWEEN 
            DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 7) DAY)
            AND 
            DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE()) + 7) DAY)

        UNION ALL

        SELECT p.id_pasien, 
            p.norm_pasien, 
            p.nama_pasien, 
            p.alamat_pasien, 
            p.tanggal_lahir_pasien, 
            p.jenis_kelamin_pasien, 
            frs.unit_asal, frs.dokter_pengirim, frs.diagnosa_klinik, 
            frs.kode_frs AS kode_pemeriksaan, frs.tanggal_permintaan, 'FRS' AS jenis_pemeriksaan,
            frs.hasil_frs AS hasil, frs.status_frs AS status, frs.penerima_frs AS penerima, frs.tanggal_penerima
        FROM patient p
        INNER JOIN frs ON frs.id_pasien = p.id_pasien 
        WHERE frs.tanggal_permintaan BETWEEN 
            DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 7) DAY)
            AND 
            DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE()) + 7) DAY)

        UNION ALL

        SELECT p.id_pasien, 
            p.norm_pasien, 
            p.nama_pasien, 
            p.alamat_pasien, 
            p.tanggal_lahir_pasien, 
            p.jenis_kelamin_pasien, 
            srs.unit_asal, srs.dokter_pengirim, srs.diagnosa_klinik, 
            srs.kode_srs AS kode_pemeriksaan, srs.tanggal_permintaan, 'SRS' AS jenis_pemeriksaan,
            srs.hasil_srs AS hasil, srs.status_srs AS status, srs.penerima_srs AS penerima, srs.tanggal_penerima
        FROM patient p
        INNER JOIN srs ON srs.id_pasien = p.id_pasien 
        WHERE srs.tanggal_permintaan BETWEEN 
            DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 7) DAY)
            AND 
            DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE()) + 7) DAY)

        UNION ALL

        SELECT p.id_pasien, 
            p.norm_pasien, 
            p.nama_pasien, 
            p.alamat_pasien, 
            p.tanggal_lahir_pasien, 
            p.jenis_kelamin_pasien, 
            ihc.unit_asal, ihc.dokter_pengirim, ihc.diagnosa_klinik, 
            ihc.kode_ihc AS kode_pemeriksaan, ihc.tanggal_permintaan, 'IHC' AS jenis_pemeriksaan,
            ihc.hasil_ihc AS hasil, ihc.status_ihc AS status, ihc.penerima_ihc AS penerima, ihc.tanggal_penerima
        FROM patient p
        INNER JOIN ihc ON ihc.id_pasien = p.id_pasien 
        WHERE ihc.tanggal_permintaan BETWEEN 
            DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 7) DAY)
            AND 
            DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE()) + 7) DAY)

        ORDER BY tanggal_permintaan ASC, kode_pemeriksaan ASC;
    ";

        $builder = $this->db->query($query);
        return $builder->getResultArray();
    }


    public function searchPatientsWithRelations($searchField, $searchValue, $startDate, $endDate)
    {

        // Query untuk HPA
        $builder1 = $this->db->table('patient')
            ->select("patient.id_pasien, patient.norm_pasien, patient.nama_pasien, 
                    patient.alamat_pasien, patient.tanggal_lahir_pasien, patient.jenis_kelamin_pasien, 
                    hpa.unit_asal, hpa.dokter_pengirim, hpa.diagnosa_klinik, 
                    hpa.kode_hpa AS kode_pemeriksaan, hpa.tanggal_permintaan, 'HPA' AS jenis_pemeriksaan, 
                    hpa.hasil_hpa AS hasil, hpa.status_hpa AS status, hpa.penerima_hpa AS penerima, 
                    hpa.tanggal_penerima")
            ->join('hpa', 'hpa.id_pasien = patient.id_pasien', 'inner')
            ->where("hpa.tanggal_permintaan >=", $startDate)
            ->where("hpa.tanggal_permintaan <=", $endDate);

        if (!empty($searchField) && !empty($searchValue)) {
            $builder1->where($searchField, $searchValue);
        }
        $subQuery1 = $builder1->getCompiledSelect();


        // Query untuk FRS
        $builder2 = $this->db->table('patient')
            ->select("patient.id_pasien, patient.norm_pasien, patient.nama_pasien, 
                    patient.alamat_pasien, patient.tanggal_lahir_pasien, patient.jenis_kelamin_pasien, 
                    frs.unit_asal, frs.dokter_pengirim, frs.diagnosa_klinik, 
                    frs.kode_frs AS kode_pemeriksaan, frs.tanggal_permintaan, 'FRS' AS jenis_pemeriksaan, 
                    frs.hasil_frs AS hasil, frs.status_frs AS status, frs.penerima_frs AS penerima, 
                    frs.tanggal_penerima")
            ->join('frs', 'frs.id_pasien = patient.id_pasien', 'inner')
            ->where("frs.tanggal_permintaan >=", $startDate)
            ->where("frs.tanggal_permintaan <=", $endDate);

        if (!empty($searchField) && !empty($searchValue)) {
            $builder2->where($searchField, $searchValue);
        }
        $subQuery2 = $builder2->getCompiledSelect();

        // Query untuk SRS
        $builder3 = $this->db->table('patient')
            ->select("patient.id_pasien, patient.norm_pasien, patient.nama_pasien, 
                    patient.alamat_pasien, patient.tanggal_lahir_pasien, patient.jenis_kelamin_pasien, 
                    srs.unit_asal, srs.dokter_pengirim, srs.diagnosa_klinik, 
                    srs.kode_srs AS kode_pemeriksaan, srs.tanggal_permintaan, 'SRS' AS jenis_pemeriksaan, 
                    srs.hasil_srs AS hasil, srs.status_srs AS status, srs.penerima_srs AS penerima, 
                    srs.tanggal_penerima")
            ->join('srs', 'srs.id_pasien = patient.id_pasien', 'inner')
            ->where("srs.tanggal_permintaan >=", $startDate)
            ->where("srs.tanggal_permintaan <=", $endDate);

        if (!empty($searchField) && !empty($searchValue)) {
            $builder3->where($searchField, $searchValue);
        }
        $subQuery3 = $builder3->getCompiledSelect();

        // Query untuk IHC
        $builder4 = $this->db->table('patient')
            ->select("patient.id_pasien, patient.norm_pasien, patient.nama_pasien, 
                    patient.alamat_pasien, patient.tanggal_lahir_pasien, patient.jenis_kelamin_pasien, 
                    ihc.unit_asal, ihc.dokter_pengirim, ihc.diagnosa_klinik, 
                    ihc.kode_ihc AS kode_pemeriksaan, ihc.tanggal_permintaan, 'IHC' AS jenis_pemeriksaan, 
                    ihc.hasil_ihc AS hasil, ihc.status_ihc AS status, ihc.penerima_ihc AS penerima, 
                    ihc.tanggal_penerima")
            ->join('ihc', 'ihc.id_pasien = patient.id_pasien', 'inner')
            ->where("ihc.tanggal_permintaan >=", $startDate)
            ->where("ihc.tanggal_permintaan <=", $endDate);

        if (!empty($searchField) && !empty($searchValue)) {
            $builder4->where($searchField, $searchValue);
        }
        $subQuery4 = $builder4->getCompiledSelect();

        // Gabungkan semua query dengan UNION ALL
        $finalQuery = "($subQuery1) UNION ALL ($subQuery2) UNION ALL ($subQuery3) UNION ALL ($subQuery4) 
                ORDER BY tanggal_permintaan ASC, kode_pemeriksaan ASC";

        return $this->db->query($finalQuery)->getResultArray();
    }

    public function filterPatients($keyword = null, $jenisKelamin = null, $statusPasien = null)
    {
        $builder = $this->table($this->table);

        if ($keyword) {
            $builder->groupStart()
                ->like('norm_pasien', $keyword)
                ->orLike('nama_pasien', $keyword)
                ->orLike('alamat_pasien', $keyword)
                ->groupEnd();
        }

        if ($jenisKelamin) {
            $builder->where('jenis_kelamin_pasien', $jenisKelamin);
        }

        if ($statusPasien) {
            $builder->where('status_pasien', $statusPasien);
        }

        return $builder->get()->getResultArray();
    }

    public function apiPemeriksaanPasien($norm)
    {
        $client = \Config\Services::curlrequest();
        $apiURL = "http://10.250.10.107/apibdrs/apibdrs/getPemeriksaanPasien/" . $norm;

        try {
            $response = $client->get($apiURL);
            $result = json_decode($response->getBody(), true);

            if (isset($result['code']) && $result['code'] == 200 && isset($result['data']) && is_array($result['data'])) {
                // Filter hanya data yang hasilnya: null, "", atau "<br>"
                $filteredData = array_filter($result['data'], function ($item) {
                    $hasil = trim($item['hasil'] ?? ''); // Trim untuk hilangkan spasi dan newline
                    return $hasil === '' || $hasil === '<br>';
                });

                return [
                    'code' => 200,
                    'data' => array_values($filteredData)
                ];
            }

            return [
                'code' => $result['code'] ?? 500,
                'data' => []
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException("Gagal mengambil data API: " . $e->getMessage());
        }
    }

    public function riwayatPemeriksaanPasien($norm)
    {
        $client = \Config\Services::curlrequest();
        $apiURL = "http://10.250.10.107/apibdrs/apibdrs/getPemeriksaanPasien/" . $norm;

        try {
            $response = $client->get($apiURL);
            $result = json_decode($response->getBody(), true);

            if (isset($result['code']) && $result['code'] == 200 && isset($result['data']) && is_array($result['data'])) {
                return [
                    'code' => 200,
                    'data' => array_values($result['data']) // Tanpa filter, langsung semua data
                ];
            }

            return [
                'code' => $result['code'] ?? 500,
                'data' => []
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException("Gagal mengambil data API: " . $e->getMessage());
        }
    }
}
