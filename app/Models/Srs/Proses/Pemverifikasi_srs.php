<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pemverifikasi_srs extends Model // Update nama model
{
    protected $table      = 'pemverifikasi_srs'; // Nama tabel
    protected $primaryKey = 'id_pemverifikasi_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_pemverifikasi_srs', // Update nama kolom
        'status_pemverifikasi_srs', // Update nama kolom
        'mulai_pemverifikasi_srs', // Update nama kolom
        'selesai_pemverifikasi_srs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pemverifikasi_srs
    public function insertpemverifikasi_srs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pemverifikasi_srs dengan relasi
    public function getpemverifikasi_srsWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pemverifikasi_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi_srs,
        mutu.total_nilai_mutu'
        )
            ->join('srs', 'pemverifikasi_srs.id_srs = srs.id_srs', 'left') // Relasi dengan tabel srs
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pemverifikasi_srs.id_user_pemverifikasi_srs = users.id_user', 'left') // Relasi dengan tabel users untuk pemverifikasi_srs
            ->join('mutu', 'srs.id_srs = mutu.id_srs', 'left') // Relasi dengan tabel mutu berdasarkan id_srs
            ->where('srs.status_srs', 'pemverifikasi_srs') // Filter berdasarkan status_srs 'pemverifikasi_srs'
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pemverifikasi_srs
    public function updatepemverifikasi_srs($id_pemverifikasi_srs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pemverifikasi_srs
        $builder->where('id_pemverifikasi_srs', $id_pemverifikasi_srs);  // Menentukan baris yang akan diupdate berdasarkan id_pemverifikasi_srs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepemverifikasi_srs($id_pemverifikasi_srs)
    {
        return $this->delete($id_pemverifikasi_srs);
    }

}
