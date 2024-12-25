<?php

namespace App\Models; // Sesuaikan dengan folder 'Model'

use CodeIgniter\Model;

class MutuModel extends Model
{
    protected $table      = 'mutu'; // Nama tabel
    protected $primaryKey = 'id_mutu'; // Nama primary key
    protected $returnType = 'array';
    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'indikator_1',
        'indikator_2',
        'indikator_3',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
