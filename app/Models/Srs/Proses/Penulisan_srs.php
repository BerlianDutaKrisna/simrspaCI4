<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_srs extends Model // Update nama model
{
    protected $table      = 'penulisa_srs'; // Nama tabel
    protected $primaryKey = 'id_penulisa_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_penulisa_srs', // Update nama kolom
        'status_penulisa_srs', // Update nama kolom
        'mulai_penulisa_srs', // Update nama kolom
        'selesai_penulisa_srs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data penulisa_srs
    public function insertpenulisa_srs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data penulisa_srs dengan relasi
    public function getpenulisa_srsWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        penulisa_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_penulisa_srs,
        mutu.total_nilai_mutu'
        )
            ->join('srs', 'penulisa_srs.id_srs = srs.id_srs', 'left') // Relasi dengan tabel srs
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penulisa_srs.id_user_penulisa_srs = users.id_user', 'left') // Relasi dengan tabel users untuk penulisa_srs
            ->join('mutu', 'srs.id_srs = mutu.id_srs', 'left') // Relasi dengan tabel mutu berdasarkan id_srs
            ->where('srs.status_srs', 'penulisa_srs') // Filter berdasarkan status_srs 'penulisa_srs'
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penulisa_srs
    public function updatepenulisa_srs($id_penulisa_srs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penulisa_srs
        $builder->where('id_penulisa_srs', $id_penulisa_srs);  // Menentukan baris yang akan diupdate berdasarkan id_penulisa_srs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenulisa_srs($id_penulisa_srs)
    {
        return $this->delete($id_penulisa_srs);
    }

}
