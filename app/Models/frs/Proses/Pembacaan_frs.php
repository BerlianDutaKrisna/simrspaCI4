<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pembacaan_frs extends Model // Update nama model
{
    protected $table      = 'pembacaan_frs'; // Nama tabel
    protected $primaryKey = 'id_pembacaan_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_pembacaan_frs',
        'status_pembacaan_frs',
        'mulai_pembacaan_frs',
        'selesai_pembacaan_frs',
        'id_user_dokter_pembacaan_frs',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pembacaan_frs dengan relasi
    public function getpembacaan_frs()
    {
        return $this->select(
            '
        pembacaan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pembacaan_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs,
        pembacaan_frs.id_pembacaan_frs, 
        pembacaan_frs.id_user_dokter_pembacaan_frs,
        dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan_frs'
        )
            ->join('frs', 'pembacaan_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pembacaan_frs.id_user_pembacaan_frs = users.id_user', 'left')
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->join('users AS dokter_pembacaan', 'pembacaan_frs.id_user_dokter_pembacaan_frs = dokter_pembacaan.id_user', 'left')
            ->whereIn('frs.status_frs', ['Pembacaan'])
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    public function detailspembacaan_frs($id_pembacaan_frs)
    {
        $data = $this->select(
            'pembacaan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_pembacaan_frs'
        )
            ->join('frs', 'pembacaan_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pembacaan_frs.id_user_dokter_pembacaan_frs = users.id_user', 'left')
            ->where('pembacaan_frs.id_pembacaan_frs', $id_pembacaan_frs)
            ->first();

        return $data;
    }
}
