<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PemotonganModel extends Model
{
    protected $table      = 'pemotongan'; // Nama tabel
    protected $primaryKey = 'id_pemotongan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pemotongan',
        'id_user_dokter_pemotongan',
        'status_pemotongan',
        'mulai_pemotongan',
        'selesai_pemotongan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pemotongan
    public function insertPemotongan(array $data): bool
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Pemotongan dengan relasi
    public function getPemotonganWithRelations()
{
    return $this->select(
        '
        pemotongan.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pemotongan,
        mutu.total_nilai_mutu'
    )
        ->join('hpa', 'pemotongan.id_hpa = hpa.id_hpa', 'left')
        ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') 
        ->join('users', 'pemotongan.id_user_pemotongan = users.id_user', 'left') 
        ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left')
        ->where('hpa.status_hpa', 'Pemotongan')
        ->orderBy('hpa.kode_hpa', 'ASC') 
        ->findAll();
}


    // Fungsi untuk mengupdate data pemotongan
    public function updatePemotongan($id_pemotongan, $data)
    {
        $builder = $this->db->table($this->table);  // Mengambil table pemotongan
        $builder->where('id_pemotongan', $id_pemotongan);  // Menentukan baris yang akan diupdate berdasarkan id_pemotongan
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePemotongan($id_pemotongan)
    {
        return $this->delete($id_pemotongan);
    }

    public function countPemotongan()
    {
        return $this->where('status_pemotongan !=', 'Selesai Pemotongan')->countAllResults();
    }
}
