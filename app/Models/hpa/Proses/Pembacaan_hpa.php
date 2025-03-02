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
    public function getpembacaan_hpaWithRelations()
    {
        return $this->select(
            'pembacaan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_dokter_pemotongan,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pembacaan_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('pemotongan', 'hpa.id_pemotongan = pemotongan.id_pemotongan', 'left') // Relasi dengan tabel pemotongan
            ->join('users', 'pemotongan.id_user_dokter_pemotongan = users.id_user', 'left') // Relasi dengan tabel users untuk dokter pemotongan
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'pembacaan_hpa') // Filter berdasarkan status_hpa 'pembacaan_hpa'
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
