<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Frs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_frs' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_frs' => [
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
                'null'       => true,
            ],
            'dokter_pengirim' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dipilih',
                'null'       => true,
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
                'null'       => true,
            ],
            'tindakan_spesimen' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dipilih',
                'null'       => true,
            ],
            'diagnosa_klinik' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diisi',
                'null'       => true,
            ],
            'status_frs' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diproses',
                'null'       => true,
            ],
            'makroskopis_frs' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'foto_makroskopis_frs' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'mikroskopis_frs' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'foto_mikroskopis_frs' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'jumlah_slide' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
            ],
            'hasil_frs' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'print_frs' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'penerima_frs' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diambil',
                'null' => true,
            ],
            'tanggal_penerima' => [
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

        $this->forge->addKey('id_frs', true);
        $this->forge->addForeignKey('id_pasien', 'patient', 'id_pasien', 'CASCADE', 'CASCADE');
        $this->forge->createTable('frs');
    }

    public function down()
    {
        $this->forge->dropTable('frs');
    }
}
