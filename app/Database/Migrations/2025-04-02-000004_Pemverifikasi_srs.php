<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemverifikasi_srs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemverifikasi_srs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_srs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'id_user_pemverifikasi_srs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemverifikasi_srs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_pemverifikasi_srs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemverifikasi_srs' => [
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

        $this->forge->addKey('id_pemverifikasi_srs', true); // Primary Key
        $this->forge->addForeignKey('id_srs', 'srs', 'id_srs', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel srs
        $this->forge->addForeignKey('id_user_pemverifikasi_srs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users

        $this->forge->createTable('pemverifikasi_srs');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemverifikasi_srs', 'id_srs');
        $this->forge->dropForeignKey('pemverifikasi_srs', 'id_user_pemverifikasi_srs');
        $this->forge->dropTable('pemverifikasi_srs');
    }
}
