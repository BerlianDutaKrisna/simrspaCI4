<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $returnType = 'array';
    protected $allowedFields = [
        'username',
        'password_user',
        'nama_user',
        'status_user'];
    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // Menyimpan data users baru
    public function insertUser($data)
    {
        // Pastikan data disimpan dengan benar
        try {
            // Proses penyimpanan data menggunakan insert
            $this->insert($data);

            // Cek apakah ada error saat penyimpanan
            if ($this->db->affectedRows() > 0) {
                return true; // Berhasil menyimpan data
            } else {
                return false; // Gagal menyimpan data
            }
        } catch (\Exception $e) {
            // Menangani error dan mengembalikan pesan error
            return $e->getMessage();
        }
    }
    // Mengecek apakah username sudah ada di database
    public function checkUsernameExists($username)
    {
        return $this->where('username', $username)->countAllResults() > 0; // Cek apakah username sudah ada di database
    }

    public function deleteUsers($id_users)
    {
        try {
            // Menghapus data berdasarkan ID
            return $this->db->table($this->table)->delete(['id_user' => $id_users]);
        } catch (\Throwable $e) {
            // Menangkap error jika terjadi
            log_message('error', 'Error saat menghapus data user: ' . $e->getMessage());
            return false;
        }
    }

    public function getTotalByUserName($nama)
    {
        $user = $this->where('nama_user', $nama)->first();
        if (!$user) {
            return 0; // Jika user tidak ditemukan, langsung kembalikan 0
        }

        $userId = $user['id_user'];
        $db = \Config\Database::connect();

        // Mapping tabel dan foreign key
        $tables = [
            'penerimaan_hpa' => 'id_user_penerimaan_hpa',
            'penerimaan_frs' => 'id_user_penerimaan_frs',
            'penerimaan_srs' => 'id_user_penerimaan_srs',
            'penerimaan_ihc' => 'id_user_penerimaan_ihc',
            'pemotongan_hpa' => 'id_user_pemotongan_hpa',
            'pemprosesan_hpa' => 'id_user_pemprosesan_hpa',
            'penanaman_hpa' => 'id_user_penanaman_hpa',
            'pemotongan_tipis_hpa' => 'id_user_pemotongan_tipis_hpa',
            'pewarnaan_hpa' => 'id_user_pewarnaan_hpa',
            'pembacaan_hpa' => 'id_user_dokter_pembacaan_hpa',
            'pembacaan_frs' => 'id_user_dokter_pembacaan_frs',
            'pembacaan_srs' => 'id_user_dokter_pembacaan_srs',
            'pembacaan_ihc' => 'id_user_dokter_pembacaan_ihc',
            'pemverifikasi_hpa' => 'id_user_pemverifikasi_hpa',
            'pemverifikasi_frs' => 'id_user_pemverifikasi_frs',
            'pemverifikasi_srs' => 'id_user_pemverifikasi_srs',
            'pemverifikasi_ihc' => 'id_user_pemverifikasi_ihc',
            'authorized_hpa' => 'id_user_dokter_authorized_hpa',
            'authorized_frs' => 'id_user_dokter_authorized_frs',
            'authorized_srs' => 'id_user_dokter_authorized_srs',
            'authorized_ihc' => 'id_user_dokter_authorized_ihc'
        ];

        $total = 0;

        foreach ($tables as $table => $foreignKey) {
            try {
                $builder = $db->table($table);
                $builder->where($foreignKey, $userId);
                $total += $builder->countAllResults(false); // false untuk mencegah reset query builder
            } catch (\Exception $e) {
                // Optional: log jika ada error pada salah satu tabel
                log_message('error', "Error counting table $table: " . $e->getMessage());
            }
        }

        return $total;
    }
}
