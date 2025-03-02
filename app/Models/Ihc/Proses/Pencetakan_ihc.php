<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pencetakan_ihc extends Model // Update nama model
{
    protected $table      = 'pencetakan_ihc'; // Nama tabel
    protected $primaryKey = 'id_pencetakan_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_pencetakan_ihc', // Update nama kolom
        'status_pencetakan_ihc', // Update nama kolom
        'mulai_pencetakan_ihc', // Update nama kolom
        'selesai_pencetakan_ihc', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pencetakan_ihc
    public function insertpencetakan_ihc(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pencetakan_ihc dengan relasi
    public function getpencetakan_ihcWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        pencetakan_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_ihc,
        mutu.total_nilai_mutu'
        )
            ->join('ihc', 'pencetakan_ihc.id_ihc = ihc.id_ihc', 'left') // Relasi dengan tabel ihc
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pencetakan_ihc.id_user_pencetakan_ihc = users.id_user', 'left') // Relasi dengan tabel users untuk pencetakan_ihc
            ->join('mutu', 'ihc.id_ihc = mutu.id_ihc', 'left') // Relasi dengan tabel mutu berdasarkan id_ihc
            ->where('ihc.status_ihc', 'pencetakan_ihc') // Filter berdasarkan status_ihc 'pencetakan_ihc'
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pencetakan_ihc
    public function updatepencetakan_ihc($id_pencetakan_ihc, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pencetakan_ihc
        $builder->where('id_pencetakan_ihc', $id_pencetakan_ihc);  // Menentukan baris yang akan diupdate berdasarkan id_pencetakan_ihc
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepencetakan_ihc($id_pencetakan_ihc)
    {
        return $this->delete($id_pencetakan_ihc);
    }

}
