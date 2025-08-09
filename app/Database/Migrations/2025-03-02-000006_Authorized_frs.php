<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Authorized_frs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_authorized_frs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'id_user_authorized_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_authorized_frs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_authorized_frs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_authorized_frs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_authorized_frs' => [
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

        $this->forge->addKey('id_authorized_frs', true); // Primary Key
        $this->forge->addForeignKey('id_frs', 'frs', 'id_frs', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel frs
        $this->forge->addForeignKey('id_user_authorized_frs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_authorized_frs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)
        $this->forge->createTable('authorized_frs');
    }

    public function down()
    {
        $this->forge->dropTable('authorized_frs');
    }
}
