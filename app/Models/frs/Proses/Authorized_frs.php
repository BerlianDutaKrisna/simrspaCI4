<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Authorized_frs extends Model // Update nama model
{
    protected $table      = 'authorized_frs'; // Nama tabel
    protected $primaryKey = 'id_authorized_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_authorized_frs',
        'status_authorized_frs',
        'mulai_authorized_frs',
        'selesai_authorized_frs',
        'id_user_dokter_authorized_frs',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data authorized_frs
    public function insertauthorized_frs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    public function getauthorized_frs()
    {
        return $this->select(
            '
        authorized_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs,
        penerimaan_frs.id_penerimaan_frs,
        pembacaan_frs.id_pembacaan_frs,
        dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan, 
        pemverifikasi_frs.id_pemverifikasi_frs,
        pencetakan_frs.id_pencetakan_frs
        '
        )
            ->join('frs', 'authorized_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_frs.id_user_authorized_frs = users.id_user', 'left')
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->join('penerimaan_frs', 'frs.id_frs = penerimaan_frs.id_frs', 'left')
            ->join('pembacaan_frs', 'frs.id_frs = pembacaan_frs.id_frs', 'left')
            ->join('users AS dokter_pembacaan','dokter_pembacaan.id_user = pembacaan_frs.id_user_dokter_pembacaan_frs','left')
            ->join('pemverifikasi_frs', 'frs.id_frs = pemverifikasi_frs.id_frs', 'left')
            ->join('pencetakan_frs', 'frs.id_frs = pencetakan_frs.id_frs', 'left')
            ->where('frs.status_frs', 'Authorized')
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    public function getauthorized_frs_by_dokter($namaDokter)
    {
        return $this->select(
            '
        authorized_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs,
        penerimaan_frs.id_penerimaan_frs,
        pembacaan_frs.id_pembacaan_frs,
        dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan,
        pemverifikasi_frs.id_pemverifikasi_frs,
        pencetakan_frs.id_pencetakan_frs
        '
        )
            ->join('frs', 'authorized_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_frs.id_user_authorized_frs = users.id_user', 'left')
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left')
            ->join('penerimaan_frs', 'frs.id_frs = penerimaan_frs.id_frs', 'left')
            ->join('pembacaan_frs', 'frs.id_frs = pembacaan_frs.id_frs', 'left')
            ->join('users AS dokter_pembacaan', 'dokter_pembacaan.id_user = pembacaan_frs.id_user_dokter_pembacaan_frs', 'left')
            ->join('pemverifikasi_frs', 'frs.id_frs = pemverifikasi_frs.id_frs', 'left')
            ->join('pencetakan_frs', 'frs.id_frs = pencetakan_frs.id_frs', 'left')
            ->where('frs.status_frs', 'Authorized')
            ->where('dokter_pembacaan.nama_user', $namaDokter)
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    public function detailsauthorized_frs($id_authorized_frs)
    {
        $data = $this->select(
            'authorized_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_frs'
        )
            ->join('frs', 'authorized_frs.id_frs = frs.id_frs', 'left')
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_frs.id_user_dokter_authorized_frs = users.id_user', 'left')
            ->where('authorized_frs.id_authorized_frs', $id_authorized_frs)
            ->first();

        return $data;
    }
}
