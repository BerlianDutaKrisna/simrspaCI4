<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pewarnaan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pewarnaan' => [
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
            'status_pewarnaan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Diwarnai', 'Proses Pewarnaan', 'Selesai Pewarnaan'],
                'default'    => 'Belum Diwarnai',
                'null'       => true,
            ],
            'mulai_pewarnaan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pewarnaan' => [
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

        $this->forge->addKey('id_pewarnaan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pewarnaan');
    }

    public function down()
    {
        $this->forge->dropTable('pewarnaan');
    }
}
