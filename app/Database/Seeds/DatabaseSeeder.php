<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Menjalankan UserSeeder
        $this->call('App\Database\Seeds\UserSeeder');
        
        // Menjalankan PatientSeeder
        $this->call('App\Database\Seeds\PatientSeeder');
    }
}
