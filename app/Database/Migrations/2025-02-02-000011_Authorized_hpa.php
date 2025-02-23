<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Authorized_hpa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_authorized_hpa' => [
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
            'id_user_authorized_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_authorized_hpa' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_authorized_hpa' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_authorized_hpa' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_authorized_hpa' => [
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

        $this->forge->addKey('id_authorized_hpa', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user_authorized_hpa', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_authorized_hpa', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)

        $this->forge->createTable('authorized_hpa');
    }

    public function down()
    {
        $this->forge->dropForeignKey('authorized_hpa', 'id_hpa');
        $this->forge->dropForeignKey('authorized_hpa', 'id_user_authorized_hpa');
        $this->forge->dropForeignKey('authorized_hpa', 'id_user_dokter_authorized_hpa');
        $this->forge->dropTable('authorized_hpa');
    }
}
