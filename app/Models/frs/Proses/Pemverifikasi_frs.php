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

    public function getpemverifikasi_frs()
    {
        return $this->select(
            '
        pemverifikasi_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs,
        penerimaan_frs.id_penerimaan_frs,
        pembacaan_frs.id_pembacaan_frs,
        pembacaan_frs.id_pembacaan_frs, 
        authorized_frs.id_authorized_frs,
        pencetakan_frs.id_pencetakan_frs'
        )
            ->join('frs', 'pemverifikasi_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemverifikasi_frs.id_user_pemverifikasi_frs = users.id_user', 'left')
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->join('penerimaan_frs', 'frs.id_frs = penerimaan_frs.id_frs', 'left')
            ->join('pembacaan_frs', 'frs.id_frs = pembacaan_frs.id_frs', 'left')
            ->join('authorized_frs', 'frs.id_frs = authorized_frs.id_frs', 'left')
            ->join('pencetakan_frs', 'frs.id_frs = pencetakan_frs.id_frs', 'left')
            ->whereIn('frs.status_frs', ['Pemverifikasi'])
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
