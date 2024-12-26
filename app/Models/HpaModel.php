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
}
