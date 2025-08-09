<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pemverifikasi_hpa extends Model // Update nama model
{
    protected $table      = 'pemverifikasi_hpa'; // Nama tabel
    protected $primaryKey = 'id_pemverifikasi_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemverifikasi_hpa', // Update nama kolom
        'status_pemverifikasi_hpa', // Update nama kolom
        'mulai_pemverifikasi_hpa', // Update nama kolom
        'selesai_pemverifikasi_hpa', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getpemverifikasi_hpa()
    {
        return $this->select(
            '
        pemverifikasi_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa,
        penerimaan_hpa.id_penerimaan_hpa,
        pembacaan_hpa.id_pembacaan_hpa,
        pembacaan_hpa.id_pembacaan_hpa, 
        authorized_hpa.id_authorized_hpa,
        pencetakan_hpa.id_pencetakan_hpa'
        )
            ->join('hpa', 'pemverifikasi_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemverifikasi_hpa.id_user_pemverifikasi_hpa = users.id_user', 'left')
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
            ->join('penerimaan_hpa', 'hpa.id_hpa = penerimaan_hpa.id_hpa', 'left')
            ->join('pembacaan_hpa', 'hpa.id_hpa = pembacaan_hpa.id_hpa', 'left')
            ->join('authorized_hpa', 'hpa.id_hpa = authorized_hpa.id_hpa', 'left')
            ->join('pencetakan_hpa', 'hpa.id_hpa = pencetakan_hpa.id_hpa', 'left')
            ->whereIn('hpa.status_hpa', ['Pemverifikasi'])
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    public function detailspemverifikasi_hpa($id_pemverifikasi_hpa)
    {
        $data = $this->select(
            'pemverifikasi_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi_hpa'
        )
            ->join('hpa', 'pemverifikasi_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemverifikasi_hpa.id_user_pemverifikasi_hpa = users.id_user', 'left')
            ->where('pemverifikasi_hpa.id_pemverifikasi_hpa', $id_pemverifikasi_hpa)
            ->first();

        return $data;
    }
}
