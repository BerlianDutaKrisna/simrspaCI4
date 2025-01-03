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
                'username'      => 'vinna',
                'password_user' => password_hash('vinna', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'dr. Vinna Chrisdianti, Sp.PA',
                'status_user'   => 'Dokter',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'ayu',
                'password_user' => password_hash('ayu', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'dr. Ayu Tyasmara Pratiwi, Sp.PA',
                'status_user'   => 'Dokter',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'endar',
                'password_user' => password_hash('endar', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Endar Pratiwi, S.Si',
                'status_user'   => 'Analis',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'arlina',
                'password_user' => password_hash('arlina', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Arlina Kartika, A.Md.AK',
                'status_user'   => 'Analis',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'ilham',
                'password_user' => password_hash('ilham', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Ilham Tyas Ismadi, A.Md.Kes',
                'status_user'   => 'Analis',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'username'      => 'berlian',
                'password_user' => password_hash('berlian', PASSWORD_DEFAULT),  // Enkripsi password
                'nama_user'     => 'Berlian Duta Krisna, S.Tr.Kes',
                'status_user'   => 'Analis',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        ];

        // Menggunakan query builder untuk memasukkan data ke tabel users
        $this->db->table('users')->insertBatch($data);
    }
}
