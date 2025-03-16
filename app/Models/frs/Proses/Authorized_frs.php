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

    public function getauthorized_frs()
    {
        return $this->select(
            '
        authorized_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs,
        penerimaan_frs.id_penerimaan_frs,
        pembacaan_frs.id_pembacaan_frs, 
        pemverifikasi_frs.id_pemverifikasi_frs,
        pencetakan_frs.id_pencetakan_frs
        '
        )
            ->join('frs', 'authorized_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_frs.id_user_authorized_frs = users.id_user', 'left')
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->join('penerimaan_frs', 'frs.id_frs = penerimaan_frs.id_frs', 'left')
            ->join('pembacaan_frs', 'frs.id_frs = pembacaan_frs.id_frs', 'left')
            ->join('pemverifikasi_frs', 'frs.id_frs = pemverifikasi_frs.id_frs', 'left')
            ->join('pencetakan_frs', 'frs.id_frs = pencetakan_frs.id_frs', 'left')
            ->where('frs.status_frs', 'Authorized')
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
