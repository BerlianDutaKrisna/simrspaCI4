<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_hpa extends Model // Update nama model
{
    protected $table      = 'penulisa_hpa'; // Nama tabel
    protected $primaryKey = 'id_penulisa_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_penulisa_hpa', // Update nama kolom
        'status_penulisa_hpa', // Update nama kolom
        'mulai_penulisa_hpa', // Update nama kolom
        'selesai_penulisa_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data penulisa_hpa
    public function insertpenulisa_hpa(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data penulisa_hpa dengan relasi
    public function getpenulisa_hpaWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        penulisa_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_penulisa_hpa,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'penulisa_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penulisa_hpa.id_user_penulisa_hpa = users.id_user', 'left') // Relasi dengan tabel users untuk penulisa_hpa
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'penulisa_hpa') // Filter berdasarkan status_hpa 'penulisa_hpa'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penulisa_hpa
    public function updatepenulisa_hpa($id_penulisa_hpa, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penulisa_hpa
        $builder->where('id_penulisa_hpa', $id_penulisa_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_penulisa_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenulisa_hpa($id_penulisa_hpa)
    {
        return $this->delete($id_penulisa_hpa);
    }

}
