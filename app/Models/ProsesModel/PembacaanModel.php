<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PembacaanModel extends Model
{
    protected $table      = 'pembacaan'; // Nama tabel
    protected $primaryKey = 'id_pembacaan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user',
        'id_user_dokter', // Menambahkan field id_user_dokter
        'status_pembacaan',
        'mulai_pembacaan',
        'selesai_pembacaan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
