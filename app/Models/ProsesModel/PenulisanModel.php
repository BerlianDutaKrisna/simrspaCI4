<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PenulisanModel extends Model // Update nama model
{
    protected $table      = 'penulisan'; // Nama tabel
    protected $primaryKey = 'id_penulisan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_penulisan', // Update nama kolom
        'status_penulisan', // Update nama kolom
        'mulai_penulisan', // Update nama kolom
        'selesai_penulisan', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Penulisan
    public function insertPenulisan(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Penulisan dengan relasi
    public function getPenulisanWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        penulisan.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_penulisan,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'penulisan.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penulisan.id_user_penulisan = users.id_user', 'left') // Relasi dengan tabel users untuk penulisan
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Penulisan') // Filter berdasarkan status_hpa 'Penulisan'
            ->findAll();
    }

    // Fungsi untuk mengupdate data penulisan
    public function updatePenulisan($id_penulisan, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penulisan
        $builder->where('id_penulisan', $id_penulisan);  // Menentukan baris yang akan diupdate berdasarkan id_penulisan
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePenulisan($id_penulisan)
    {
        return $this->delete($id_penulisan);
    }
}
