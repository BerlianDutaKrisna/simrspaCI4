<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Authorized_ihc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_authorized_ihc' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_ihc' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'id_user_authorized_ihc' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_authorized_ihc' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_authorized_ihc' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_authorized_ihc' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_authorized_ihc' => [
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

        $this->forge->addKey('id_authorized_ihc', true); // Primary Key
        $this->forge->addForeignKey('id_ihc', 'ihc', 'id_ihc', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel ihc
        $this->forge->addForeignKey('id_user_authorized_ihc', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_authorized_ihc', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)
        $this->forge->createTable('authorized_ihc');
    }

    public function down()
    {
        $this->forge->dropForeignKey('authorized_ihc', 'id_ihc');
        $this->forge->dropForeignKey('authorized_ihc', 'id_user_authorized_ihc');
        $this->forge->dropForeignKey('authorized_ihc', 'id_user_dokter_authorized_ihc');
        $this->forge->dropTable('authorized_ihc');
    }
}
