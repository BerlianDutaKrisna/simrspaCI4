<?php

namespace App\Models\Hpa\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Authorized_hpa extends Model // Update nama model
{
    protected $table      = 'authorized_hpa'; // Nama tabel
    protected $primaryKey = 'id_authorized_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_authorized_hpa', // Update nama kolom
        'status_authorized_hpa', // Update nama kolom
        'mulai_authorized_hpa', // Update nama kolom
        'selesai_authorized_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data authorized_hpa
    public function insertauthorized_hpa(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data authorized_hpa dengan relasi
    public function getauthorized_hpaWithRelations()
    {
        return $this->select(
            '
        authorized_hpa.*, 
        hpa.*, 
        patient.*, 
        dokter_pemotongan.nama_user AS nama_user_dokter_pemotongan, 
        users.nama_user AS nama_user_authorized_hpa, 
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'authorized_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('pemotongan', 'hpa.id_pemotongan = pemotongan.id_pemotongan', 'left')
            ->join('users AS dokter_pemotongan', 'pemotongan.id_user_dokter_pemotongan = dokter_pemotongan.id_user', 'left') // Benar-benar mengambil nama dokter pemotongan
            ->join('users', 'authorized_hpa.id_user_authorized_hpa = users.id_user', 'left') // Mengambil nama user untuk authorized_hpa
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left')
            ->where('hpa.status_hpa', 'authorized_hpa')
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data authorized_hpa
    public function updateauthorized_hpa($id_authorized_hpa, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel authorized_hpa
        $builder->where('id_authorized_hpa', $id_authorized_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_authorized_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deleteauthorized_hpa($id_authorized_hpa)
    {
        return $this->delete($id_authorized_hpa);
    }

}
