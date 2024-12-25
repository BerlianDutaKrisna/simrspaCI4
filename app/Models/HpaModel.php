<?php

namespace App\Models; // Sesuaikan dengan folder 'Model'

use CodeIgniter\Model;

class HpaModel extends Model
{
    protected $table      = 'hpa'; // Nama tabel
    protected $primaryKey = 'id_hpa'; // Nama primary key
    protected $returnType = 'array';
    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'kode_hpa',
        'id_pasien',
        'unit_asal',
        'dokter_pengirim',
        'tanggal_permintaan',
        'tanggal_hasil',
        'lokasi_spesimen',
        'tindakan_spesimen',
        'diagnosa_klinik',
        'status_hpa',
        'id_penerimaan',
        'id_pengirisan',
        'id_mutu',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

public function insertHpa(array $data): bool
{
    // Insert data into the table
    $this->insert($data);

    // Check if the row was affected
    return $this->db->affectedRows() > 0;
}
}