<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pembacaan_frs extends Model // Update nama model
{
    protected $table      = 'pembacaan_frs'; // Nama tabel
    protected $primaryKey = 'id_pembacaan_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_pembacaan_frs', // Update nama kolom
        'status_pembacaan_frs', // Update nama kolom
        'mulai_pembacaan_frs', // Update nama kolom
        'selesai_pembacaan_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pembacaan_frs dengan relasi
    public function getpembacaan_frs()
    {
        return $this->select(
            '
        pembacaan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pembacaan_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs,
        pembacaan_frs.id_pembacaan_frs, 
        pembacaan_frs.id_user_dokter_pembacaan_frs,
        dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan_frs'
        )
            ->join('frs', 'pembacaan_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pembacaan_frs.id_user_pembacaan_frs = users.id_user', 'left')
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->join('users AS dokter_pembacaan', 'pembacaan_frs.id_user_dokter_pembacaan_frs = dokter_pembacaan.id_user', 'left')
            ->whereIn('frs.status_frs', ['Pembacaan'])
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pembacaan_frs
    public function updatepembacaan_frs($id_pembacaan_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pembacaan_frs
        $builder->where('id_pembacaan_frs', $id_pembacaan_frs);  // Menentukan baris yang akan diupdate berdasarkan id_pembacaan_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepembacaan_frs($id_pembacaan_frs)
    {
        return $this->delete($id_pembacaan_frs);
    }

}
