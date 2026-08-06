<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Panggil UserSeeder dan PatientSeeder
        $this->call('App\Database\Seeds\UserSeeder');

        echo "UserSeeder dan PatientSeeder telah dijalankan.\n";
    }
}
