<?php

namespace App\Models\Hpa\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penerimaan_hpa extends Model
{
    protected $table      = 'penerimaan_hpa'; // Nama tabel
    protected $primaryKey = 'id_penerimaan_hpa'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_penerimaan_hpa',
        'status_penerimaan_hpa',
        'mulai_penerimaan_hpa',
        'selesai_penerimaan_hpa',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPenerimaan_hpa()
    {
        return $this->select(
            '
        penerimaan_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_penerimaan_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa'
        )
            ->join('hpa', 'penerimaan_hpa.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penerimaan_hpa.id_user_penerimaan_hpa = users.id_user', 'left') // Relasi dengan tabel users untuk penerimaan_hpa
            ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left') // Relasi dengan tabel mutu_hpa berdasarkan id_hpa
            ->whereIn('hpa.status_hpa', ['penerimaan_hpa', 'Penerimaan']) // Menambahkan filter whereIn untuk status_hpa
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penerimaan_hpa
    public function updatepenerimaan_hpa($id_penerimaan_hpa, $data)
    {
        $builder = $this->db->table($this->table);  // Mengambil table penerimaan_hpa
        $builder->where('id_penerimaan_hpa', $id_penerimaan_hpa);  // Menentukan baris yang akan diupdate berdasarkan id_penerimaan_hpa
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenerimaan_hpa($id_penerimaan_hpa)
    {
        return $this->delete($id_penerimaan_hpa);
    }

}
