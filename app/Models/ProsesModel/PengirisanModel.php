<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PengirisanModel extends Model
{
    protected $table      = 'pengirisan'; // Nama tabel
    protected $primaryKey = 'id_pengirisan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pengirisan',
        'status_pengirisan',
        'mulai_pengirisan',
        'selesai_pengirisan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pengirisan
    public function insertPengirisan(array $data): bool
    {
        $this->insertPengirisan($data);
        return $this->db->affectedRows() > 0;
    }
}
