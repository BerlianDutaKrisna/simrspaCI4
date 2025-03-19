<?php

namespace App\Models\srs\Proses; // Update namespace sesuai dengan folder

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

    public function getpemverifikasi_srs()
    {
        return $this->select(
            '
        pemverifikasi_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi_srs,
        mutu_srs.id_mutu_srs,
        mutu_srs.total_nilai_mutu_srs,
        penerimaan_srs.id_penerimaan_srs,
        pembacaan_srs.id_pembacaan_srs,
        pembacaan_srs.id_pembacaan_srs, 
        authorized_srs.id_authorized_srs,
        pencetakan_srs.id_pencetakan_srs'
        )
            ->join('srs', 'pemverifikasi_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemverifikasi_srs.id_user_pemverifikasi_srs = users.id_user', 'left')
            ->join('mutu_srs', 'srs.id_srs = mutu_srs.id_srs', 'left')
            ->join('penerimaan_srs', 'srs.id_srs = penerimaan_srs.id_srs', 'left')
            ->join('pembacaan_srs', 'srs.id_srs = pembacaan_srs.id_srs', 'left')
            ->join('authorized_srs', 'srs.id_srs = authorized_srs.id_srs', 'left')
            ->join('pencetakan_srs', 'srs.id_srs = pencetakan_srs.id_srs', 'left')
            ->whereIn('srs.status_srs', ['Pemverifikasi'])
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
