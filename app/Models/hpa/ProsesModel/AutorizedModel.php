<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class AutorizedModel extends Model // Update nama model
{
    protected $table      = 'autorized'; // Nama tabel
    protected $primaryKey = 'id_autorized'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_autorized', // Update nama kolom
        'status_autorized', // Update nama kolom
        'mulai_autorized', // Update nama kolom
        'selesai_autorized', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data autorized
    public function insertAutorized(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data autorized dengan relasi
    public function getAutorizedWithRelations()
    {
        return $this->select(
            '
        autorized.*, 
        hpa.*, 
        patient.*, 
        dokter_pemotongan.nama_user AS nama_user_dokter_pemotongan, 
        users.nama_user AS nama_user_autorized, 
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'autorized.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('pemotongan', 'hpa.id_pemotongan = pemotongan.id_pemotongan', 'left')
            ->join('users AS dokter_pemotongan', 'pemotongan.id_user_dokter_pemotongan = dokter_pemotongan.id_user', 'left') // Benar-benar mengambil nama dokter pemotongan
            ->join('users', 'autorized.id_user_autorized = users.id_user', 'left') // Mengambil nama user untuk autorized
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left')
            ->where('hpa.status_hpa', 'Autorized')
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data autorized
    public function updateAutorized($id_autorized, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel autorized
        $builder->where('id_autorized', $id_autorized);  // Menentukan baris yang akan diupdate berdasarkan id_autorized
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deleteAutorized($id_autorized)
    {
        return $this->delete($id_autorized);
    }

    public function countAutorized()
    {
        return $this->where('status_autorized !=', 'Selesai Authorized')->countAllResults();
    }
}
