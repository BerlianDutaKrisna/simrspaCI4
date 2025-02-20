<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penulisan_fnab extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penulisan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_fnab' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_penulisan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penulisan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_penulisan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penulisan' => [
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

        $this->forge->addKey('id_penulisan', true); // Primary Key
        $this->forge->addForeignKey('id_fnab', 'fnab', 'id_fnab', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel fnab
        $this->forge->addForeignKey('id_user_penulisan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('penulisan_fnab');
    }

    public function down()
    {
        $this->forge->dropForeignKey('penulisan_fnab', 'id_fnab');
        $this->forge->dropForeignKey('penulisan_fnab', 'id_user_penulisan');
        $this->forge->dropTable('penulisan_fnab');
    }
}
