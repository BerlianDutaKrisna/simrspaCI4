<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemprosesanModel extends Model // Update nama model
{
    protected $table      = 'pemprosesan'; // Nama tabel
    protected $primaryKey = 'id_pemprosesan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemprosesan', // Update nama kolom
        'status_pemprosesan', // Update nama kolom
        'mulai_pemprosesan', // Update nama kolom
        'selesai_pemprosesan', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pemprosesan
    public function insertPemprosesan(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Pemprosesan dengan relasi
    public function getPemprosesanWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pemprosesan.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemprosesan,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pemprosesan.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pemprosesan.id_user_pemprosesan = users.id_user', 'left') // Relasi dengan tabel users untuk pemprosesan
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Pemprosesan') // Filter berdasarkan status_hpa 'Pemprosesan'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pemprosesan
    public function updatePemprosesan($id_pemprosesan, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil table pemprosesan
        $builder->where('id_pemprosesan', $id_pemprosesan);  // Menentukan baris yang akan diupdate berdasarkan id_pemprosesan
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePemprosesan($id_pemprosesan)
    {
        return $this->delete($id_pemprosesan);
    }

    public function countPemprosesan()
    {
        return $this->where('status_pemprosesan !=', 'Selesai Pemprosesan')->countAllResults();
    }
}
