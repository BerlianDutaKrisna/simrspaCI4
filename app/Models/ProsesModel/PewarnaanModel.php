<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PewarnaanModel extends Model // Update nama model
{
    protected $table      = 'pewarnaan'; // Nama tabel
    protected $primaryKey = 'id_pewarnaan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pewarnaan', // Update nama kolom
        'status_pewarnaan', // Update nama kolom
        'mulai_pewarnaan', // Update nama kolom
        'selesai_pewarnaan', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pewarnaan
    public function insertPewarnaan(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Pewarnaan dengan relasi
    public function getPewarnaanWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pewarnaan.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pewarnaan,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pewarnaan.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pewarnaan.id_user_pewarnaan = users.id_user', 'left') // Relasi dengan tabel users untuk pewarnaan
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Pewarnaan') // Filter berdasarkan status_hpa 'Pewarnaan'
            ->findAll();
    }

    // Fungsi untuk mengupdate data pewarnaan
    public function updatePewarnaan($id_pewarnaan, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pewarnaan
        $builder->where('id_pewarnaan', $id_pewarnaan);  // Menentukan baris yang akan diupdate berdasarkan id_pewarnaan
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePewarnaan($id_pewarnaan)
    {
        return $this->delete($id_pewarnaan);
    }
}
