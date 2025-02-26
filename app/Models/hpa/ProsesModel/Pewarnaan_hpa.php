<?php

namespace App\Models\Hpa\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pewarnaan_hpa extends Model // Update nama model
{
    protected $table      = 'pewarnaan_hpa'; // Nama tabel
    protected $primaryKey = 'id_pewarnaan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pewarnaan_hpa', // Update nama kolom
        'status_pewarnaan_hpa', // Update nama kolom
        'mulai_pewarnaan_hpa', // Update nama kolom
        'selesai_pewarnaan_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pewarnaan_hpa
    public function insertpewarnaan_hpa(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pewarnaan_hpa dengan relasi
    public function getpewarnaan_hpaWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pewarnaan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pewarnaan_hpa,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pewarnaan_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pewarnaan_hpa.id_user_pewarnaan_hpa = users.id_user', 'left') // Relasi dengan tabel users untuk pewarnaan_hpa
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'pewarnaan_hpa') // Filter berdasarkan status_hpa 'pewarnaan_hpa'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pewarnaan_hpa
    public function updatepewarnaan_hpa($id_pewarnaan_hpa, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pewarnaan_hpa
        $builder->where('id_pewarnaan_hpa', $id_pewarnaan_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_pewarnaan_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepewarnaan_hpa($id_pewarnaan_hpa)
    {
        return $this->delete($id_pewarnaan_hpa);
    }

}
