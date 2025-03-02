<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Authorized_frs extends Model // Update nama model
{
    protected $table      = 'authorized_frs'; // Nama tabel
    protected $primaryKey = 'id_authorized_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_authorized_frs', // Update nama kolom
        'status_authorized_frs', // Update nama kolom
        'mulai_authorized_frs', // Update nama kolom
        'selesai_authorized_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data authorized_frs
    public function insertauthorized_frs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data authorized_frs dengan relasi
    public function getauthorized_frsWithRelations()
    {
        return $this->select(
            '
        authorized_frs.*, 
        frs.*, 
        patient.*, 
        dokter_pemotongan.nama_user AS nama_user_dokter_pemotongan, 
        users.nama_user AS nama_user_authorized_frs, 
        mutu.total_nilai_mutu'
        )
            ->join('frs', 'authorized_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('pemotongan', 'frs.id_pemotongan = pemotongan.id_pemotongan', 'left')
            ->join('users AS dokter_pemotongan', 'pemotongan.id_user_dokter_pemotongan = dokter_pemotongan.id_user', 'left') // Benar-benar mengambil nama dokter pemotongan
            ->join('users', 'authorized_frs.id_user_authorized_frs = users.id_user', 'left') // Mengambil nama user untuk authorized_frs
            ->join('mutu', 'frs.id_frs = mutu.id_frs', 'left')
            ->where('frs.status_frs', 'authorized_frs')
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data authorized_frs
    public function updateauthorized_frs($id_authorized_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel authorized_frs
        $builder->where('id_authorized_frs', $id_authorized_frs);  // Menentukan baris yang akan diupdate berdasarkan id_authorized_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deleteauthorized_frs($id_authorized_frs)
    {
        return $this->delete($id_authorized_frs);
    }

}
