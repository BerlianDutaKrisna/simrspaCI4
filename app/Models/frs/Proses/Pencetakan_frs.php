<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pencetakan_frs extends Model // Update nama model
{
    protected $table      = 'pencetakan_frs'; // Nama tabel
    protected $primaryKey = 'id_pencetakan_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_pencetakan_frs', // Update nama kolom
        'status_pencetakan_frs', // Update nama kolom
        'mulai_pencetakan_frs', // Update nama kolom
        'selesai_pencetakan_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pencetakan_frs
    public function insertpencetakan_frs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pencetakan_frs dengan relasi
    public function getpencetakan_frsWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pencetakan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_frs,
        mutu.total_nilai_mutu'
        )
            ->join('frs', 'pencetakan_frs.id_frs = frs.id_frs', 'left') // Relasi dengan tabel frs
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pencetakan_frs.id_user_pencetakan_frs = users.id_user', 'left') // Relasi dengan tabel users untuk pencetakan_frs
            ->join('mutu', 'frs.id_frs = mutu.id_frs', 'left') // Relasi dengan tabel mutu berdasarkan id_frs
            ->where('frs.status_frs', 'pencetakan_frs') // Filter berdasarkan status_frs 'pencetakan_frs'
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pencetakan_frs
    public function updatepencetakan_frs($id_pencetakan_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pencetakan_frs
        $builder->where('id_pencetakan_frs', $id_pencetakan_frs);  // Menentukan baris yang akan diupdate berdasarkan id_pencetakan_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepencetakan_frs($id_pencetakan_frs)
    {
        return $this->delete($id_pencetakan_frs);
    }

}
