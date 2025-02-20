<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemverifikasi_fnab extends Migration
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
            'id_fnab' => [
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
        $this->forge->addForeignKey('id_fnab', 'fnab', 'id_fnab', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel fnab
        $this->forge->addForeignKey('id_user_pemverifikasi', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users

        $this->forge->createTable('pemverifikasi_fnab');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemverifikasi_fnab', 'id_fnab');
        $this->forge->dropForeignKey('pemverifikasi_fnab', 'id_user_pemverifikasi');
        $this->forge->dropTable('pemverifikasi_fnab');
    }
}
