<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pewarnaan_hpa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pewarnaan_hpa' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_pewarnaan_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pewarnaan_hpa' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_pewarnaan_hpa' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pewarnaan_hpa' => [
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

        $this->forge->addKey('id_pewarnaan_hpa', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user_pewarnaan_hpa', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pewarnaan_hpa');
    }

    public function down()
    {
        $this->forge->dropTable('pewarnaan_hpa');
    }
}
