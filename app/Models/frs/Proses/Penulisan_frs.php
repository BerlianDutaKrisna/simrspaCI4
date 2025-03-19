<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_frs extends Model // Update nama model
{
    protected $table      = 'penulisan_frs'; // Nama tabel
    protected $primaryKey = 'id_penulisan_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_penulisan_frs', // Update nama kolom
        'status_penulisan_frs', // Update nama kolom
        'mulai_penulisan_frs', // Update nama kolom
        'selesai_penulisan_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pembacaan_frs dengan relasi
    public function getpenulisan_frs()
    {
        return $this->select(
            '
            penulisan_frs.*, 
            frs.*, 
            patient.*, 
            users.nama_user AS nama_user_penulisan_frs,
            mutu_frs.id_mutu_frs,
            mutu_frs.total_nilai_mutu_frs,
            pembacaan_frs.id_pembacaan_frs, 
            pembacaan_frs.id_user_dokter_pembacaan_frs,
            dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan_frs'
        )
            ->join('frs', 'penulisan_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penulisan_frs.id_user_penulisan_frs = users.id_user', 'left')
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->join('pembacaan_frs', 'frs.id_frs = pembacaan_frs.id_frs', 'left')
            ->join('users AS dokter_pembacaan', 'pembacaan_frs.id_user_dokter_pembacaan_frs = dokter_pembacaan.id_user', 'left')
            ->whereIn('frs.status_frs', ['Penulisan'])
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penulisan_frs
    public function updatepenulisan_frs($id_penulisan_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penulisan_frs
        $builder->where('id_penulisan_frs', $id_penulisan_frs);  // Menentukan baris yang akan diupdate berdasarkan id_penulisan_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenulisan_frs($id_penulisan_frs)
    {
        return $this->delete($id_penulisan_frs);
    }

}
