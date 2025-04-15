<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pewarnaan_hpa extends Model // Update nama model
{
    protected $table      = 'pewarnaan_hpa'; // Nama tabel
    protected $primaryKey = 'id_pewarnaan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pewarnaan_hpa', // Update nama kolom
        'status_pewarnaan_hpa', // Update nama kolom
        'mulai_pewarnaan_hpa', // Update nama kolom
        'selesai_pewarnaan_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pewarnaan_hpa dengan relasi
    public function getpewarnaan_hpa()
    {
        return $this->select(
            '
        pewarnaan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pewarnaan_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa'
        )
            ->join('hpa', 'pewarnaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pewarnaan_hpa.id_user_pewarnaan_hpa = users.id_user', 'left') 
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->whereIn('hpa.status_hpa', ['Pewarnaan']) 
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    public function detailspewarnaan_hpa($id_pewarnaan_hpa)
    {
        $data = $this->select(
            'pewarnaan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pewarnaan_hpa'
        )
            ->join('hpa', 'pewarnaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pewarnaan_hpa.id_user_pewarnaan_hpa = users.id_user', 'left')
            ->where('pewarnaan_hpa.id_pewarnaan_hpa', $id_pewarnaan_hpa)
            ->first();

        return $data;
    }
}
