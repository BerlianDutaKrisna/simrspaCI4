<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pembacaan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pembacaan' => [
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
            'status_pembacaan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Dibaca', 'Proses Pembacaan', 'Selesai Pembacaan'],
                'default'    => 'Belum Dibaca',
                'null'       => true,
            ],
            'mulai_pembacaan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pembacaan' => [
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

        $this->forge->addKey('id_pembacaan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pembacaan');
    }

    public function down()
    {
        $this->forge->dropTable('pembacaan');
    }
}
