<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penerimaan_hpa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penerimaan_hpa' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_penerimaan_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penerimaan_hpa' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_penerimaan_hpa' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penerimaan_hpa' => [
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

        $this->forge->addKey('id_penerimaan_hpa', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel hpa
        $this->forge->addForeignKey('id_user_penerimaan_hpa', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penerimaan_hpa');
    }

    public function down()
    {
        $this->forge->dropForeignKey('penerimaan_hpa', 'id_hpa');
        $this->forge->dropForeignKey('penerimaan_hpa', 'id_user_penerimaan_hpa');
        $this->forge->dropTable('penerimaan_hpa');
    }
}
