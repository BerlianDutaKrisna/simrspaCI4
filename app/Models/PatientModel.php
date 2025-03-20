<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'patient'; // Nama tabel di database
    protected $primaryKey = 'id_pasien';
    protected $returnType = 'array';
    protected $allowedFields = [
        'norm_pasien',
        'nama_pasien',
        'alamat_pasien',
        'tanggal_lahir_pasien',
        'jenis_kelamin_pasien',
        'status_pasien'
    ];
    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
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

    public function getPatientWithRelations()
    {
        $query = "
        SELECT patient.*, 
                hpa.kode_hpa AS kode_pemeriksaan, hpa.tanggal_permintaan, 'HPA' AS jenis_pemeriksaan,
                hpa.hasil_hpa AS hasil, hpa.status_hpa AS status, hpa.penerima_hpa AS penerima, hpa.tanggal_penerima
        FROM patient
        JOIN hpa ON hpa.id_pasien = patient.id_pasien
        UNION ALL
        SELECT patient.*, 
                frs.kode_frs AS kode_pemeriksaan, frs.tanggal_permintaan, 'FRS' AS jenis_pemeriksaan,
                NULL AS hasil, NULL AS status, frs.penerima_frs AS penerima, NULL AS tanggal_penerima
        FROM patient
        JOIN frs ON frs.id_pasien = patient.id_pasien
        UNION ALL
        SELECT patient.*, 
                srs.kode_srs AS kode_pemeriksaan, srs.tanggal_permintaan, 'SRS' AS jenis_pemeriksaan,
                NULL AS hasil, NULL AS status, srs.penerima_srs AS penerima, NULL AS tanggal_penerima
        FROM patient
        JOIN srs ON srs.id_pasien = patient.id_pasien
        UNION ALL
        SELECT patient.*, 
                ihc.kode_ihc AS kode_pemeriksaan, ihc.tanggal_permintaan, 'IHC' AS jenis_pemeriksaan,
                NULL AS hasil, NULL AS status, ihc.penerima_ihc AS penerima, NULL AS tanggal_penerima
        FROM patient
        JOIN ihc ON ihc.id_pasien = patient.id_pasien
        ORDER BY tanggal_permintaan ASC, kode_pemeriksaan ASC
    ";

        $builder = $this->db->query($query);
        return $builder->getResultArray();
    }
}
