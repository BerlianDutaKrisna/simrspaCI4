<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pencetakan_frs extends Model // Update nama model
{
    protected $table      = 'pencetakan_frs'; // Nama tabel
    protected $primaryKey = 'id_pencetakan_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_pencetakan_frs', // Update nama kolom
        'status_pencetakan_frs', // Update nama kolom
        'mulai_pencetakan_frs', // Update nama kolom
        'selesai_pencetakan_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pencetakan_frs
    public function insertpencetakan_frs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    public function getpencetakan_frs()
    {
        return $this->select(
            '
        pencetakan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs,
        penerimaan_frs.id_penerimaan_frs,
        pembacaan_frs.id_pembacaan_frs, 
        pemverifikasi_frs.id_pemverifikasi_frs,
        authorized_frs.id_authorized_frs
        '
        )
            ->join('frs', 'pencetakan_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pencetakan_frs.id_user_pencetakan_frs = users.id_user', 'left')
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->join('penerimaan_frs', 'frs.id_frs = penerimaan_frs.id_frs', 'left')
            ->join('pembacaan_frs', 'frs.id_frs = pembacaan_frs.id_frs', 'left')
            ->join('pemverifikasi_frs', 'frs.id_frs = pemverifikasi_frs.id_frs', 'left')
            ->join('authorized_frs', 'frs.id_frs = authorized_frs.id_frs', 'left')
            ->where('frs.status_frs', 'Pencetakan') // Sesuaikan jika ada kondisi lain
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pencetakan_frs
    public function updatepencetakan_frs($id_pencetakan_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pencetakan_frs
        $builder->where('id_pencetakan_frs', $id_pencetakan_frs);  // Menentukan baris yang akan diupdate berdasarkan id_pencetakan_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepencetakan_frs($id_pencetakan_frs)
    {
        return $this->delete($id_pencetakan_frs);
    }

}
