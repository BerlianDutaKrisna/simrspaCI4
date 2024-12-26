<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PenerimaanModel extends Model
{
    protected $table      = 'penerimaan'; // Nama tabel
    protected $primaryKey = 'id_penerimaan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user',
        'status_penerimaan',
        'mulai_penerimaan',
        'selesai_penerimaan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Penerimaan
    public function insertPenerimaan(array $data): bool
    {
        $this->insertPenerimaan($data);
        return $this->db->affectedRows() > 0;
    }
}
