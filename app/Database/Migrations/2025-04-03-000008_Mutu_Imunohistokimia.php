<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mutu_imunohistokimia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_mutu' => [
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
            'indikator_1' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_2' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_3' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_4' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_5' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_6' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_7' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_8' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_9' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'indikator_10' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
            ],
            'total_nilai_mutu' => [
                'type'       => 'INT',
                'constraint' => 100,
                'null'       => true,
                'default'    => 0, // Nilai default 0
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

        $this->forge->addKey('id_mutu', true); // Primary Key
        $this->forge->addForeignKey('id_imunohistokimia', 'imunohistokimia', 'id_imunohistokimia', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel imunohistokimia
        $this->forge->createTable('mutu_imunohistokimia');
    }


    public function down()
    {
        $this->forge->dropTable('mutu_imunohistokimia');
    }
}
