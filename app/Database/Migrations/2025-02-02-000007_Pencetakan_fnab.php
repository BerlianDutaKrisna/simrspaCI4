<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pencetakan_fnab extends Migration
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
            'id_fnab' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
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
        $this->forge->addForeignKey('id_fnab', 'fnab', 'id_fnab', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel fnab
        $this->forge->addForeignKey('id_user_pencetakan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pencetakan_fnab');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pencetakan_fnab', 'id_fnab');
        $this->forge->dropForeignKey('pencetakan_fnab', 'id_user_pencetakan');
        $this->forge->dropTable('pencetakan_fnab');
    }
}
