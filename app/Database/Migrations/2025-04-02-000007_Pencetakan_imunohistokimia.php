<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pencetakan_imunohistokimia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pencetakan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_imunohistokimia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'id_user_pencetakan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pencetakan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_pencetakan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pencetakan' => [
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

        $this->forge->addKey('id_pencetakan', true); // Primary Key
        $this->forge->addForeignKey('id_imunohistokimia', 'imunohistokimia', 'id_imunohistokimia', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel imunohistokimia
        $this->forge->addForeignKey('id_user_pencetakan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->createTable('pencetakan_imunohistokimia');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pencetakan_imunohistokimia', 'id_imunohistokimia');
        $this->forge->dropForeignKey('pencetakan_imunohistokimia', 'id_user_pencetakan');
        $this->forge->dropTable('pencetakan_imunohistokimia');
    }
}
