<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_frs extends Model // Update nama model
{
    protected $table      = 'penulisa_frs'; // Nama tabel
    protected $primaryKey = 'id_penulisa_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_penulisa_frs', // Update nama kolom
        'status_penulisa_frs', // Update nama kolom
        'mulai_penulisa_frs', // Update nama kolom
        'selesai_penulisa_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data penulisa_frs
    public function insertpenulisa_frs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data penulisa_frs dengan relasi
    public function getpenulisa_frsWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        penulisa_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_penulisa_frs,
        mutu.total_nilai_mutu'
        )
            ->join('frs', 'penulisa_frs.id_frs = frs.id_frs', 'left') // Relasi dengan tabel frs
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penulisa_frs.id_user_penulisa_frs = users.id_user', 'left') // Relasi dengan tabel users untuk penulisa_frs
            ->join('mutu', 'frs.id_frs = mutu.id_frs', 'left') // Relasi dengan tabel mutu berdasarkan id_frs
            ->where('frs.status_frs', 'penulisa_frs') // Filter berdasarkan status_frs 'penulisa_frs'
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penulisa_frs
    public function updatepenulisa_frs($id_penulisa_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penulisa_frs
        $builder->where('id_penulisa_frs', $id_penulisa_frs);  // Menentukan baris yang akan diupdate berdasarkan id_penulisa_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenulisa_frs($id_penulisa_frs)
    {
        return $this->delete($id_penulisa_frs);
    }

}
