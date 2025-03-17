<?php

namespace App\Models\Srs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pembacaan_srs extends Model // Update nama model
{
    protected $table      = 'pembacaan_srs'; // Nama tabel
    protected $primaryKey = 'id_pembacaan_srs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_srs',
        'id_user_pembacaan_srs',
        'status_pembacaan_srs',
        'mulai_pembacaan_srs',
        'selesai_pembacaan_srs',
        'id_user_dokter_pembacaan_srs',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pembacaan_srs
    public function insertpembacaan_srs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pembacaan_srs dengan relasi
    public function getpembacaan_srsWithRelations()
    {
        return $this->select(
            'pembacaan_srs.*, 
        srs.*, 
        patient.*, 
        users.nama_user AS nama_user_dokter_pemotongan,
        mutu.total_nilai_mutu'
        )
            ->join('srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left') // Relasi dengan tabel srs
            ->join('patient', 'srs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('pemotongan', 'srs.id_pemotongan = pemotongan.id_pemotongan', 'left') // Relasi dengan tabel pemotongan
            ->join('users', 'pemotongan.id_user_dokter_pemotongan = users.id_user', 'left') // Relasi dengan tabel users untuk dokter pemotongan
            ->join('mutu', 'srs.id_srs = mutu.id_srs', 'left') // Relasi dengan tabel mutu berdasarkan id_srs
            ->where('srs.status_srs', 'pembacaan_srs') // Filter berdasarkan status_srs 'pembacaan_srs'
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }


    // Fungsi untuk mengupdate data pembacaan_srs
    public function updatepembacaan_srs($id_pembacaan_srs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pembacaan_srs
        $builder->where('id_pembacaan_srs', $id_pembacaan_srs);  // Menentukan baris yang akan diupdate berdasarkan id_pembacaan_srs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepembacaan_srs($id_pembacaan_srs)
    {
        return $this->delete($id_pembacaan_srs);
    }

}
