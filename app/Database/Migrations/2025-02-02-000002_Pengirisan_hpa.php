<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pengirisan_hpa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pengirisan_hpa' => [
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
            'id_user_pengirisan_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pengirisan_hpa' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_pengirisan_hpa' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pengirisan_hpa' => [
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

        $this->forge->addKey('id_pengirisan_hpa', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel hpa
        $this->forge->addForeignKey('id_user_pengirisan_hpa', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel users
        $this->forge->createTable('pengirisan_hpa');
    }

    public function down()
    {
        $this->forge->dropTable('pengirisan_hpa');
    }
}
