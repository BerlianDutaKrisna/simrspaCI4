<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemverifikasiModel extends Model
{
    protected $table      = 'pemverifikasi'; // Nama tabel
    protected $primaryKey = 'id_pemverifikasian'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemverifikasian',
        'id_user_dokter_pemverifikasian',
        'status_pemverifikasian',
        'mulai_pemverifikasian',
        'selesai_pemverifikasian',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pemverifikasi
    public function insertPemverifikasi(array $data): bool
    {
        $this->insertPemverifikasi($data);
        return $this->db->affectedRows() > 0;
    }
}
