<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PenanamanModel extends Model
{
    protected $table      = 'penanaman'; // Nama tabel
    protected $primaryKey = 'id_penanaman'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_penanaman',
        'status_penanaman',
        'mulai_penanaman',
        'selesai_penanaman',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Penanaman
    public function insertPenanaman(array $data): bool
    {
        $this->insertPenanaman($data);
        return $this->db->affectedRows() > 0;
    }
}
