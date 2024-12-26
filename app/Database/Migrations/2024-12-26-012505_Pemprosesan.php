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
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemprosesan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Diproses', 'Proses Pemprosesan', 'Selesai Pemprosesan'],
                'default'    => 'Belum Diproses',
                'null'       => true,
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
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pemprosesan');
    }

    public function down()
    {
        $this->forge->dropTable('pemprosesan');
    }
}
