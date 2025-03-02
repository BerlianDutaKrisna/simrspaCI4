<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_ihc extends Model // Update nama model
{
    protected $table      = 'penulisa_ihc'; // Nama tabel
    protected $primaryKey = 'id_penulisa_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_penulisa_ihc', // Update nama kolom
        'status_penulisa_ihc', // Update nama kolom
        'mulai_penulisa_ihc', // Update nama kolom
        'selesai_penulisa_ihc', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data penulisa_ihc
    public function insertpenulisa_ihc(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data penulisa_ihc dengan relasi
    public function getpenulisa_ihcWithRelations() // Update nama fungsi
    {
        return $this->select(
            '
        penulisa_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_penulisa_ihc,
        mutu.total_nilai_mutu'
        )
            ->join('ihc', 'penulisa_ihc.id_ihc = ihc.id_ihc', 'left') // Relasi dengan tabel ihc
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penulisa_ihc.id_user_penulisa_ihc = users.id_user', 'left') // Relasi dengan tabel users untuk penulisa_ihc
            ->join('mutu', 'ihc.id_ihc = mutu.id_ihc', 'left') // Relasi dengan tabel mutu berdasarkan id_ihc
            ->where('ihc.status_ihc', 'penulisa_ihc') // Filter berdasarkan status_ihc 'penulisa_ihc'
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penulisa_ihc
    public function updatepenulisa_ihc($id_penulisa_ihc, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel penulisa_ihc
        $builder->where('id_penulisa_ihc', $id_penulisa_ihc);  // Menentukan baris yang akan diupdate berdasarkan id_penulisa_ihc
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenulisa_ihc($id_penulisa_ihc)
    {
        return $this->delete($id_penulisa_ihc);
    }

}
