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
}
