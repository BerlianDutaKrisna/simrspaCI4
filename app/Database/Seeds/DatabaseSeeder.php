<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('App\Database\Seeds\UserSeeder');

        $sql_file = FCPATH . 'assets/sql/patient.sql';

        if (file_exists($sql_file)) {
            // Baca file SQL
            $sql = file_get_contents($sql_file);

            // Eksekusi SQL
            $this->db->query($sql);
            echo "Data berhasil dimasukkan ke tabel `patient`!";
        } else {
            echo "File SQL tidak ditemukan: " . $sql_file;
        }
    }
}
