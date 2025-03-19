<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_ihc extends Model // Update nama model
{
    protected $table      = 'penulisan_ihc'; // Nama tabel
    protected $primaryKey = 'id_penulisan_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_penulisan_ihc', // Update nama kolom
        'status_penulisan_ihc', // Update nama kolom
        'mulai_penulisan_ihc', // Update nama kolom
        'selesai_penulisan_ihc', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pembacaan_ihc dengan relasi
    public function getpenulisan_ihc()
    {
        return $this->select(
            '
            penulisan_ihc.*, 
            ihc.*, 
            patient.*, 
            users.nama_user AS nama_user_penulisan_ihc,
            mutu_ihc.id_mutu_ihc,
            mutu_ihc.total_nilai_mutu_ihc,
            pembacaan_ihc.id_pembacaan_ihc, 
            pembacaan_ihc.id_user_dokter_pembacaan_ihc,
            dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan_ihc'
        )
            ->join('ihc', 'penulisan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penulisan_ihc.id_user_penulisan_ihc = users.id_user', 'left')
            ->join('mutu_ihc', 'ihc.id_ihc = mutu_ihc.id_ihc', 'left')
            ->join('pembacaan_ihc', 'ihc.id_ihc = pembacaan_ihc.id_ihc', 'left')
            ->join('users AS dokter_pembacaan', 'pembacaan_ihc.id_user_dokter_pembacaan_ihc = dokter_pembacaan.id_user', 'left')
            ->whereIn('ihc.status_ihc', ['Penulisan'])
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penulisan_ihc
    public function updatepenulisan_ihc($id_penulisan_ihc, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penulisan_ihc
        $builder->where('id_penulisan_ihc', $id_penulisan_ihc);  // Menentukan baris yang akan diupdate berdasarkan id_penulisan_ihc
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenulisan_ihc($id_penulisan_ihc)
    {
        return $this->delete($id_penulisan_ihc);
    }
}
