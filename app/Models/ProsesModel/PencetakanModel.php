<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PencetakanModel extends Model
{
    protected $table      = 'pencetakan'; // Nama tabel
    protected $primaryKey = 'id_pencetakan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pencetakan',
        'status_pencetakan',
        'mulai_pencetakan',
        'selesai_pencetakan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pencetakan
    public function insertPencetakan(array $data): bool
    {
        $this->insertPencetakan($data);
        return $this->db->affectedRows() > 0;
    }
}
