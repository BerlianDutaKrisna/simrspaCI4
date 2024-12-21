<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Test extends Controller
{
    public function index()
    {
        // Mengakses database untuk memastikan koneksi berhasil
        $db = \Config\Database::connect();  // Mengambil objek koneksi database

        try {
            // Menjalankan query sederhana untuk menguji koneksi
            $query = $db->query("SELECT 1");
            $result = $query->getResultArray();

            // Jika query berhasil, tampilkan pesan sukses
            return "Database connected successfully!";
        } catch (DatabaseException $e) {
            // Menangani error jika koneksi gagal
            return "Database connection failed: " . $e->getMessage();
        }
    }
}
