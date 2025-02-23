<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penerimaan_srs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penerimaan_srs' => [
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
            'id_user_penerimaan_srs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penerimaan_srs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_penerimaan_srs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penerimaan_srs' => [
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

        $this->forge->addKey('id_penerimaan_srs', true); // Primary Key
        $this->forge->addForeignKey('id_srs', 'srs', 'id_srs', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel srs
        $this->forge->addForeignKey('id_user_penerimaan_srs', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penerimaan_srs');
    }

    public function down()
    {
        $this->forge->dropForeignKey('penerimaan_srs', 'id_srs');
        $this->forge->dropForeignKey('penerimaan_srs', 'id_user_penerimaan_srs');
        $this->forge->dropTable('penerimaan_srs');
    }
}
