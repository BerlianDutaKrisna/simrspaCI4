<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Authorized_ihc extends Model // Update nama model
{
    protected $table      = 'authorized_ihc'; // Nama tabel
    protected $primaryKey = 'id_authorized_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_authorized_ihc',
        'status_authorized_ihc',
        'mulai_authorized_ihc',
        'selesai_authorized_ihc',
        'id_user_dokter_authorized_ihc',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data authorized_ihc
    public function insertauthorized_ihc(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    public function getauthorized_ihc()
    {
        return $this->select(
            '
        authorized_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_ihc,
        mutu_ihc.id_mutu_ihc,
        mutu_ihc.total_nilai_mutu_ihc,
        penerimaan_ihc.id_penerimaan_ihc,
        pembacaan_ihc.id_pembacaan_ihc,
        dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan, 
        pemverifikasi_ihc.id_pemverifikasi_ihc,
        pencetakan_ihc.id_pencetakan_ihc
        '
        )
            ->join('ihc', 'authorized_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_ihc.id_user_authorized_ihc = users.id_user', 'left')
            ->join('mutu_ihc', 'ihc.id_ihc = mutu_ihc.id_ihc', 'left')
            ->join('penerimaan_ihc', 'ihc.id_ihc = penerimaan_ihc.id_ihc', 'left')
            ->join('pembacaan_ihc', 'ihc.id_ihc = pembacaan_ihc.id_ihc', 'left')
            ->join('users AS dokter_pembacaan','dokter_pembacaan.id_user = pembacaan_ihc.id_user_dokter_pembacaan_ihc','left')
            ->join('pemverifikasi_ihc', 'ihc.id_ihc = pemverifikasi_ihc.id_ihc', 'left')
            ->join('pencetakan_ihc', 'ihc.id_ihc = pencetakan_ihc.id_ihc', 'left')
            ->where('ihc.status_ihc', 'Authorized')
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    public function detailsauthorized_ihc($id_authorized_ihc)
    {
        $data = $this->select(
            'authorized_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_ihc'
        )
            ->join('ihc', 'authorized_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'authorized_ihc.id_user_dokter_authorized_ihc = users.id_user', 'left')
            ->where('authorized_ihc.id_authorized_ihc', $id_authorized_ihc)
            ->first();

        return $data;
    }
}
