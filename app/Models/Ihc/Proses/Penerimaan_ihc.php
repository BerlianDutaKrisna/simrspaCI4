<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penerimaan_ihc extends Model
{
    protected $table      = 'penerimaan_ihc'; // Nama tabel
    protected $primaryKey = 'id_penerimaan_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_penerimaan_ihc',
        'status_penerimaan_ihc',
        'mulai_penerimaan_ihc',
        'selesai_penerimaan_ihc',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPenerimaan_ihc()
    {
        return $this->select(
            '
        penerimaan_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_penerimaan_ihc,
        mutu_ihc.id_mutu_ihc,
        mutu_ihc.total_nilai_mutu_ihc'
        )
            ->join('ihc', 'penerimaan_ihc.id_ihc = ihc.id_ihc', 'left') // Relasi dengan tabel ihc
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penerimaan_ihc.id_user_penerimaan_ihc = users.id_user', 'left') // Relasi dengan tabel users untuk penerimaan_ihc
            ->join('mutu_ihc', 'ihc.id_ihc = mutu_ihc.id_ihc', 'left') // Relasi dengan tabel mutu_ihc berdasarkan id_ihc
            ->whereIn('ihc.status_ihc', ['penerimaan_ihc', 'Penerimaan']) // Menambahkan filter whereIn untuk status_ihc
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    public function detailspenerimaan_ihc($id_penerimaan_ihc)
    {
        $data = $this->select(
            'penerimaan_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_penerimaan_ihc'
        )
            ->join('ihc', 'penerimaan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'penerimaan_ihc.id_user_penerimaan_ihc = users.id_user', 'left')
            ->where('penerimaan_ihc.id_penerimaan_ihc', $id_penerimaan_ihc)
            ->first();

        return $data;
    }
}
