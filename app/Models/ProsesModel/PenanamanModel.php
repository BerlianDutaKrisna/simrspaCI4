<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PenanamanModel extends Model // Update nama model
{
    protected $table      = 'penanaman'; // Nama tabel
    protected $primaryKey = 'id_penanaman'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_penanaman', // Update nama kolom
        'status_penanaman', // Update nama kolom
        'mulai_penanaman', // Update nama kolom
        'selesai_penanaman', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Penanaman
    public function insertPenanaman(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Penanaman dengan relasi
    public function getPenanamanWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        penanaman.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_penanaman,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'penanaman.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penanaman.id_user_penanaman = users.id_user', 'left') // Relasi dengan tabel users untuk penanaman
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Penanaman') // Filter berdasarkan status_hpa 'Penanaman'
            ->findAll();
    }

    // Fungsi untuk mengupdate data penanaman
    public function updatePenanaman($id_penanaman, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penanaman
        $builder->where('id_penanaman', $id_penanaman);  // Menentukan baris yang akan diupdate berdasarkan id_penanaman
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePenanaman($id_penanaman)
    {
        return $this->delete($id_penanaman);
    }
}
