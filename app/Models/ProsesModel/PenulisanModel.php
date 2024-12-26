<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PenulisanModel extends Model
{
    protected $table      = 'penulisan'; // Nama tabel
    protected $primaryKey = 'id_penulisan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user',
        'status_penulisan',
        'mulai_penulisan',
        'selesai_penulisan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Penulisan
    public function insertPenulisan(array $data): bool
    {
        $this->insertPenulisan($data);
        return $this->db->affectedRows() > 0;
    }
}
