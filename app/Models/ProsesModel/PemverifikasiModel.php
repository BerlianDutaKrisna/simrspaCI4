<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemverifikasiModel extends Model // Update nama model
{
    protected $table      = 'pemverifikasi'; // Nama tabel
    protected $primaryKey = 'id_pemverifikasi'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemverifikasi', // Update nama kolom
        'status_pemverifikasi', // Update nama kolom
        'mulai_pemverifikasi', // Update nama kolom
        'selesai_pemverifikasi', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pemverifikasi
    public function insertPemverifikasi(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Pemverifikasi dengan relasi
    public function getPemverifikasiWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pemverifikasi.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pemverifikasi.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pemverifikasi.id_user_pemverifikasi = users.id_user', 'left') // Relasi dengan tabel users untuk pemverifikasi
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Pemverifikasi') // Filter berdasarkan status_hpa 'Pemverifikasi'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pemverifikasi
    public function updatePemverifikasi($id_pemverifikasi, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pemverifikasi
        $builder->where('id_pemverifikasi', $id_pemverifikasi);  // Menentukan baris yang akan diupdate berdasarkan id_pemverifikasi
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePemverifikasi($id_pemverifikasi)
    {
        return $this->delete($id_pemverifikasi);
    }

    public function countPemverifikasi()
    {
        return $this->where('status_pemverifikasi !=', 'Selesai Pemverifikasi')->countAllResults();
    }
}
