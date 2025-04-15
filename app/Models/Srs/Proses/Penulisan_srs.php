<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penulisan_srs extends Model // Update nama model
{
    protected $table      = 'penulisan_srs'; // Nama tabel
    protected $primaryKey = 'id_penulisan_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_penulisan_srs', // Update nama kolom
        'status_penulisan_srs', // Update nama kolom
        'mulai_penulisan_srs', // Update nama kolom
        'selesai_penulisan_srs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil data pembacaan_srs dengan relasi
    public function getpenulisan_srs()
    {
        return $this->select(
            '
            penulisan_srs.*, 
            srs.*, 
            patient.*, 
            users.nama_user AS nama_user_penulisan_srs,
            mutu_srs.id_mutu_srs,
            mutu_srs.total_nilai_mutu_srs,
            pembacaan_srs.id_pembacaan_srs, 
            pembacaan_srs.id_user_dokter_pembacaan_srs,
            dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan_srs'
        )
            ->join('srs', 'penulisan_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penulisan_srs.id_user_penulisan_srs = users.id_user', 'left')
            ->join('mutu_srs', 'srs.id_srs = mutu_srs.id_srs', 'left')
            ->join('pembacaan_srs', 'srs.id_srs = pembacaan_srs.id_srs', 'left')
            ->join('users AS dokter_pembacaan', 'pembacaan_srs.id_user_dokter_pembacaan_srs = dokter_pembacaan.id_user', 'left')
            ->whereIn('srs.status_srs', ['Penulisan'])
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    public function detailspenulisan_srs($id_penulisan_srs)
    {
        $data = $this->select(
            'penulisan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_penulisan_srs'
        )
            ->join('srs', 'penulisan_srs.id_srs = srs.id_srs', 'left')
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penulisan_srs.id_user_penulisan_srs = users.id_user', 'left')
            ->where('penulisan_srs.id_penulisan_srs', $id_penulisan_srs)
            ->first();

        return $data;
    }
}
