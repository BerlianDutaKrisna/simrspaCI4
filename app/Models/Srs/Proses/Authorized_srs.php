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
        'id_user_authorized_srs',
        'status_authorized_srs',
        'mulai_authorized_srs',
        'selesai_authorized_srs',
        'id_user_dokter_authorized_srs',
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

    public function getauthorized_srs()
    {
        return $this->select(
            '
        authorized_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_srs,
        mutu_srs.id_mutu_srs,
        mutu_srs.total_nilai_mutu_srs,
        penerimaan_srs.id_penerimaan_srs,
        pembacaan_srs.id_pembacaan_srs, 
        pemverifikasi_srs.id_pemverifikasi_srs,
        pencetakan_srs.id_pencetakan_srs
        '
        )
            ->join('srs', 'authorized_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_srs.id_user_authorized_srs = users.id_user', 'left')
            ->join('mutu_srs', 'srs.id_srs = mutu_srs.id_srs', 'left')
            ->join('penerimaan_srs', 'srs.id_srs = penerimaan_srs.id_srs', 'left')
            ->join('pembacaan_srs', 'srs.id_srs = pembacaan_srs.id_srs', 'left')
            ->join('pemverifikasi_srs', 'srs.id_srs = pemverifikasi_srs.id_srs', 'left')
            ->join('pencetakan_srs', 'srs.id_srs = pencetakan_srs.id_srs', 'left')
            ->where('srs.status_srs', 'Authorized')
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
