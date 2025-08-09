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

    public function detailspencetakan_hpa($id_pencetakan_hpa)
    {
        $data = $this->select(
            'pencetakan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_hpa'
        )
            ->join('hpa', 'pencetakan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pencetakan_hpa.id_user_pencetakan_hpa = users.id_user', 'left')
            ->where('pencetakan_hpa.id_pencetakan_hpa', $id_pencetakan_hpa)
            ->first();

        return $data;
    }
}
