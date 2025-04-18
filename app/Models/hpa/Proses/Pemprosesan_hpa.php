<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pemprosesan_hpa extends Model // Update nama model
{
    protected $table      = 'pemprosesan_hpa'; // Nama tabel
    protected $primaryKey = 'id_pemprosesan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemprosesan_hpa', // Update nama kolom
        'status_pemprosesan_hpa', // Update nama kolom
        'mulai_pemprosesan_hpa', // Update nama kolom
        'selesai_pemprosesan_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pemprosesan_hpa dengan relasi
    public function getpemprosesan_hpa()
    {
        return $this->select(
            '
        pemprosesan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemprosesan_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa'
        )
            ->join('hpa', 'pemprosesan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemprosesan_hpa.id_user_pemprosesan_hpa = users.id_user', 'left') 
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->whereIn('hpa.status_hpa', ['Pemprosesan']) 
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    public function detailspemprosesan_hpa($id_pemprosesan_hpa)
    {
        $data = $this->select(
            'pemprosesan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemprosesan_hpa'
        )
            ->join('hpa', 'pemprosesan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemprosesan_hpa.id_user_pemprosesan_hpa = users.id_user', 'left')
            ->where('pemprosesan_hpa.id_pemprosesan_hpa', $id_pemprosesan_hpa)
            ->first();

        return $data;
    }

    public function countpemprosesan_hpa()
    {
        return $this->where('status_pemprosesan_hpa !=', 'Selesai pemprosesan_hpa')->countAllResults();
    }
}
