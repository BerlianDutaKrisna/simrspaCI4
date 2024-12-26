<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penanaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penanaman' => [
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
            'status_penanaman' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Ditanam', 'Proses Penanaman', 'Selesai Penanaman'],
                'default'    => 'Belum Ditanam',
                'null'       => true,
            ],
            'mulai_penanaman' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penanaman' => [
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

        $this->forge->addKey('id_penanaman', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('penanaman');
    }

    public function down()
    {
        $this->forge->dropTable('penanaman');
    }
}
