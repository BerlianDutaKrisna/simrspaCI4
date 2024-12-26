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

    public function getHpaWithRelations()
    {
        return $this->select('
        hpa.*,
        patient.nama_pasien,
        patient.norm_pasien,
        users_penerimaan.nama_user AS nama_user_penerimaan,
        penerimaan.status_penerimaan,
        users_pengirisan.nama_user AS nama_user_pengirisan,
        users_pemotongan.nama_user AS nama_user_pemotongan,
        users_pemprosesan.nama_user AS nama_user_pemprosesan,
        users_penanaman.nama_user AS nama_user_penanaman,
        users_pemotongan_tipis.nama_user AS nama_user_pemotongan_tipis,
        users_pewarnaan.nama_user AS nama_user_pewarnaan,
        users_pembacaan.nama_user AS nama_user_pembacaan,
        users_penulisan.nama_user AS nama_user_penulisan,
        users_pemverifikasi.nama_user AS nama_user_pemverifikasi,
        users_pencetakan.nama_user AS nama_user_pencetakan,
        penerimaan.mulai_penerimaan,
        penerimaan.selesai_penerimaan
    ')
            ->join('patient', 'hpa.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('penerimaan', 'hpa.id_penerimaan = penerimaan.id_penerimaan', 'left') // Relasi dengan tabel penerimaan
            ->join('users AS users_penerimaan', 'penerimaan.id_user = users_penerimaan.id_user', 'left') // Relasi dengan tabel users untuk penerimaan
            ->join('pengirisan', 'hpa.id_pengirisan = pengirisan.id_pengirisan', 'left') // Relasi dengan tabel pengirisan
            ->join('users AS users_pengirisan', 'pengirisan.id_user = users_pengirisan.id_user', 'left') // Relasi dengan tabel users untuk pengirisan
            ->join('pemotongan', 'hpa.id_pemotongan = pemotongan.id_pemotongan', 'left') // Relasi dengan tabel pemotongan
            ->join('users AS users_pemotongan', 'pemotongan.id_user = users_pemotongan.id_user', 'left') // Relasi dengan tabel users untuk pemotongan
            ->join('pemprosesan', 'hpa.id_pemprosesan = pemprosesan.id_pemprosesan', 'left') // Relasi dengan tabel pemprosesan
            ->join('users AS users_pemprosesan', 'pemprosesan.id_user = users_pemprosesan.id_user', 'left') // Relasi dengan tabel users untuk pemprosesan
            ->join('penanaman', 'hpa.id_penanaman = penanaman.id_penanaman', 'left') // Relasi dengan tabel penanaman
            ->join('users AS users_penanaman', 'penanaman.id_user = users_penanaman.id_user', 'left') // Relasi dengan tabel users untuk penanaman
            ->join('pemotongan_tipis', 'hpa.id_pemotongan_tipis = pemotongan_tipis.id_pemotongan_tipis', 'left') // Relasi dengan tabel pemotongan_tipis
            ->join('users AS users_pemotongan_tipis', 'pemotongan_tipis.id_user = users_pemotongan_tipis.id_user', 'left') // Relasi dengan tabel users untuk pemotongan_tipis
            ->join('pewarnaan', 'hpa.id_pewarnaan = pewarnaan.id_pewarnaan', 'left') // Relasi dengan tabel pewarnaan
            ->join('users AS users_pewarnaan', 'pewarnaan.id_user = users_pewarnaan.id_user', 'left') // Relasi dengan tabel users untuk pewarnaan
            ->join('pembacaan', 'hpa.id_pembacaan = pembacaan.id_pembacaan', 'left') // Relasi dengan tabel pembacaan
            ->join('users AS users_pembacaan', 'pembacaan.id_user = users_pembacaan.id_user', 'left') // Relasi dengan tabel users untuk pembacaan
            ->join('penulisan', 'hpa.id_penulisan = penulisan.id_penulisan', 'left') // Relasi dengan tabel penulisan
            ->join('users AS users_penulisan', 'penulisan.id_user = users_penulisan.id_user', 'left') // Relasi dengan tabel users untuk penulisan
            ->join('pemverifikasi', 'hpa.id_pemverifikasi = pemverifikasi.id_pemverifikasi', 'left') // Relasi dengan tabel pemverifikasi
            ->join('users AS users_pemverifikasi', 'pemverifikasi.id_user = users_pemverifikasi.id_user', 'left') // Relasi dengan tabel users untuk pemverifikasi
            ->join('pencetakan', 'hpa.id_pencetakan = pencetakan.id_pencetakan', 'left') // Relasi dengan tabel pencetakan
            ->join('users AS users_pencetakan', 'pencetakan.id_user = users_pencetakan.id_user', 'left') // Relasi dengan tabel users untuk pencetakan
            ->join('mutu', 'hpa.id_mutu = mutu.id_mutu', 'left') // Relasi dengan tabel mutu
            ->findAll();
    }

}
