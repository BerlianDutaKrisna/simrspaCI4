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
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status_pencetakan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Dicetak', 'Proses Pencetakan', 'Selesai Pencetakan'],
                'default'    => 'Belum Dicetak',
                'null'       => true,
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
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pencetakan');
    }

    public function down()
    {
        $this->forge->dropTable('pencetakan');
    }
}
