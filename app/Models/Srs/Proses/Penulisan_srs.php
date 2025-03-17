<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_srs extends Model // Update nama model
{
    protected $table      = 'penulisan_srs'; // Nama tabel
    protected $primaryKey = 'id_penulisan_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_penulisan_srs', // Update nama kolom
        'status_penulisan_srs', // Update nama kolom
        'mulai_penulisan_srs', // Update nama kolom
        'selesai_penulisan_srs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data penulisan_srs
    public function insertpenulisan_srs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data penulisan_srs dengan relasi
    public function getpenulisan_srsWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        penulisan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_penulisan_srs,
        mutu.total_nilai_mutu'
        )
            ->join('srs', 'penulisan_srs.id_srs = srs.id_srs', 'left') // Relasi dengan tabel srs
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penulisan_srs.id_user_penulisan_srs = users.id_user', 'left') // Relasi dengan tabel users untuk penulisan_srs
            ->join('mutu', 'srs.id_srs = mutu.id_srs', 'left') // Relasi dengan tabel mutu berdasarkan id_srs
            ->where('srs.status_srs', 'penulisan_srs') // Filter berdasarkan status_srs 'penulisan_srs'
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penulisan_srs
    public function updatepenulisan_srs($id_penulisan_srs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penulisan_srs
        $builder->where('id_penulisan_srs', $id_penulisan_srs);  // Menentukan baris yang akan diupdate berdasarkan id_penulisan_srs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenulisan_srs($id_penulisan_srs)
    {
        return $this->delete($id_penulisan_srs);
    }

}
