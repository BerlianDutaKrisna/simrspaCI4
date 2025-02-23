<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemverifikasi_hpa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemverifikasi_hpa' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_pemverifikasi_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemverifikasi_hpa' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_pemverifikasi_hpa' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemverifikasi_hpa' => [
                'type' => 'DATETIME',
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

        $this->forge->addKey('id_pemverifikasi_hpa', true);
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user_pemverifikasi_hpa', 'users', 'id_user', 'CASCADE', 'CASCADE'); 
        $this->forge->createTable('pemverifikasi_hpa');
    }

    public function down()
    {
        $this->forge->dropTable('pemverifikasi_hpa');
    }
}
