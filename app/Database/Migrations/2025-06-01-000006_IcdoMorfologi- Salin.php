<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IcdoMorfologi extends Migration
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
            'kode_morfologi' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
            ],
            'nama_morfologi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('kode_morfologi');

        $this->forge->createTable('icdo_morfologi', true);
    }

    public function down()
    {
        $this->forge->dropTable('icdo_morfologi', true);
    }
}
