<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pemverifikasi_frs extends Model // Update nama model
{
    protected $table      = 'pemverifikasi_frs'; // Nama tabel
    protected $primaryKey = 'id_pemverifikasi_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_pemverifikasi_frs', // Update nama kolom
        'status_pemverifikasi_frs', // Update nama kolom
        'mulai_pemverifikasi_frs', // Update nama kolom
        'selesai_pemverifikasi_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pemverifikasi_frs
    public function insertpemverifikasi_frs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pemverifikasi_frs dengan relasi
    public function getpemverifikasi_frsWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pemverifikasi_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi_frs,
        mutu.total_nilai_mutu'
        )
            ->join('frs', 'pemverifikasi_frs.id_frs = frs.id_frs', 'left') // Relasi dengan tabel frs
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pemverifikasi_frs.id_user_pemverifikasi_frs = users.id_user', 'left') // Relasi dengan tabel users untuk pemverifikasi_frs
            ->join('mutu', 'frs.id_frs = mutu.id_frs', 'left') // Relasi dengan tabel mutu berdasarkan id_frs
            ->where('frs.status_frs', 'pemverifikasi_frs') // Filter berdasarkan status_frs 'pemverifikasi_frs'
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pemverifikasi_frs
    public function updatepemverifikasi_frs($id_pemverifikasi_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pemverifikasi_frs
        $builder->where('id_pemverifikasi_frs', $id_pemverifikasi_frs);  // Menentukan baris yang akan diupdate berdasarkan id_pemverifikasi_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepemverifikasi_frs($id_pemverifikasi_frs)
    {
        return $this->delete($id_pemverifikasi_frs);
    }

}
