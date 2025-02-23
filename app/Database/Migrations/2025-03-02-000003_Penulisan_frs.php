<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penulisan_frs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penulisan_frs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_penulisan_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penulisan_frs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_penulisan_frs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penulisan_frs' => [
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

        $this->forge->addKey('id_penulisan_frs', true); // Primary Key
        $this->forge->addForeignKey('id_frs', 'frs', 'id_frs', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel frs
        $this->forge->addForeignKey('id_user_penulisan_frs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('penulisan_frs');
    }

    public function down()
    {
        $this->forge->dropForeignKey('penulisan_frs', 'id_frs');
        $this->forge->dropForeignKey('penulisan_frs', 'id_user_penulisan_frs');
        $this->forge->dropTable('penulisan_frs');
    }
}
