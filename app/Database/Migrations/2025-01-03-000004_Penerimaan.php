<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penerimaan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penerimaan' => [
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
            'id_user_penerimaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penerimaan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_penerimaan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penerimaan' => [
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

        $this->forge->addKey('id_penerimaan', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel hpa
        $this->forge->addForeignKey('id_user_penerimaan', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penerimaan');
    }

    public function down()
    {
        $this->forge->dropForeignKey('penerimaan', 'id_hpa');
        $this->forge->dropForeignKey('penerimaan', 'id_user_penerimaan');
        $this->forge->dropTable('penerimaan');
    }
}
