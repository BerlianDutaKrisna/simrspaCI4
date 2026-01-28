<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IcdoTopografi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_topografi' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
            ],
            'nama_topografi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('kode_topografi'); // penting untuk LIKE 'C50%'

        $this->forge->createTable('icdo_topografi', true);
    }

    public function down()
    {
        $this->forge->dropTable('icdo_topografi', true);
    }
}
