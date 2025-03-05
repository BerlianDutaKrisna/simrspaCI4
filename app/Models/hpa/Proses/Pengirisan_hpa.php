<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pengirisan_hpa extends Model
{
    protected $table      = 'pengirisan_hpa'; // Nama tabel
    protected $primaryKey = 'id_pengirisan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pengirisan_hpa',
        'status_pengirisan_hpa',
        'mulai_pengirisan_hpa',
        'selesai_pengirisan_hpa',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pengirisan_hpa
    public function insertpengirisan_hpa(array $data): bool
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pengirisan_hpa dengan relasi
    public function getPengirisan_hpa()
    {
        return $this->select(
            '
        pengirisan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pengirisan_hpa,
        mutu_hpa.total_nilai_mutu_hpa'
        )
            ->join('hpa', 'pengirisan_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pengirisan_hpa.id_user_pengirisan_hpa = users.id_user', 'left') // Relasi dengan tabel users untuk pengirisan_hpa
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left') // Relasi dengan tabel mutu_hpa berdasarkan id_hpa
            ->where('hpa.status_hpa', 'pengirisan_hpa') // Filter berdasarkan status_hpa 'pengirisan_hpa'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }


    // Fungsi untuk mengupdate data pengirisan_hpa
    public function updatepengirisan_hpa($id_pengirisan_hpa, $data)
    {
        $builder = $this->db->table($this->table);  // Mengambil table pengirisan_hpa
        $builder->where('id_pengirisan_hpa', $id_pengirisan_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_pengirisan_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepengirisan_hpa($id_pengirisan_hpa)
    {
        return $this->delete($id_pengirisan_hpa);
    }

}
