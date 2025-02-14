<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemotonganTipisModel extends Model // Update nama model
{
    protected $table      = 'pemotongan_tipis'; // Nama tabel
    protected $primaryKey = 'id_pemotongan_tipis'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemotongan_tipis', // Update nama kolom
        'status_pemotongan_tipis', // Update nama kolom
        'mulai_pemotongan_tipis', // Update nama kolom
        'selesai_pemotongan_tipis', // Update nama kolom
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
    public function getPemotonganTipisWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pemotongan_tipis.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemotongan_tipis,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pemotongan_tipis.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pemotongan_tipis.id_user_pemotongan_tipis = users.id_user', 'left') // Relasi dengan tabel users untuk pemotongan tipis
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Pemotongan Tipis') // Filter berdasarkan status_hpa 'Pemotongan Tipis'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pemotongan tipis
    public function updatePemotonganTipis($id_pemotongan_tipis, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pemotongan tipis
        $builder->where('id_pemotongan_tipis', $id_pemotongan_tipis);  // Menentukan baris yang akan diupdate berdasarkan id_pemotongan_tipis
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePemotonganTipis($id_pemotongan_tipis)
    {
        return $this->delete($id_pemotongan_tipis);
    }
}
