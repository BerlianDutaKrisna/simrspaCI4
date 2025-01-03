<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PatientSeeder extends Seeder
{
    public function run()
    {
        // Menyiapkan data pasien palsu
        $data = [
            [
                'norm_pasien'          => '000001', // Nomor RM pertama
                'nama_pasien'          => 'Ahmad Zaki', 
                'alamat_pasien'        => 'Jl. Melati No. 12, Jakarta',
                'tanggal_lahir_pasien' => '1985-02-15',
                'jenis_kelamin_pasien' => 'Laki-laki',
                'status_pasien'        => 'PBI',
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ],
            [
                'norm_pasien'          => '000002', // Nomor RM kedua
                'nama_pasien'          => 'Budi Santoso',
                'alamat_pasien'        => 'Jl. Raya No. 8, Bandung',
                'tanggal_lahir_pasien' => '1990-07-10',
                'jenis_kelamin_pasien' => 'Laki-laki',
                'status_pasien'        => 'Non PBI',
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ],
            [
                'norm_pasien'          => '000003', // Nomor RM ketiga
                'nama_pasien'          => 'Siti Nuraini',
                'alamat_pasien'        => 'Jl. Mawar No. 3, Surabaya',
                'tanggal_lahir_pasien' => '1995-11-25',
                'jenis_kelamin_pasien' => 'Perempuan',
                'status_pasien'        => 'Umum',
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ],
            [
                'norm_pasien'          => '000004', // Nomor RM keempat
                'nama_pasien'          => 'Rina Amelia',
                'alamat_pasien'        => 'Jl. Kenanga No. 5, Yogyakarta',
                'tanggal_lahir_pasien' => '2000-04-30',
                'jenis_kelamin_pasien' => 'Perempuan',
                'status_pasien'        => 'PBI',
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ],
            [
                'norm_pasien'          => '000005', // Nomor RM kelima
                'nama_pasien'          => 'John Doe',
                'alamat_pasien'        => 'Jl. Semangka No. 7, Bali',
                'tanggal_lahir_pasien' => '1988-06-20',
                'jenis_kelamin_pasien' => 'Laki-laki',
                'status_pasien'        => 'Non PBI',
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]
        ];

        // Menambahkan data ke tabel patient
        $this->db->table('patient')->insertBatch($data);
    }
}
