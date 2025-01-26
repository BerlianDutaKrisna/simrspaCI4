<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PencetakanModel extends Model // Update nama model
{
    protected $table      = 'pencetakan'; // Nama tabel
    protected $primaryKey = 'id_pencetakan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pencetakan', // Update nama kolom
        'status_pencetakan', // Update nama kolom
        'mulai_pencetakan', // Update nama kolom
        'selesai_pencetakan', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pencetakan
    public function insertPencetakan(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Pencetakan dengan relasi
    public function getPencetakanWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pencetakan.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pencetakan.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pencetakan.id_user_pencetakan = users.id_user', 'left') // Relasi dengan tabel users untuk pencetakan
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Pencetakan') // Filter berdasarkan status_hpa 'Pencetakan'
            ->findAll();
    }

    // Fungsi untuk mengupdate data pencetakan
    public function updatePencetakan($id_pencetakan, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pencetakan
        $builder->where('id_pencetakan', $id_pencetakan);  // Menentukan baris yang akan diupdate berdasarkan id_pencetakan
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePencetakan($id_pencetakan)
    {
        return $this->delete($id_pencetakan);
    }
}
