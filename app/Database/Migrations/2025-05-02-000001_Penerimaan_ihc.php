<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penerimaan_ihc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penerimaan_ihc' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_ihc' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_penerimaan_ihc' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penerimaan_ihc' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mulai_penerimaan_ihc' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penerimaan_ihc' => [
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

        $this->forge->addKey('id_penerimaan_ihc', true); // Primary Key
        $this->forge->addForeignKey('id_ihc', 'ihc', 'id_ihc', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel ihc
        $this->forge->addForeignKey('id_user_penerimaan_ihc', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penerimaan_ihc');
    }

    public function down()
    {
        $this->forge->dropTable('penerimaan_ihc');
    }
}
