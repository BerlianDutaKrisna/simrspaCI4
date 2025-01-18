<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemprosesan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemprosesan' => [
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
            'id_user_pemprosesan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemprosesan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_pemprosesan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemprosesan' => [
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

        $this->forge->addKey('id_pemprosesan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user_pemprosesan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pemprosesan');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemprosesan', 'id_hpa');
        $this->forge->dropForeignKey('pemprosesan', 'id_user_pemprosesan');
        $this->forge->dropTable('pemprosesan');
    }
}
