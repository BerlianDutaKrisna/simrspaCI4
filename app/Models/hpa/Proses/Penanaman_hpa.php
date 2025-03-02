<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penanaman_hpa extends Model // Update nama model
{
    protected $table      = 'penanaman_hpa'; // Nama tabel
    protected $primaryKey = 'id_penanaman_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_penanaman_hpa', // Update nama kolom
        'status_penanaman_hpa', // Update nama kolom
        'mulai_penanaman_hpa', // Update nama kolom
        'selesai_penanaman_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data penanaman_hpa
    public function insertpenanaman_hpa(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data penanaman_hpa dengan relasi
    public function getpenanaman_hpaWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        penanaman_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_penanaman_hpa,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'penanaman_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penanaman_hpa.id_user_penanaman_hpa = users.id_user', 'left') // Relasi dengan tabel users untuk penanaman_hpa
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'penanaman_hpa') // Filter berdasarkan status_hpa 'penanaman_hpa'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penanaman_hpa
    public function updatepenanaman_hpa($id_penanaman_hpa, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penanaman_hpa
        $builder->where('id_penanaman_hpa', $id_penanaman_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_penanaman_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenanaman_hpa($id_penanaman_hpa)
    {
        return $this->delete($id_penanaman_hpa);
    }

}
