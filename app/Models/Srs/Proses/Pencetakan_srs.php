<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pencetakan_srs extends Model // Update nama model
{
    protected $table      = 'pencetakan_srs'; // Nama tabel
    protected $primaryKey = 'id_pencetakan_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_pencetakan_srs', // Update nama kolom
        'status_pencetakan_srs', // Update nama kolom
        'mulai_pencetakan_srs', // Update nama kolom
        'selesai_pencetakan_srs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pencetakan_srs
    public function insertpencetakan_srs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    public function getpencetakan_srs()
    {
        return $this->select(
            '
        pencetakan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_srs,
        mutu_srs.id_mutu_srs,
        mutu_srs.total_nilai_mutu_srs,
        penerimaan_srs.id_penerimaan_srs,
        pembacaan_srs.id_pembacaan_srs, 
        pemverifikasi_srs.id_pemverifikasi_srs,
        authorized_srs.id_authorized_srs
        '
        )
            ->join('srs', 'pencetakan_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pencetakan_srs.id_user_pencetakan_srs = users.id_user', 'left')
            ->join('mutu_srs', 'srs.id_srs = mutu_srs.id_srs', 'left')
            ->join('penerimaan_srs', 'srs.id_srs = penerimaan_srs.id_srs', 'left')
            ->join('pembacaan_srs', 'srs.id_srs = pembacaan_srs.id_srs', 'left')
            ->join('pemverifikasi_srs', 'srs.id_srs = pemverifikasi_srs.id_srs', 'left')
            ->join('authorized_srs', 'srs.id_srs = authorized_srs.id_srs', 'left')
            ->where('srs.status_srs', 'Pencetakan') // Sesuaikan jika ada kondisi lain
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pencetakan_srs
    public function updatepencetakan_srs($id_pencetakan_srs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pencetakan_srs
        $builder->where('id_pencetakan_srs', $id_pencetakan_srs);  // Menentukan baris yang akan diupdate berdasarkan id_pencetakan_srs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepencetakan_srs($id_pencetakan_srs)
    {
        return $this->delete($id_pencetakan_srs);
    }

}
