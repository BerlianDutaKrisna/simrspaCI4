<?php

namespace App\Models\Hpa\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Pemotongan_hpa extends Model
{
    protected $table      = 'pemotongan_hpa'; // Nama tabel
    protected $primaryKey = 'id_pemotongan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemotongan_hpa',
        'id_user_dokter_pemotongan_hpa',
        'status_pemotongan_hpa',
        'mulai_pemotongan_hpa',
        'selesai_pemotongan_hpa',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data pemotongan_hpa
    public function insertpemotongan_hpa(array $data): bool
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data pemotongan_hpa dengan relasi
    public function getPemotongan_hpa()
    {
        return $this->select(
            '
        pemotongan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemotongan_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa'
        )
            ->join('hpa', 'pemotongan_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pemotongan_hpa.id_user_pemotongan_hpa = users.id_user', 'left') // Relasi dengan tabel users untuk pemotongan_hpa
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left') // Relasi dengan tabel mutu_hpa berdasarkan id_hpa
            ->whereIn('hpa.status_hpa', ['Pemotongan']) // Menambahkan filter whereIn untuk status_hpa
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }


    // Fungsi untuk mengupdate data pemotongan_hpa
    public function updatepemotongan_hpa($id_pemotongan_hpa, $data)
    {
        $builder = $this->db->table($this->table);  // Mengambil table pemotongan_hpa
        $builder->where('id_pemotongan_hpa', $id_pemotongan_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_pemotongan_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepemotongan_hpa($id_pemotongan_hpa)
    {
        return $this->delete($id_pemotongan_hpa);
    }

}
