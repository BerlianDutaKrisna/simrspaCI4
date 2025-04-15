<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_hpa extends Model // Update nama model
{
    protected $table      = 'penulisan_hpa'; // Nama tabel
    protected $primaryKey = 'id_penulisan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_penulisan_hpa', // Update nama kolom
        'status_penulisan_hpa', // Update nama kolom
        'mulai_penulisan_hpa', // Update nama kolom
        'selesai_penulisan_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pembacaan_hpa dengan relasi
    public function getpenulisan_hpa()
    {
        return $this->select(
            '
            penulisan_hpa.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_penulisan_hpa,
            mutu_hpa.id_mutu_hpa,
            mutu_hpa.total_nilai_mutu_hpa,
            pembacaan_hpa.id_pembacaan_hpa, 
            pembacaan_hpa.id_user_dokter_pembacaan_hpa,
            dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan_hpa'
        )
            ->join('hpa', 'penulisan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penulisan_hpa.id_user_penulisan_hpa = users.id_user', 'left')
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->join('pembacaan_hpa', 'hpa.id_hpa = pembacaan_hpa.id_hpa', 'left')
            ->join('users AS dokter_pembacaan', 'pembacaan_hpa.id_user_dokter_pembacaan_hpa = dokter_pembacaan.id_user', 'left')
            ->whereIn('hpa.status_hpa', ['Penulisan'])
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    public function detailspenulisan_hpa($id_penulisan_hpa)
    {
        $data = $this->select(
            'penulisan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_penulisan_hpa'
        )
            ->join('hpa', 'penulisan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penulisan_hpa.id_user_penulisan_hpa = users.id_user', 'left')
            ->where('penulisan_hpa.id_penulisan_hpa', $id_penulisan_hpa)
            ->first();

        return $data;
    }
}
