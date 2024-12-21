<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $returnType = 'array';
    protected $allowedFields = [
        'username',
        'password_user',
        'nama_user',
        'status_user'];
    
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
        return $this->where('username', $username)->first(); // Cek apakah username sudah ada di database
    }
}
