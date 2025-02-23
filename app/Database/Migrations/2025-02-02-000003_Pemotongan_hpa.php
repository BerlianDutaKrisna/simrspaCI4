<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemotongan_hpa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemotongan_hpa' => [
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
            'id_user_pemotongan_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemotongan_hpa' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_pemotongan_hpa' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemotongan_hpa' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_pemotongan_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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

        $this->forge->addKey('id_pemotongan_hpa', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user_pemotongan_hpa', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_pemotongan_hpa', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)

        $this->forge->createTable('pemotongan_hpa');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemotongan_hpa', 'id_hpa');
        $this->forge->dropForeignKey('pemotongan_hpa', 'id_user_pemotongan_hpa');
        $this->forge->dropForeignKey('pemotongan_hpa', 'id_user_dokter_pemotongan_hpa');
        $this->forge->dropTable('pemotongan_hpa');
    }
}
