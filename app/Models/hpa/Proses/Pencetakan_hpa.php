<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pencetakan_hpa extends Model // Update nama model
{
    protected $table      = 'pencetakan_hpa'; // Nama tabel
    protected $primaryKey = 'id_pencetakan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pencetakan_hpa', // Update nama kolom
        'status_pencetakan_hpa', // Update nama kolom
        'mulai_pencetakan_hpa', // Update nama kolom
        'selesai_pencetakan_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pencetakan_hpa
    public function insertpencetakan_hpa(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pencetakan_hpa dengan relasi
    public function getpencetakan_hpaWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pencetakan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_hpa,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pencetakan_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pencetakan_hpa.id_user_pencetakan_hpa = users.id_user', 'left') // Relasi dengan tabel users untuk pencetakan_hpa
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'pencetakan_hpa') // Filter berdasarkan status_hpa 'pencetakan_hpa'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pencetakan_hpa
    public function updatepencetakan_hpa($id_pencetakan_hpa, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pencetakan_hpa
        $builder->where('id_pencetakan_hpa', $id_pencetakan_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_pencetakan_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepencetakan_hpa($id_pencetakan_hpa)
    {
        return $this->delete($id_pencetakan_hpa);
    }

}
