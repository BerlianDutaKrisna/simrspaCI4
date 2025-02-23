<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        // Menambahkan field untuk tabel users
        $this->forge->addField([
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,  // Menjadikan ini auto increment
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
                'unique' => true,  // Menambahkan constraint unique untuk username
            ],
            'password_user' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'nama_user' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'status_user' => [
                'type' => 'ENUM',
                'constraint' => ['Admin', 'Analis', 'Dokter', 'Belum Dipilih'],  // Status bisa 'admin', 'analis', atau 'dokter'
                'default' => 'Belum Dipilih',  // Default adalah 'Belum Dipilih'
                'null'       => true,
            ],'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id_user', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
