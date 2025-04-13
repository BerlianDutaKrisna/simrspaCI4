<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pemotongan_tipis_hpa extends Model // Update nama model
{
    protected $table      = 'pemotongan_tipis_hpa'; // Nama tabel
    protected $primaryKey = 'id_pemotongan_tipis_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemotongan_tipis_hpa', // Update nama kolom
        'status_pemotongan_tipis_hpa', // Update nama kolom
        'mulai_pemotongan_tipis_hpa', // Update nama kolom
        'selesai_pemotongan_tipis_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data PemotonganTipis
    public function insertPemotonganTipis(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data PemotonganTipis dengan relasi
    public function getpemotongan_tipis_hpa()
    {
        return $this->select(
            '
        pemotongan_tipis_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemotongan_tipis_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa'
        )
            ->join('hpa', 'pemotongan_tipis_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemotongan_tipis_hpa.id_user_pemotongan_tipis_hpa = users.id_user', 'left') 
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->whereIn('hpa.status_hpa', ['Pemotongan Tipis']) 
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    public function detailspemotongan_tipis_hpa($id_pemotongan_tipis_hpa)
    {
        $data = $this->select(
            'pemotongan_tipis_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemotongan_tipis_hpa'
        )
            ->join('hpa', 'pemotongan_tipis_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemotongan_tipis_hpa.id_user_pemotongan_tipis_hpa = users.id_user', 'left')
            ->where('pemotongan_tipis_hpa.id_pemotongan_tipis_hpa', $id_pemotongan_tipis_hpa)
            ->first();

        return $data;
    }

    public function deletePemotonganTipis($id_pemotongan_tipis_hpa)
    {
        return $this->delete($id_pemotongan_tipis_hpa);
    }

}
