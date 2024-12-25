<?php
namespace App\Models;

use CodeIgniter\Model;

class HpaModel extends Model
{
protected $table = 'hpa';
protected $primaryKey = 'id_hpa';
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
];
protected $useTimestamps = true;

public function insertHpa(array $data): bool
{
    // Insert data into the table
    $this->insert($data);

    // Check if the row was affected
    return $this->db->affectedRows() > 0;
}
}