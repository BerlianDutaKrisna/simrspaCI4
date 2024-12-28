<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemotongan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemotongan' => [
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
            'id_user_pemotongan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemotongan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_pemotongan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemotongan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_pemotongan' => [
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

        $this->forge->addKey('id_pemotongan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user_pemotongan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_pemotongan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)

        $this->forge->createTable('pemotongan');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemotongan', 'id_hpa');
        $this->forge->dropForeignKey('pemotongan', 'id_user_pemotongan');
        $this->forge->dropForeignKey('pemotongan', 'id_user_dokter_pemotongan');
        $this->forge->dropTable('pemotongan');
    }
}
