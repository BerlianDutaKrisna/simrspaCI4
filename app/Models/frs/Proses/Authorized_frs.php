<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Authorized_frs extends Model // Update nama model
{
    protected $table      = 'authorized_frs'; // Nama tabel
    protected $primaryKey = 'id_authorized_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_authorized_frs', // Update nama kolom
        'status_authorized_frs', // Update nama kolom
        'mulai_authorized_frs', // Update nama kolom
        'selesai_authorized_frs', // Update nama kolom
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data authorized_frs
    public function insertauthorized_frs(array $data): bool // Update nama fungsi
    {
        $this->insert($data);
        return $this->db->affectedRows() > 0;
    }

    public function getauthorized_hpa()
{
    return $this->select(
        '
        authorized_hpa.*, 
        hpa.*, 
        patient.*, 
        users.nama_user AS nama_user_authorized_hpa,
        mutu_hpa.id_mutu_hpa,
        mutu_hpa.total_nilai_mutu_hpa,
        penerimaan_hpa.id_penerimaan_hpa,
        pembacaan_hpa.id_pembacaan_hpa,
        pembacaan_hpa.id_pembacaan_hpa, 
        pemverifikasi_hpa.id_pemverifikasi_hpa,
        pencetakan_hpa.id_pencetakan_hpa'
    )
        ->join('hpa', 'authorized_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left')
        ->join('users', 'authorized_hpa.id_user_authorized_hpa = users.id_user', 'left') 
        ->join('mutu_hpa', 'hpa.id_hpa = mutu_hpa.id_hpa', 'left')
        ->join('penerimaan_hpa', 'hpa.id_hpa = penerimaan_hpa.id_hpa', 'left')
        ->join('pembacaan_hpa', 'hpa.id_hpa = pembacaan_hpa.id_hpa', 'left')
        ->join('authorized_hpa', 'hpa.id_hpa = authorized_hpa.id_hpa', 'left')
        ->join('pencetakan_hpa', 'hpa.id_hpa = pencetakan_hpa.id_hpa', 'left')
        ->whereIn('hpa.status_hpa', ['Authorized']) 
        ->orderBy('hpa.kode_hpa', 'ASC')
        ->findAll();
}

    // Fungsi untuk mengupdate data authorized_frs
    public function updateauthorized_frs($id_authorized_frs, $data) // Update nama fungsi dan parameter
    {
        $builder = $this->db->table($this->table);  // Mengambil tabel authorized_frs
        $builder->where('id_authorized_frs', $id_authorized_frs);  // Menentukan baris yang akan diupdate berdasarkan id_authorized_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deleteauthorized_frs($id_authorized_frs)
    {
        return $this->delete($id_authorized_frs);
    }

}
