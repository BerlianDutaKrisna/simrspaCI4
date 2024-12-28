<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pencetakan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pencetakan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_user_pencetakan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pencetakan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_pencetakan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pencetakan' => [
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

        $this->forge->addKey('id_pencetakan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user_pencetakan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pencetakan');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pencetakan', 'id_hpa');
        $this->forge->dropForeignKey('pencetakan', 'id_user_pencetakan');
        $this->forge->dropTable('pencetakan');
    }
}
