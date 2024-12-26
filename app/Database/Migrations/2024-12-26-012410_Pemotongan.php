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
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status_pemotongan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Dipotong', 'Proses Pemotongan', 'Selesai Pemotongan'],
                'default'    => 'Belum Dipotong',
                'null'       => true,
            ],
            'mulai_pemotongan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemotongan' => [
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

        $this->forge->addKey('id_pemotongan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pemotongan');
    }

    public function down()
    {
        $this->forge->dropTable('pemotongan');
    }
}
