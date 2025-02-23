<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pembacaan_frs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pembacaan_frs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_pembacaan_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pembacaan_frs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_pembacaan_frs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pembacaan_frs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_pembacaan_frs' => [
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

        $this->forge->addKey('id_pembacaan_frs', true); // Primary Key
        $this->forge->addForeignKey('id_frs', 'frs', 'id_frs', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel frs
        $this->forge->addForeignKey('id_user_pembacaan_frs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_pembacaan_frs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)
        $this->forge->createTable('pembacaan_frs');
    }

    public function down()
    {
        $this->forge->dropTable('pembacaan_frs');
    }
}
