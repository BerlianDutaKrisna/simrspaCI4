<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Patient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pasien' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'norm_pasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => false,
                'unique'     => true,
            ],
            'nama_pasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'alamat_pasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diisi',
                'null' => true,
            ],
            'tanggal_lahir_pasien' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'jenis_kelamin_pasien' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P', 'Belum Dipilih'],
                'default'    => 'Belum Dipilih',
                'null'       => true,
            ],
            'status_pasien' => [
                'type'       => 'ENUM',
                'constraint' => ['PBI', 'Non PBI', 'Umum', 'Belum Dipilih'],
                'default'    => 'Belum Dipilih',
                'null'       => true,
            ],'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_pasien', true); // Primary key
        $this->forge->createTable('patient');  // Membuat tabel pasien
    }

    public function down()
    {
        // Menghapus tabel jika rollback
        $this->forge->dropTable('patient');
    }
}
