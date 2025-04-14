<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'norm_pasien' => '100001',
                'nama_pasien' => 'Budi Santoso',
                'alamat_pasien' => 'Jl. Merdeka No. 1, Jakarta',
                'tanggal_lahir_pasien' => '1985-05-10',
                'jenis_kelamin_pasien' => 'L',
                'status_pasien' => 'Umum',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'norm_pasien' => '100002',
                'nama_pasien' => 'Siti Aisyah',
                'alamat_pasien' => 'Jl. Diponegoro No. 5, Bandung',
                'tanggal_lahir_pasien' => '1990-08-15',
                'jenis_kelamin_pasien' => 'P',
                'status_pasien' => 'PBI',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'norm_pasien' => '100003',
                'nama_pasien' => 'Ahmad Fauzi',
                'alamat_pasien' => 'Jl. Sudirman No. 21, Surabaya',
                'tanggal_lahir_pasien' => '1978-11-30',
                'jenis_kelamin_pasien' => 'L',
                'status_pasien' => 'Non PBI',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'norm_pasien' => '100004',
                'nama_pasien' => 'Dewi Lestari',
                'alamat_pasien' => 'Jl. Thamrin No. 10, Yogyakarta',
                'tanggal_lahir_pasien' => '2000-03-22',
                'jenis_kelamin_pasien' => 'P',
                'status_pasien' => 'Umum',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'norm_pasien' => '100005',
                'nama_pasien' => 'Rizky Hidayat',
                'alamat_pasien' => 'Jl. Gajah Mada No. 7, Medan',
                'tanggal_lahir_pasien' => '1995-09-18',
                'jenis_kelamin_pasien' => 'L',
                'status_pasien' => 'PBI',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data ke tabel patient
        $this->db->table('patient')->insertBatch($data);
    }
}
