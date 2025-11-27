<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Gambar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_gambar' => [
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
            'nama_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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

        // Primary key
        $this->forge->addKey('id_gambar', true);

        // Foreign key ke tabel hpa (id_hpa)
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE');

        // Buat tabel
        $this->forge->createTable('gambar');
    }

    public function down()
    {
        $this->forge->dropTable('gambar');
    }
}
