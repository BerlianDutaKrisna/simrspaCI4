<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AgilSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'      => 'agil',
            'password_user' => password_hash('agil', PASSWORD_DEFAULT), // Hash password
            'nama_user'     => 'dr. Agil Kusumawati',
            'status_user'   => 'Dokter',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $this->db->table('users')->insert($data);
    }
}
