<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Authorized_srs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_authorized_srs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_srs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'id_user_authorized_srs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_authorized_srs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_authorized_srs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_authorized_srs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_authorized_srs' => [
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

        $this->forge->addKey('id_authorized_srs', true); // Primary Key
        $this->forge->addForeignKey('id_srs', 'srs', 'id_srs', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel srs
        $this->forge->addForeignKey('id_user_authorized_srs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_authorized_srs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)

        $this->forge->createTable('authorized_srs');
    }

    public function down()
    {
        $this->forge->dropForeignKey('authorized_srs', 'id_srs');
        $this->forge->dropForeignKey('authorized_srs', 'id_user_authorized_srs');
        $this->forge->dropForeignKey('authorized_srs', 'id_user_dokter_authorized_srs');
        $this->forge->dropTable('authorized_srs');
    }
}
