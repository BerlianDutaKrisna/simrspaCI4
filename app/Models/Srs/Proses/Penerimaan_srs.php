<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penerimaan_srs extends Model
{
    protected $table      = 'penerimaan_srs'; // Nama tabel
    protected $primaryKey = 'id_penerimaan_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_penerimaan_srs',
        'status_penerimaan_srs',
        'mulai_penerimaan_srs',
        'selesai_penerimaan_srs',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPenerimaan_srs()
    {
        return $this->select(
            '
        penerimaan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_penerimaan_srs,
        mutu_srs.id_mutu_srs,
        mutu_srs.total_nilai_mutu_srs'
        )
            ->join('srs', 'penerimaan_srs.id_srs = srs.id_srs', 'left') // Relasi dengan tabel srs
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penerimaan_srs.id_user_penerimaan_srs = users.id_user', 'left') // Relasi dengan tabel users untuk penerimaan_srs
            ->join('mutu_srs', 'srs.id_srs = mutu_srs.id_srs', 'left') // Relasi dengan tabel mutu_srs berdasarkan id_srs
            ->whereIn('srs.status_srs', ['penerimaan_srs', 'Penerimaan']) // Menambahkan filter whereIn untuk status_srs
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    public function detailspenerimaan_srs($id_penerimaan_srs)
    {
        $data = $this->select(
            'penerimaan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_penerimaan_srs'
        )
            ->join('srs', 'penerimaan_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penerimaan_srs.id_user_penerimaan_srs = users.id_user', 'left')
            ->where('penerimaan_srs.id_penerimaan_srs', $id_penerimaan_srs)
            ->first();

        return $data;
    }
}
