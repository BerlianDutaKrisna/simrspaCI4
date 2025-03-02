<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Authorized_srs extends Model // Update nama model
{
    protected $table      = 'authorized_srs'; // Nama tabel
    protected $primaryKey = 'id_authorized_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_authorized_srs', // Update nama kolom
        'status_authorized_srs', // Update nama kolom
        'mulai_authorized_srs', // Update nama kolom
        'selesai_authorized_srs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data authorized_srs
    public function insertauthorized_srs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data authorized_srs dengan relasi
    public function getauthorized_srsWithRelations()
    {
        return $this->select(
            '
        authorized_srs.*, 
        srs.*, 
        patient.*, 
        dokter_pemotongan.nama_user AS nama_user_dokter_pemotongan, 
        users.nama_user AS nama_user_authorized_srs, 
        mutu.total_nilai_mutu'
        )
            ->join('srs', 'authorized_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('pemotongan', 'srs.id_pemotongan = pemotongan.id_pemotongan', 'left')
            ->join('users AS dokter_pemotongan', 'pemotongan.id_user_dokter_pemotongan = dokter_pemotongan.id_user', 'left') // Benar-benar mengambil nama dokter pemotongan
            ->join('users', 'authorized_srs.id_user_authorized_srs = users.id_user', 'left') // Mengambil nama user untuk authorized_srs
            ->join('mutu', 'srs.id_srs = mutu.id_srs', 'left')
            ->where('srs.status_srs', 'authorized_srs')
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data authorized_srs
    public function updateauthorized_srs($id_authorized_srs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel authorized_srs
        $builder->where('id_authorized_srs', $id_authorized_srs);  // Menentukan baris yang akan diupdate berdasarkan id_authorized_srs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deleteauthorized_srs($id_authorized_srs)
    {
        return $this->delete($id_authorized_srs);
    }

}
