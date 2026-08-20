<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IcdoSeeder extends Seeder
{
    public function run()
    {
        // Menentukan lokasi file SQL
        $sqlPath = APPPATH . 'Database/Seeds/icdo_data.sql';
        
        // Membaca isi file SQL
        $sql = file_get_contents($sqlPath);
        
        if ($sql === false) {
            die("Gagal membaca file SQL di path: " . $sqlPath);
        }

        // Mengeksekusi raw query
        // Gunakan prepared statement atau query langsung karena ini adalah data seeder
        $this->db->query($sql);
        
        echo "Seeding data ICD-O berhasil dieksekusi dari file SQL!\n";
    }
}