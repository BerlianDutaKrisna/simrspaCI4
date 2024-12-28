<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penulisan extends Migration
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
            'id_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user_penulisan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('penulisan');
    }

    public function down()
    {
        $this->forge->dropForeignKey('penulisan', 'id_hpa');
        $this->forge->dropForeignKey('penulisan', 'id_user_penulisan');
        $this->forge->dropTable('penulisan');
    }
}
