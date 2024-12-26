<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemprosesanModel extends Model
{
    protected $table      = 'pemprosesan'; // Nama tabel
    protected $primaryKey = 'id_pemprosesan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemprosesan',
        'status_pemprosesan',
        'mulai_pemprosesan',
        'selesai_pemprosesan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pemprosesan
    public function insertPemprosesan(array $data): bool
    {
        $this->insertPemprosesan($data);
        return $this->db->affectedRows() > 0;
    }
}
