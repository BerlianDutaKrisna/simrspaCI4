<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemotonganTipisModel extends Model
{
    protected $table      = 'pemotongan_tipis'; // Nama tabel
    protected $primaryKey = 'id_pemotongan_tipis'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_userPemotongan_tipis',
        'status_pemotongan_tipis',
        'mulai_pemotongan_tipis',
        'selesai_pemotongan_tipis',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data PemotonganTipis
    public function insertPemotonganTipis(array $data): bool
    {
        $this->insertPemotonganTipis($data);
        return $this->db->affectedRows() > 0;
    }
}
