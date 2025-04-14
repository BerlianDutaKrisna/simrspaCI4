<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Authorized_hpa extends Model // Update nama model
{
    protected $table      = 'authorized_hpa'; // Nama tabel
    protected $primaryKey = 'id_authorized_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_authorized_hpa',
        'status_authorized_hpa',
        'mulai_authorized_hpa',
        'selesai_authorized_hpa',
        'id_user_dokter_authorized_hpa',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data authorized_hpa
    public function insertauthorized_hpa(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    public function getauthorized_hpa()
    {
        return $this->select(
            '
        authorized_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa,
        penerimaan_hpa.id_penerimaan_hpa,
        pembacaan_hpa.id_pembacaan_hpa, 
        pemverifikasi_hpa.id_pemverifikasi_hpa,
        pencetakan_hpa.id_pencetakan_hpa
        '
        )
            ->join('hpa', 'authorized_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_hpa.id_user_authorized_hpa = users.id_user', 'left')
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->join('penerimaan_hpa', 'hpa.id_hpa = penerimaan_hpa.id_hpa', 'left')
            ->join('pembacaan_hpa', 'hpa.id_hpa = pembacaan_hpa.id_hpa', 'left')
            ->join('pemverifikasi_hpa', 'hpa.id_hpa = pemverifikasi_hpa.id_hpa', 'left')
            ->join('pencetakan_hpa', 'hpa.id_hpa = pencetakan_hpa.id_hpa', 'left')
            ->where('hpa.status_hpa', 'Authorized')
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    public function detailsauthorized_hpa($id_authorized_hpa)
    {
        $data = $this->select(
            'authorized_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_hpa'
        )
            ->join('hpa', 'authorized_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_hpa.id_user_dokter_authorized_hpa = users.id_user', 'left')
            ->where('authorized_hpa.id_authorized_hpa', $id_authorized_hpa)
            ->first();

        return $data;
    }

    public function deleteauthorized_hpa($id_authorized_hpa)
    {
        return $this->delete($id_authorized_hpa);
    }
}
