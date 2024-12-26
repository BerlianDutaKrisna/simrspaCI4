<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemverifikasiModel extends Model
{
    protected $table      = 'pemverifikasi'; // Nama tabel
    protected $primaryKey = 'id_pemverifikasian'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user',
        'id_user_dokter',
        'status_pemverifikasian',
        'mulai_pemverifikasian',
        'selesai_pemverifikasian',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
