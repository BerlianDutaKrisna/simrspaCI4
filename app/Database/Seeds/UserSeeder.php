<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Menyiapkan data pengguna untuk dimasukkan ke tabel users
        $data = [
            [
                'username'      => 'admin',
                'password_user' => password_hash('admin', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Administrator',
                'status_user'   => 'Admin',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'analis1',
                'password_user' => password_hash('analis1', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Analis 1',
                'status_user'   => 'Analis',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'analis2',
                'password_user' => password_hash('analis2', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Analis 2',
                'status_user'   => 'Analis',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'dokter1',
                'password_user' => password_hash('dokter1', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Dokter',
                'status_user'   => 'Dokter',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'dokter2',
                'password_user' => password_hash('dokter2', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Dokter',
                'status_user'   => 'Dokter',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        ];

        // Menggunakan query builder untuk memasukkan data ke tabel users
        $this->db->table('users')->insertBatch($data);
    }
}
