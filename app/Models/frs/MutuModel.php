<?php

namespace App\Models\Fnab; // Sesuaikan dengan folder 'Model'

use CodeIgniter\Model;

class MutuModel extends Model
{
    protected $table      = 'mutu_fnab'; // Nama tabel
    protected $primaryKey = 'id_mutu'; // Nama primary key
    protected $returnType = 'array';
    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_fnab',
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
        'total_nilai_mutu',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Mutu
    public function insertMutu(array $data): bool
    {
        $this->insertMutu($data);
        return $this->db->affectedRows() > 0;
    }

    public function getmutuWithRelations()
    {
        return $this->select(
            'mutu.*, 
            fnab.*, 
            patient.*'
        )
            ->join('fnab', 'mutu.id_fnab = fnab.id_fnab', 'left') // Relasi dengan tabel fnab
            ->join('patient', 'fnab.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->findAll();
    }

    // Fungsi untuk mengupdate data mutu
    public function updateMutu($id_mutu, $data)
    {
        // Mengambil table mutu
        $builder = $this->db->table($this->table);
        $builder->where('id_mutu', $id_mutu);
        $builder->update($data);
        return $this->db->affectedRows();
    }
}
