<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pembacaan_imunohistokimia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pembacaan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_imunohistokimia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_pembacaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pembacaan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_pembacaan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pembacaan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_pembacaan' => [
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

        $this->forge->addKey('id_pembacaan', true); // Primary Key
        $this->forge->addForeignKey('id_imunohistokimia', 'imunohistokimia', 'id_imunohistokimia', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel imunohistokimia
        $this->forge->addForeignKey('id_user_pembacaan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_pembacaan', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)
        $this->forge->createTable('pembacaan_imunohistokimia');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pembacaan_imunohistokimia', 'id_imunohistokimia');
        $this->forge->dropForeignKey('pembacaan_imunohistokimia', 'id_user_pembacaan');
        $this->forge->dropForeignKey('pembacaan_imunohistokimia', 'id_user_dokter_pembacaan');
        $this->forge->dropTable('pembacaan_imunohistokimia');
    }
}
