<?php

namespace App\Models\Frs;

use CodeIgniter\Model;

class Mutu_frs extends Model
{
    protected $table      = 'mutu_frs'; // Nama tabel
    protected $primaryKey = 'id_mutu_frs'; // Nama primary key
    protected $returnType = 'array';
    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
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
        'total_nilai_mutu_frs',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data mutu_frs
    public function insertmutu_frs(array $data): bool
    {
        $this->insertmutu_frs($data);
        return $this->db->affectedRows() > 0;
    }

    public function getmutuWithRelations()
    {
        return $this->select(
            'mutu_frs.*, 
            frs.*, 
            patient.*'
        )
            ->join('frs', 'mutu_frs.id_frs = frs.id_frs', 'left') // Relasi dengan tabel frs
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->findAll();
    }

    public function detailsmutu_frs($id_mutu_frs)
    {
        $data = $this->select(
            'mutu_frs.*, 
        frs.*, 
        patient.*'
        )
            ->join('frs', 'mutu_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->where('mutu_frs.id_mutu_frs', $id_mutu_frs)
            ->first();

        return $data;
    }
}
