<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penulisan_ihc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penulisan_ihc' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_ihc' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_penulisan_ihc' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penulisan_ihc' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_penulisan_ihc' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penulisan_ihc' => [
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

        $this->forge->addKey('id_penulisan_ihc', true); // Primary Key
        $this->forge->addForeignKey('id_ihc', 'ihc', 'id_ihc', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel ihc
        $this->forge->addForeignKey('id_user_penulisan_ihc', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('penulisan_ihc');
    }

    public function down()
    {
        $this->forge->dropTable('penulisan_ihc');
    }
}
