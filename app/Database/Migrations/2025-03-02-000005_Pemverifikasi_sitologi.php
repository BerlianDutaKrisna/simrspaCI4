<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemverifikasi_sitologi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemverifikasi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_sitologi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_user_pemverifikasi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemverifikasi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_pemverifikasi' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemverifikasi' => [
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

        $this->forge->addKey('id_pemverifikasi', true); // Primary Key
        $this->forge->addForeignKey('id_sitologi', 'sitologi', 'id_sitologi', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel sitologi
        $this->forge->addForeignKey('id_user_pemverifikasi', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users

        $this->forge->createTable('pemverifikasi_sitologi');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemverifikasi_sitologi', 'id_sitologi');
        $this->forge->dropForeignKey('pemverifikasi_sitologi', 'id_user_pemverifikasi');
        $this->forge->dropTable('pemverifikasi_sitologi');
    }
}
