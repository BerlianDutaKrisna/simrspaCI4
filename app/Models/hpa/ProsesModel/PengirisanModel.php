<?php

namespace App\Models\ProsesModel; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class PengirisanModel extends Model
{
    protected $table      = 'pengirisan'; // Nama tabel
    protected $primaryKey = 'id_pengirisan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_hpa',
        'id_user_pengirisan',
        'status_pengirisan',
        'mulai_pengirisan',
        'selesai_pengirisan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Pengirisan
    public function insertPengirisan(array $data): bool
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    // Mengambil data Pengirisan dengan relasi
    public function getPengirisanWithRelations()
    {
        return $this->select(
            '
        pengirisan.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_pengirisan,
        mutu.total_nilai_mutu'
        )
            ->join('hpa', 'pengirisan.id_hpa = hpa.id_hpa', 'left') // Relasi dengan tabel hpa
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'pengirisan.id_user_pengirisan = users.id_user', 'left') // Relasi dengan tabel users untuk pengirisan
            ->join('mutu', 'hpa.id_hpa = mutu.id_hpa', 'left') // Relasi dengan tabel mutu berdasarkan id_hpa
            ->where('hpa.status_hpa', 'Pengirisan') // Filter berdasarkan status_hpa 'Pengirisan'
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }


    // Fungsi untuk mengupdate data pengirisan
    public function updatePengirisan($id_pengirisan, $data)
    {
        $builder = $this->db->table($this->table);  // Mengambil table pengirisan
        $builder->where('id_pengirisan', $id_pengirisan);  // Menentukan baris yang akan diupdate berdasarkan id_pengirisan
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePengirisan($id_pengirisan)
    {
        return $this->delete($id_pengirisan);
    }

    public function countPengirisan()
    {
        return $this->where('status_pengirisan !=', 'Selesai Pengirisan')->countAllResults();
    }
}
