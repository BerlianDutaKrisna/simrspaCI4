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
        'id_user_pembacaan_frs', // Update nama kolom
        'status_pembacaan_frs', // Update nama kolom
        'mulai_pembacaan_frs', // Update nama kolom
        'selesai_pembacaan_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pembacaan_frs
    public function insertpembacaan_frs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pembacaan_frs dengan relasi
    public function getpembacaan_frsWithRelations()
    {
        return $this->select(
            'pembacaan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_dokter_pemotongan,
        mutu.total_nilai_mutu'
        )
            ->join('frs', 'pembacaan_frs.id_frs = frs.id_frs', 'left') // Relasi dengan tabel frs
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('pemotongan', 'frs.id_pemotongan = pemotongan.id_pemotongan', 'left') // Relasi dengan tabel pemotongan
            ->join('users', 'pemotongan.id_user_dokter_pemotongan = users.id_user', 'left') // Relasi dengan tabel users untuk dokter pemotongan
            ->join('mutu', 'frs.id_frs = mutu.id_frs', 'left') // Relasi dengan tabel mutu berdasarkan id_frs
            ->where('frs.status_frs', 'pembacaan_frs') // Filter berdasarkan status_frs 'pembacaan_frs'
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }


    // Fungsi untuk mengupdate data pembacaan_frs
    public function updatepembacaan_frs($id_pembacaan_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel pembacaan_frs
        $builder->where('id_pembacaan_frs', $id_pembacaan_frs);  // Menentukan baris yang akan diupdate berdasarkan id_pembacaan_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepembacaan_frs($id_pembacaan_frs)
    {
        return $this->delete($id_pembacaan_frs);
    }

}
