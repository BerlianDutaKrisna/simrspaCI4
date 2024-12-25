<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penerimaan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penerimaan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status_penerimaan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Diperiksa', 'Proses Pemeriksaan', 'Sudah Diperiksa'],
                'default'    => 'Belum Diperiksa',
                'null'       => true,
            ],
            'mulai_penerimaan' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'selesai_penerimaan' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_penerimaan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel hpa
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel users
        $this->forge->createTable('penerimaan');
    }

    public function down()
    {
        $this->forge->dropTable('penerimaan');
    }
}
