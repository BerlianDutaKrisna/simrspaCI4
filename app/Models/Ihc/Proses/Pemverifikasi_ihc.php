<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pemverifikasi_ihc extends Model // Update nama model
{
    protected $table      = 'pemverifikasi_ihc'; // Nama tabel
    protected $primaryKey = 'id_pemverifikasi_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_pemverifikasi_ihc', // Update nama kolom
        'status_pemverifikasi_ihc', // Update nama kolom
        'mulai_pemverifikasi_ihc', // Update nama kolom
        'selesai_pemverifikasi_ihc', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pemverifikasi_ihc
    public function insertpemverifikasi_ihc(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pemverifikasi_ihc dengan relasi
    public function getpemverifikasi_ihcWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pemverifikasi_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi_ihc,
        mutu.total_nilai_mutu'
        )
            ->join('ihc', 'pemverifikasi_ihc.id_ihc = ihc.id_ihc', 'left') // Relasi dengan tabel ihc
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pemverifikasi_ihc.id_user_pemverifikasi_ihc = users.id_user', 'left') // Relasi dengan tabel users untuk pemverifikasi_ihc
            ->join('mutu', 'ihc.id_ihc = mutu.id_ihc', 'left') // Relasi dengan tabel mutu berdasarkan id_ihc
            ->where('ihc.status_ihc', 'pemverifikasi_ihc') // Filter berdasarkan status_ihc 'pemverifikasi_ihc'
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pemverifikasi_ihc
    public function updatepemverifikasi_ihc($id_pemverifikasi_ihc, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pemverifikasi_ihc
        $builder->where('id_pemverifikasi_ihc', $id_pemverifikasi_ihc);  // Menentukan baris yang akan diupdate berdasarkan id_pemverifikasi_ihc
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepemverifikasi_ihc($id_pemverifikasi_ihc)
    {
        return $this->delete($id_pemverifikasi_ihc);
    }

}
