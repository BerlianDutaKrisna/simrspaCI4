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
        'id_user_authorized_ihc', // Update nama kolom
        'status_authorized_ihc', // Update nama kolom
        'mulai_authorized_ihc', // Update nama kolom
        'selesai_authorized_ihc', // Update nama kolom
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

    // Mengambil data authorized_ihc dengan relasi
    public function getauthorized_ihcWithRelations()
    {
        return $this->select(
            '
        authorized_ihc.*, 
        ihc.*, 
        patient.*, 
        dokter_pemotongan.nama_user AS nama_user_dokter_pemotongan, 
        users.nama_user AS nama_user_authorized_ihc, 
        mutu.total_nilai_mutu'
        )
            ->join('ihc', 'authorized_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left')
            ->join('pemotongan', 'ihc.id_pemotongan = pemotongan.id_pemotongan', 'left')
            ->join('users AS dokter_pemotongan', 'pemotongan.id_user_dokter_pemotongan = dokter_pemotongan.id_user', 'left') // Benar-benar mengambil nama dokter pemotongan
            ->join('users', 'authorized_ihc.id_user_authorized_ihc = users.id_user', 'left') // Mengambil nama user untuk authorized_ihc
            ->join('mutu', 'ihc.id_ihc = mutu.id_ihc', 'left')
            ->where('ihc.status_ihc', 'authorized_ihc')
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data authorized_ihc
    public function updateauthorized_ihc($id_authorized_ihc, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel authorized_ihc
        $builder->where('id_authorized_ihc', $id_authorized_ihc);  // Menentukan baris yang akan diupdate berdasarkan id_authorized_ihc
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deleteauthorized_ihc($id_authorized_ihc)
    {
        return $this->delete($id_authorized_ihc);
    }

}
