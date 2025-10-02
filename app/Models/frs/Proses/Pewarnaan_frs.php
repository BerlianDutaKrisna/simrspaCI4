<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pewarnaan_frs extends Model // Update nama model
{
    protected $table      = 'pewarnaan_frs'; // Nama tabel
    protected $primaryKey = 'id_pewarnaan_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_pewarnaan_frs', // Update nama kolom
        'status_pewarnaan_frs', // Update nama kolom
        'mulai_pewarnaan_frs', // Update nama kolom
        'selesai_pewarnaan_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pewarnaan_frs dengan relasi
    public function getpewarnaan_frs()
    {
        return $this->select(
            '
        pewarnaan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pewarnaan_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs'
        )
            ->join('frs', 'pewarnaan_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pewarnaan_frs.id_user_pewarnaan_frs = users.id_user', 'left') 
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->whereIn('frs.status_frs', ['Pewarnaan']) 
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    public function detailspewarnaan_frs($id_pewarnaan_frs)
    {
        $data = $this->select(
            'pewarnaan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pewarnaan_frs'
        )
            ->join('frs', 'pewarnaan_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pewarnaan_frs.id_user_pewarnaan_frs = users.id_user', 'left')
            ->where('pewarnaan_frs.id_pewarnaan_frs', $id_pewarnaan_frs)
            ->first();

        return $data;
    }
}
