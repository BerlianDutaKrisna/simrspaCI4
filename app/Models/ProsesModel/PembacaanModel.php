<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PembacaanModel extends Model // Update nama model
{
    protected $table      = 'pembacaan'; // Nama tabel
    protected $primaryKey = 'id_pembacaan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pembacaan', // Update nama kolom
        'status_pembacaan', // Update nama kolom
        'mulai_pembacaan', // Update nama kolom
        'selesai_pembacaan', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pembacaan
    public function insertPembacaan(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Pembacaan dengan relasi
    public function getPembacaanWithRelations()
    {
        return $this->select(
            'pembacaan.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_dokter_pemotongan,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pembacaan.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('pemotongan', 'hpa.id_pemotongan = pemotongan.id_pemotongan', 'left') // Relasi dengan tabel pemotongan
            ->join('users', 'pemotongan.id_user_dokter_pemotongan = users.id_user', 'left') // Relasi dengan tabel users untuk dokter pemotongan
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Pembacaan') // Filter berdasarkan status_hpa 'Pembacaan'
            ->findAll();
    }


    // Fungsi untuk mengupdate data pembacaan
    public function updatePembacaan($id_pembacaan, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pembacaan
        $builder->where('id_pembacaan', $id_pembacaan);  // Menentukan baris yang akan diupdate berdasarkan id_pembacaan
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePembacaan($id_pembacaan)
    {
        return $this->delete($id_pembacaan);
    }
}
