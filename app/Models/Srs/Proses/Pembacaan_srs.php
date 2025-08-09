<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pembacaan_srs extends Model // Update nama model
{
    protected $table      = 'pembacaan_srs'; // Nama tabel
    protected $primaryKey = 'id_pembacaan_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_pembacaan_srs',
        'status_pembacaan_srs',
        'mulai_pembacaan_srs',
        'selesai_pembacaan_srs',
        'id_user_dokter_pembacaan_srs',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pembacaan_srs dengan relasi
    public function getpembacaan_srs()
    {
        return $this->select(
            '
        pembacaan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_pembacaan_srs,
        mutu_srs.id_mutu_srs,
        mutu_srs.total_nilai_mutu_srs,
        pembacaan_srs.id_pembacaan_srs, 
        pembacaan_srs.id_user_dokter_pembacaan_srs,
        dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan_srs'
        )
            ->join('srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pembacaan_srs.id_user_pembacaan_srs = users.id_user', 'left')
            ->join('mutu_srs', 'srs.id_srs = mutu_srs.id_srs', 'left')
            ->join('users AS dokter_pembacaan', 'pembacaan_srs.id_user_dokter_pembacaan_srs = dokter_pembacaan.id_user', 'left')
            ->whereIn('srs.status_srs', ['Pembacaan'])
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    public function detailspembacaan_srs($id_pembacaan_srs)
    {
        $data = $this->select(
            'pembacaan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_pembacaan_srs'
        )
            ->join('srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pembacaan_srs.id_user_dokter_pembacaan_srs = users.id_user', 'left')
            ->where('pembacaan_srs.id_pembacaan_srs', $id_pembacaan_srs)
            ->first();

        return $data;
    }
}
