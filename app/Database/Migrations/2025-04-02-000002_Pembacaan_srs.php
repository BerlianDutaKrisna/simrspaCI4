<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pembacaan_srs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pembacaan_srs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_srs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_pembacaan_srs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pembacaan_srs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_pembacaan_srs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pembacaan_srs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_pembacaan_srs' => [
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

        $this->forge->addKey('id_pembacaan_srs', true); // Primary Key
        $this->forge->addForeignKey('id_srs', 'srs', 'id_srs', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel srs
        $this->forge->addForeignKey('id_user_pembacaan_srs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_pembacaan_srs', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)

        $this->forge->createTable('pembacaan_srs');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pembacaan_srs', 'id_srs');
        $this->forge->dropForeignKey('pembacaan_srs', 'id_user_pembacaan_srs');
        $this->forge->dropForeignKey('pembacaan_srs', 'id_user_dokter_pembacaan_srs');
        $this->forge->dropTable('pembacaan_srs');
    }
}
