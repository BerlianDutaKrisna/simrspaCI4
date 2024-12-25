<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mutu extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'id_mutu' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'id_hpa' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'indikator_1' => [
                    'type'       => 'INT',
                    'constraint' => 100,
                    'null'       => true,
                ],
                'indikator_2' => [
                    'type'       => 'INT',
                    'constraint' => 100,
                    'null'       => true,
                ],
                'indikator_3' => [
                    'type'       => 'INT',
                    'constraint' => 100,
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
            ]
        );

        $this->forge->addKey('id_mutu', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel hpa
        $this->forge->createTable('mutu');
    }

    public function down()
    {
        $this->forge->dropTable('mutu');
    }
}
