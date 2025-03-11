<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pembacaan_hpa extends Model // Update nama model
{
    protected $table      = 'pembacaan_hpa'; // Nama tabel
    protected $primaryKey = 'id_pembacaan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pembacaan_hpa', // Update nama kolom
        'status_pembacaan_hpa', // Update nama kolom
        'mulai_pembacaan_hpa', // Update nama kolom
        'selesai_pembacaan_hpa', // Update nama kolom
        'id_user_dokter_pembacaan_hpa',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pembacaan_hpa
    public function insertpembacaan_hpa(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pembacaan_hpa dengan relasi
    public function getpembacaan_hpa()
    {
        return $this->select(
            '
            pembacaan_hpa.*, 
            hpa.*, 
            patient.*, 
            users.nama_user AS nama_user_pembacaan_hpa,
            mutu_hpa.id_mutu_hpa,
            mutu_hpa.total_nilai_mutu_hpa,
            pemotongan_hpa.id_pemotongan_hpa, 
            pemotongan_hpa.id_user_dokter_pemotongan_hpa,
            dokter_pemotongan.nama_user AS nama_user_dokter_pemotongan_hpa'
        )
            ->join('hpa', 'pembacaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pembacaan_hpa.id_user_pembacaan_hpa = users.id_user', 'left')
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->join('pemotongan_hpa', 'hpa.id_hpa = pemotongan_hpa.id_hpa', 'left')
            ->join('users AS dokter_pemotongan', 'pemotongan_hpa.id_user_dokter_pemotongan_hpa = dokter_pemotongan.id_user', 'left')
            ->whereIn('hpa.status_hpa', ['Pembacaan'])
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }
    



    // Fungsi untuk mengupdate data pembacaan_hpa
    public function updatepembacaan_hpa($id_pembacaan_hpa, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pembacaan_hpa
        $builder->where('id_pembacaan_hpa', $id_pembacaan_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_pembacaan_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepembacaan_hpa($id_pembacaan_hpa)
    {
        return $this->delete($id_pembacaan_hpa);
    }
}
