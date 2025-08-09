<?php

namespace App\Models\Ihc;

use CodeIgniter\Model;

class Mutu_ihc extends Model
{
    protected $table      = 'mutu_ihc'; // Nama tabel
    protected $primaryKey = 'id_mutu_ihc'; // Nama primary key
    protected $returnType = 'array';
    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
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
        'total_nilai_mutu_ihc',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data mutu_ihc
    public function insertmutu_ihc(array $data): bool
    {
        $this->insertmutu_ihc($data);
        return $this->db->affectedRows() > 0;
    }

    public function getmutuWithRelations()
    {
        return $this->select(
            'mutu_ihc.*, 
            ihc.*, 
            patient.*'
        )
            ->join('ihc', 'mutu_ihc.id_ihc = ihc.id_ihc', 'left') // Relasi dengan tabel ihc
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->findAll();
    }

    public function detailsmutu_ihc($id_mutu_ihc)
    {
        $data = $this->select(
            'mutu_ihc.*, 
        ihc.*, 
        patient.*'
        )
            ->join('ihc', 'mutu_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->where('mutu_ihc.id_mutu_ihc', $id_mutu_ihc)
            ->first();

        return $data;
    }
}
