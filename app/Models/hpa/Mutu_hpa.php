<?php

namespace App\Models\Hpa;

use CodeIgniter\Model;

class Mutu_hpa extends Model
{
    protected $table      = 'mutu_hpa'; // Nama tabel
    protected $primaryKey = 'id_mutu_hpa'; // Nama primary key
    protected $returnType = 'array';
    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
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
        'total_nilai_mutu_hpa',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data mutu_hpa
    public function insertmutu_hpa(array $data): bool
    {
        $this->insertmutu_hpa($data);
        return $this->db->affectedRows() > 0;
    }

    public function getmutuWithRelations()
    {
        return $this->select(
            'mutu_hpa.*, 
            hpa.*, 
            patient.*'
        )
            ->join('hpa', 'mutu_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->findAll();
    }

    public function detailsmutu_hpa($id_mutu_hpa)
    {
        $data = $this->select(
            'mutu_hpa.*, 
        hpa.*, 
        patient.*'
        )
            ->join('hpa', 'mutu_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->where('mutu_hpa.id_mutu_hpa', $id_mutu_hpa)
            ->first();

        return $data;
    }
}
