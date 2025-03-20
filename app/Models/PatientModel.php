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
        $builder = $this->db->table('patient')
            ->select('
            patient.*, 
            hpa.*, 
            frs.*, 
            srs.*, 
            ihc.*
        ')
            ->join('hpa', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('frs', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('srs', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('ihc', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->where('(hpa.id_hpa IS NOT NULL OR frs.id_frs IS NOT NULL OR srs.id_srs IS NOT NULL OR ihc.id_ihc IS NOT NULL)')
            ->orderBy('hpa.tanggal_permintaan', 'ASC')
            ->orderBy('frs.tanggal_permintaan', 'ASC')
            ->orderBy('srs.tanggal_permintaan', 'ASC')
            ->orderBy('ihc.tanggal_permintaan', 'ASC')
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->orderBy('frs.kode_frs', 'ASC')
            ->orderBy('srs.kode_srs', 'ASC')
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->get();

        return $builder->getResultArray();
    }
}
