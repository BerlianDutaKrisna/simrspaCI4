<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penerimaan_frs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penerimaan_frs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_penerimaan_frs' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penerimaan_frs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_penerimaan_frs' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penerimaan_frs' => [
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

        $this->forge->addKey('id_penerimaan_frs', true); // Primary Key
        $this->forge->addForeignKey('id_frs', 'frs', 'id_frs', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel frs
        $this->forge->addForeignKey('id_user_penerimaan_frs', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penerimaan_frs');
    }

    public function down()
    {
        $this->forge->dropTable('penerimaan_frs');
    }
}
