<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PemotonganTipis extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemotongan_tipis' => [
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
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemotongan_tipis' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Dipotong Tipis', 'Proses Pemotongan Tipis', 'Selesai Pemotongan Tipis'],
                'default'    => 'Belum Dipotong Tipis',
                'null'       => true,
            ],
            'mulai_pemotongan_tipis' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemotongan_tipis' => [
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

        $this->forge->addKey('id_pemotongan_tipis', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pemotongan_tipis');
    }

    public function down()
    {
        $this->forge->dropTable('pemotongan_tipis');
    }
}