<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PewarnaanModel extends Model
{
    protected $table      = 'pewarnaan'; // Nama tabel
    protected $primaryKey = 'id_pewarnaan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pewarnaan',
        'status_pewarnaan',
        'mulai_pewarnaan',
        'selesai_pewarnaan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pewarnaan
    public function insertPewarnaan(array $data): bool
    {
        $this->insertPewarnaan($data);
        return $this->db->affectedRows() > 0;
    }
}
