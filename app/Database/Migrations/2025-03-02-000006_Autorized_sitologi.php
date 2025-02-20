<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Autorized_sitologi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_autorized' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_sitologi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'id_user_autorized' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_autorized' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_autorized' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_autorized' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_autorized' => [
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

        $this->forge->addKey('id_autorized', true); // Primary Key
        $this->forge->addForeignKey('id_sitologi', 'sitologi', 'id_sitologi', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel sitologi
        $this->forge->addForeignKey('id_user_autorized', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_autorized', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)

        $this->forge->createTable('autorized_sitologi');
    }

    public function down()
    {
        $this->forge->dropForeignKey('autorized_sitologi', 'id_sitologi');
        $this->forge->dropForeignKey('autorized_sitologi', 'id_user_autorized');
        $this->forge->dropForeignKey('autorized_sitologi', 'id_user_dokter_autorized');
        $this->forge->dropTable('autorized_sitologi');
    }
}
