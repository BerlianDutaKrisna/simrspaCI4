<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pencetakan_hpa extends Model // Update nama model
{
    protected $table      = 'pencetakan_hpa'; // Nama tabel
    protected $primaryKey = 'id_pencetakan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pencetakan_hpa', // Update nama kolom
        'status_pencetakan_hpa', // Update nama kolom
        'mulai_pencetakan_hpa', // Update nama kolom
        'selesai_pencetakan_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pencetakan_hpa
    public function insertpencetakan_hpa(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    public function getpencetakan_hpa()
    {
        return $this->select(
            '
        pencetakan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa,
        penerimaan_hpa.id_penerimaan_hpa,
        pembacaan_hpa.id_pembacaan_hpa, 
        pemverifikasi_hpa.id_pemverifikasi_hpa,
        authorized_hpa.id_authorized_hpa
        '
        )
            ->join('hpa', 'pencetakan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pencetakan_hpa.id_user_pencetakan_hpa = users.id_user', 'left')
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->join('penerimaan_hpa', 'hpa.id_hpa = penerimaan_hpa.id_hpa', 'left')
            ->join('pembacaan_hpa', 'hpa.id_hpa = pembacaan_hpa.id_hpa', 'left')
            ->join('pemverifikasi_hpa', 'hpa.id_hpa = pemverifikasi_hpa.id_hpa', 'left')
            ->join('authorized_hpa', 'hpa.id_hpa = authorized_hpa.id_hpa', 'left')
            ->where('hpa.status_hpa', 'Pencetakan') // Sesuaikan jika ada kondisi lain
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pencetakan_hpa
    public function updatepencetakan_hpa($id_pencetakan_hpa, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pencetakan_hpa
        $builder->where('id_pencetakan_hpa', $id_pencetakan_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_pencetakan_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepencetakan_hpa($id_pencetakan_hpa)
    {
        return $this->delete($id_pencetakan_hpa);
    }
}
