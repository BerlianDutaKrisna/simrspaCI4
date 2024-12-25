<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pengirisan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pengirisan' => [
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
            'status_pengirisan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Diiris', 'Proses Pengirisan', 'Sudah Diiris'],
                'default'    => 'Belum Diiris',
                'null'       => true,
            ],
            'mulai_pengirisan' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'selesai_pengirisan' => [
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

        $this->forge->addKey('id_pengirisan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel hpa
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel users
        $this->forge->createTable('pengirisan');
    }

    public function down()
    {
        $this->forge->dropTable('pengirisan');
    }
}