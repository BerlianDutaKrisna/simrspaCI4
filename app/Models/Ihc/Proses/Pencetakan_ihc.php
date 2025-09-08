<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pencetakan_ihc extends Model // Update nama model
{
    protected $table      = 'pencetakan_ihc'; // Nama tabel
    protected $primaryKey = 'id_pencetakan_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_pencetakan_ihc', // Update nama kolom
        'status_pencetakan_ihc', // Update nama kolom
        'mulai_pencetakan_ihc', // Update nama kolom
        'selesai_pencetakan_ihc', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pencetakan_ihc
    public function insertpencetakan_ihc(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    public function getpencetakan_ihc()
    {
        return $this->select(
            '
        pencetakan_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_ihc,
        mutu_ihc.id_mutu_ihc,
        mutu_ihc.total_nilai_mutu_ihc,
        penerimaan_ihc.id_penerimaan_ihc,
        pembacaan_ihc.id_pembacaan_ihc, 
        dokter_pembacaan.nama_user AS nama_user_dokter_pembacaan, 
        pemverifikasi_ihc.id_pemverifikasi_ihc,
        authorized_ihc.id_authorized_ihc
        '
        )
            ->join('ihc', 'pencetakan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pencetakan_ihc.id_user_pencetakan_ihc = users.id_user', 'left')
            ->join('mutu_ihc', 'ihc.id_ihc = mutu_ihc.id_ihc', 'left')
            ->join('penerimaan_ihc', 'ihc.id_ihc = penerimaan_ihc.id_ihc', 'left')
            ->join('pembacaan_ihc', 'ihc.id_ihc = pembacaan_ihc.id_ihc', 'left')
            ->join('users AS dokter_pembacaan','dokter_pembacaan.id_user = pembacaan_ihc.id_user_dokter_pembacaan_ihc','left')
            ->join('pemverifikasi_ihc', 'ihc.id_ihc = pemverifikasi_ihc.id_ihc', 'left')
            ->join('authorized_ihc', 'ihc.id_ihc = authorized_ihc.id_ihc', 'left')
            ->where('ihc.status_ihc', 'Pencetakan') // Sesuaikan jika ada kondisi lain
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    public function detailspencetakan_ihc($id_pencetakan_ihc)
    {
        $data = $this->select(
            'pencetakan_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_pencetakan_ihc'
        )
            ->join('ihc', 'pencetakan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pencetakan_ihc.id_user_pencetakan_ihc = users.id_user', 'left')
            ->where('pencetakan_ihc.id_pencetakan_ihc', $id_pencetakan_ihc)
            ->first();

        return $data;
    }
}
