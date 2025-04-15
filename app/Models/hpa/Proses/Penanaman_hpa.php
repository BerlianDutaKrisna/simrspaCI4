<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penanaman_hpa extends Model // Update nama model
{
    protected $table      = 'penanaman_hpa'; // Nama tabel
    protected $primaryKey = 'id_penanaman_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_penanaman_hpa', // Update nama kolom
        'status_penanaman_hpa', // Update nama kolom
        'mulai_penanaman_hpa', // Update nama kolom
        'selesai_penanaman_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data penanaman_hpa dengan relasi
    public function getpenanaman_hpa()
    {
        return $this->select(
            '
        penanaman_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_penanaman_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa'
        )
            ->join('hpa', 'penanaman_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penanaman_hpa.id_user_penanaman_hpa = users.id_user', 'left') 
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->whereIn('hpa.status_hpa', ['Penanaman']) 
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    public function detailspenanaman_hpa($id_penanaman_hpa)
    {
        $data = $this->select(
            'penanaman_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_penanaman_hpa'
        )
            ->join('hpa', 'penanaman_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penanaman_hpa.id_user_penanaman_hpa = users.id_user', 'left')
            ->where('penanaman_hpa.id_penanaman_hpa', $id_penanaman_hpa)
            ->first();

        return $data;
    }
}
