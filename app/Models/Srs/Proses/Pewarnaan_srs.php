<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pewarnaan_srs extends Model // Update nama model
{
    protected $table      = 'pewarnaan_srs'; // Nama tabel
    protected $primaryKey = 'id_pewarnaan_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_pewarnaan_srs', // Update nama kolom
        'status_pewarnaan_srs', // Update nama kolom
        'mulai_pewarnaan_srs', // Update nama kolom
        'selesai_pewarnaan_srs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pewarnaan_srs dengan relasi
    public function getpewarnaan_srs()
    {
        return $this->select(
            '
        pewarnaan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_pewarnaan_srs,
        mutu_srs.id_mutu_srs,
        mutu_srs.total_nilai_mutu_srs'
        )
            ->join('srs', 'pewarnaan_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pewarnaan_srs.id_user_pewarnaan_srs = users.id_user', 'left') 
            ->join('mutu_srs', 'srs.id_srs = mutu_srs.id_srs', 'left')
            ->whereIn('srs.status_srs', ['Pewarnaan']) 
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    public function detailspewarnaan_srs($id_pewarnaan_srs)
    {
        $data = $this->select(
            'pewarnaan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_pewarnaan_srs'
        )
            ->join('srs', 'pewarnaan_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pewarnaan_srs.id_user_pewarnaan_srs = users.id_user', 'left')
            ->where('pewarnaan_srs.id_pewarnaan_srs', $id_pewarnaan_srs)
            ->first();

        return $data;
    }
}
