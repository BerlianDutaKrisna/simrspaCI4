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
        dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan,
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
            ->join('users AS dokter_pembacaan','dokter_pembacaan.id_user = pembacaan_frs.id_user_dokter_pembacaan_frs','left')
            ->join('pemverifikasi_frs', 'frs.id_frs = pemverifikasi_frs.id_frs', 'left')
            ->join('authorized_frs', 'frs.id_frs = authorized_frs.id_frs', 'left')
            ->where('frs.status_frs', 'Pencetakan') // Sesuaikan jika ada kondisi lain
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    public function detailspencetakan_frs($id_pencetakan_frs)
    {
        $data = $this->select(
            'pencetakan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_frs'
        )
            ->join('frs', 'pencetakan_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pencetakan_frs.id_user_pencetakan_frs = users.id_user', 'left')
            ->where('pencetakan_frs.id_pencetakan_frs', $id_pencetakan_frs)
            ->first();

        return $data;
    }
}
