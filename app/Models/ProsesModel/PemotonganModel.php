<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemotonganModel extends Model
{
    protected $table      = 'pemotongan'; // Nama tabel
    protected $primaryKey = 'id_pemotongan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user',
        'id_user_dokter',
        'status_pemotongan',
        'mulai_pemotongan',
        'selesai_pemotongan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pemotongan
    public function insertPemotongan(array $data): bool
    {
        $this->insertPemotongan($data);
        return $this->db->affectedRows() > 0;
    }
}
