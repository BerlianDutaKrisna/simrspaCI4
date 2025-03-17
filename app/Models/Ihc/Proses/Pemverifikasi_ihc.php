<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pemverifikasi_ihc extends Model // Update nama model
{
    protected $table      = 'pemverifikasi_ihc'; // Nama tabel
    protected $primaryKey = 'id_pemverifikasi_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_pemverifikasi_ihc', // Update nama kolom
        'status_pemverifikasi_ihc', // Update nama kolom
        'mulai_pemverifikasi_ihc', // Update nama kolom
        'selesai_pemverifikasi_ihc', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getpemverifikasi_ihc()
    {
        return $this->select(
            '
        pemverifikasi_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_pemverifikasi_ihc,
        mutu_ihc.id_mutu_ihc,
        mutu_ihc.total_nilai_mutu_ihc,
        penerimaan_ihc.id_penerimaan_ihc,
        pembacaan_ihc.id_pembacaan_ihc,
        pembacaan_ihc.id_pembacaan_ihc, 
        authorized_ihc.id_authorized_ihc,
        pencetakan_ihc.id_pencetakan_ihc'
        )
            ->join('ihc', 'pemverifikasi_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->join('users', 'pemverifikasi_ihc.id_user_pemverifikasi_ihc = users.id_user', 'left')
            ->join('mutu_ihc', 'ihc.id_ihc = mutu_ihc.id_ihc', 'left')
            ->join('penerimaan_ihc', 'ihc.id_ihc = penerimaan_ihc.id_ihc', 'left')
            ->join('pembacaan_ihc', 'ihc.id_ihc = pembacaan_ihc.id_ihc', 'left')
            ->join('authorized_ihc', 'ihc.id_ihc = authorized_ihc.id_ihc', 'left')
            ->join('pencetakan_ihc', 'ihc.id_ihc = pencetakan_ihc.id_ihc', 'left')
            ->whereIn('ihc.status_ihc', ['Pemverifikasi'])
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data pemverifikasi_ihc
    public function updatepemverifikasi_ihc($id_pemverifikasi_ihc, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pemverifikasi_ihc
        $builder->where('id_pemverifikasi_ihc', $id_pemverifikasi_ihc);  // Menentukan baris yang akan diupdate berdasarkan id_pemverifikasi_ihc
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepemverifikasi_ihc($id_pemverifikasi_ihc)
    {
        return $this->delete($id_pemverifikasi_ihc);
    }

}
