<?php

namespace App\Models\Srs;

use CodeIgniter\Model;

class Mutu_srs extends Model
{
    protected $table      = 'mutu_srs'; // Nama tabel
    protected $primaryKey = 'id_mutu_srs'; // Nama primary key
    protected $returnType = 'array';
    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'indikator_1',
        'indikator_2',
        'indikator_3',
        'indikator_4',
        'indikator_5',
        'indikator_6',
        'indikator_7',
        'indikator_8',
        'indikator_9',
        'indikator_10',
        'total_nilai_mutu_srs',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data mutu_srs
    public function insertmutu_srs(array $data): bool
    {
        $this->insertmutu_srs($data);
        return $this->db->affectedRows() > 0;
    }

    public function getmutu_srsWithRelations()
{
    return $this->select(
            'mutu_srs.*, 
            srs.*, 
            patient.*'
        )
        ->join('srs', 'mutu_srs.id_srs = srs.id_srs', 'left') // Relasi dengan tabel srs
        ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
        ->findAll();
}

    // Fungsi untuk mengupdate data mutu_srs
public function updatemutu_srs($id_mutu_srs, $data)
{
    // Mengambil table mutu_srs
    $builder = $this->db->table($this->table);  
    $builder->where('id_mutu_srs', $id_mutu_srs);  
    $builder->update($data);  
    return $this->db->affectedRows();  
}
}
