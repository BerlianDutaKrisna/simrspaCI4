<?php

namespace App\Models;

use CodeIgniter\Model;

class HpaModel extends Model
{
    // Nama tabel yang digunakan oleh model
    protected $table = 'hpa';

    // Primary key dari tabel
    protected $primaryKey = 'id_hpa';

    // Kolom yang dapat diisi (mass assignable)
    protected $allowedFields = [
        'kode_hpa',
        'id_pasien',
        'unit_asal',
        'dokter_pengirim',
        'tanggal_permintaan',
        'tanggal_hasil',
        'lokasi_spesimen',
        'tindakan_spesimen',
        'diagnosa_klinik',
        'status_hpa',
        'makroskopis_hpa',
        'foto_makroskopis_hpa',
        'mikroskopis_hpa',
        'foto_mikroskopis_hpa',
        'jumlah_slide',
        'hasil_hpa',
        'id_penerimaan',
        'id_pengirisan',
        'id_pemotongan',
        'id_pemprosesan',
        'id_penanaman',
        'id_pemotongan_tipis',
        'id_pewarnaan',
        'id_pembacaan',
        'id_penulisan',
        'id_pemverifikasi',
        'id_pencetakan',
        'id_mutu',
        'created_at',
        'updated_at',
    ];

    // Menentukan tipe data untuk kolom 'created_at' dan 'updated_at'
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data HPA dengan pengecekan kode_hpa
    public function insertHpa(array $data): bool
    {
        // Cek jika kode_hpa sudah ada
        if ($this->where('kode_hpa', $data['kode_hpa'])->first()) {
            return false;  // Jika sudah ada, tidak melakukan insert
        }

        // Melakukan insert data
        return $this->insertHpa($data) > 0;
    }

    public function getAllData()
    {
        return $this->db->table($this->table)
            ->select('
            hpa.*, 
            penerimaan.*, 
            pengirisan.*, 
            user_penerimaan.nama_user AS nama_user_penerimaan, 
            user_pengirisan.nama_user AS nama_user_pengirisan,
            patient.nama_pasien,
            patient.norm_pasien
        ')
            ->join('penerimaan', 'hpa.id_penerimaan = penerimaan.id_penerimaan', 'left')
            ->join('pengirisan', 'hpa.id_pengirisan = pengirisan.id_pengirisan', 'left')
            ->join('users AS user_penerimaan', 'penerimaan.id_user_penerimaan = user_penerimaan.id_user', 'left')
            ->join('users AS user_pengirisan', 'pengirisan.id_user_pengirisan = user_pengirisan.id_user', 'left')
            ->join('patient AS patient', 'hpa.id_pasien = patient.id_pasien', 'left')  // Menambahkan join dengan tabel patient
            ->get()
            ->getResultArray();
    }

    public function getHpaWithRelations()
    {
        return $this->db->table($this->table)
            ->select('
            hpa.*, 
            patient.nama_pasien, 
            patient.norm_pasien, 
            penerimaan.*, 
            pengirisan.*, 
            user_penerimaan.nama_user AS nama_user_penerimaan, 
            user_pengirisan.nama_user AS nama_user_pengirisan
        ')
            ->join('patient', 'patient.id_pasien = hpa.id_pasien', 'left')  // Mengganti 'pasien' dengan 'patient'
            ->join('penerimaan', 'hpa.id_penerimaan = penerimaan.id_penerimaan', 'left')
            ->join('pengirisan', 'hpa.id_pengirisan = pengirisan.id_pengirisan', 'left')
            ->join('users AS user_penerimaan', 'penerimaan.id_user_penerimaan = user_penerimaan.id_user', 'left')
            ->join('users AS user_pengirisan', 'pengirisan.id_user_pengirisan = user_pengirisan.id_user', 'left')
            ->get()
            ->getResultArray();
    }






    public function updateHpa($id_hpa, $data)
    {
        $builder = $this->db->table('hpa');  // Mengambil table 'hpa'
        $builder->where('id_hpa', $id_hpa); // Menambahkan kondisi WHERE
        $builder->update($data);             // Melakukan update data
        return $this->db->affectedRows();    // Mengembalikan jumlah baris yang terpengaruh
    }
}
