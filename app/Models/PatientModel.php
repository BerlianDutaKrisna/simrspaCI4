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
        return $this->where('norm_pasien', $norm_pasien)->first();  // Mengambil satu data pasien yang cocok
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
    WHERE hpa.tanggal_permintaan BETWEEN CURDATE() - INTERVAL 14 DAY AND CURDATE()

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
    WHERE frs.tanggal_permintaan BETWEEN CURDATE() - INTERVAL 14 DAY AND CURDATE()

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
    WHERE srs.tanggal_permintaan BETWEEN CURDATE() - INTERVAL 14 DAY AND CURDATE()

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
    WHERE ihc.tanggal_permintaan BETWEEN CURDATE() - INTERVAL 14 DAY AND CURDATE()

    ORDER BY tanggal_permintaan DESC, kode_pemeriksaan ASC;
        ";
        $builder = $this->db->query($query);
        return $builder->getResultArray();
    }

    // Mencari pasien berdasarkan kriteria tertentu
    public function searchPatientsWithRelations($searchField, $searchValue, $startDate, $endDate)
    {
        $searchValue = $this->db->escape($searchValue);
        $startDate = $this->db->escape($startDate);
        $endDate = $this->db->escape($endDate);
        $query = "
        SELECT patient.*, 
            hpa.kode_hpa AS kode_pemeriksaan, hpa.tanggal_permintaan, 'HPA' AS jenis_pemeriksaan,
            hpa.hasil_hpa AS hasil, hpa.status_hpa AS status, hpa.penerima_hpa AS penerima, hpa.tanggal_penerima
        FROM patient
        LEFT JOIN hpa ON hpa.id_pasien = patient.id_pasien
        WHERE 1=1 ";
        // Jika ada kriteria pencarian, gunakan "=" (tanpa LIKE)
        if (!empty($searchField) && !empty($searchValue)) {
            $query .= " AND $searchField = $searchValue";
        }
        // Filter berdasarkan rentang tanggal (tetap diterapkan meskipun pencarian field kosong)
        if (!empty($startDate) && !empty($endDate)) {
            $query .= " AND hpa.tanggal_permintaan BETWEEN $startDate AND $endDate";
        }
        $query .= " 
        UNION ALL
        SELECT patient.*, 
            frs.kode_frs AS kode_pemeriksaan, frs.tanggal_permintaan, 'FRS' AS jenis_pemeriksaan,
            frs.hasil_frs AS hasil, frs.status_frs AS status, frs.penerima_frs AS penerima, frs.tanggal_penerima
        FROM patient
        LEFT JOIN frs ON frs.id_pasien = patient.id_pasien
        WHERE 1=1 ";
        if (!empty($searchField) && !empty($searchValue)) {
            $query .= " AND $searchField = $searchValue";
        }
        if (!empty($startDate) && !empty($endDate)) {
            $query .= " AND frs.tanggal_permintaan BETWEEN $startDate AND $endDate";
        }
        $query .= " ORDER BY tanggal_permintaan DESC, kode_pemeriksaan ASC";
        return $this->db->query($query)->getResultArray();
    }
}
