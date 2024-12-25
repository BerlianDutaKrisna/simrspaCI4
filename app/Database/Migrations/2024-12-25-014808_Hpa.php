<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Hpa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_hpa' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_hpa' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'unique'     => true,
            ],
            'id_pasien' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'unit_asal' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dipilih',
                'null' => true,
            ],
            'dokter_pengirim' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dipilih',
                'null' => true,
            ],
            'tanggal_permintaan' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tanggal_hasil' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'lokasi_spesimen' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diisi',
                'null' => true,
            ],
            'tindakan_spesimen' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dipilih',
                'null' => true,
            ],
            'diagnosa_klinik' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diisi',
                'null' => true,
            ],
            'status_hpa' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diproses',
                'null' => true,
            ],
            'id_penerimaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_pengirisan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_mutu' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        ]);

        $this->forge->addKey('id_hpa', true); // Primary Key
        $this->forge->addForeignKey('id_pasien', 'patient', 'id_pasien', 'CASCADE', 'CASCADE');
        // MATIKAN DENGAN COMENT DAHULU LALU AKTIFKAN DAN MIGRATE
        $this->forge->addForeignKey('id_penerimaan', 'penerimaan', 'id_penerimaan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pengirisan', 'pengirisan', 'id_pengirisan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_mutu', 'mutu', 'id_mutu', 'CASCADE', 'CASCADE');

        $this->forge->createTable('hpa');
    }

    public function down()
    {
        $this->forge->dropTable('hpa');
    }
}