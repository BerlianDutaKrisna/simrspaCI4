<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemverifikasi_frs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemverifikasi_frs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'id_user_pemverifikasi_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemverifikasi_frs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_pemverifikasi_frs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemverifikasi_frs' => [
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

        $this->forge->addKey('id_pemverifikasi_frs', true); // Primary Key
        $this->forge->addForeignKey('id_frs', 'frs', 'id_frs', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel frs
        $this->forge->addForeignKey('id_user_pemverifikasi_frs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users

        $this->forge->createTable('pemverifikasi_frs');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemverifikasi_frs', 'id_frs');
        $this->forge->dropForeignKey('pemverifikasi_frs', 'id_user_pemverifikasi_frs');
        $this->forge->dropTable('pemverifikasi_frs');
    }
}
