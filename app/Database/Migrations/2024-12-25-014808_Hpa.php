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
            'status_hpa' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diproses',
                'null'       => true,
            ],
            'makroskopis_hpa' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'foto_makroskopis_hpa' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'mikroskopis_hpa' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'foto_mikroskopis_hpa' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'jumlah_slide' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
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
            'id_pemotongan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_pemprosesan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_penanaman' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_pemotongan_tipis' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_pewarnaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_pembacaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_penulisan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_pemverifikasi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_pencetakan' => [
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

        // Primary Key
        $this->forge->addKey('id_hpa', true);

        // Foreign Keys
        $this->forge->addForeignKey('id_pasien', 'patient', 'id_pasien', 'CASCADE', 'CASCADE');
        // KOMENT TERLEBIH DAHULU UNTUK MERGE PERTAMA DAN BUKA KOMENT LALU MERGE KEDUA
        // $this->forge->addForeignKey('id_penerimaan', 'penerimaan', 'id_penerimaan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pengirisan', 'pengirisan', 'id_pengirisan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pemotongan', 'pemotongan', 'id_pemotongan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pemprosesan', 'pemprosesan', 'id_pemprosesan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_penanaman', 'penanaman', 'id_penanaman', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pemotongan_tipis', 'pemotongan_tipis', 'id_pemotongan_tipis', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pewarnaan', 'pewarnaan', 'id_pewarnaan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pembacaan', 'pembacaan', 'id_pembacaan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_penulisan', 'penulisan', 'id_penulisan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pemverifikasi', 'pemverifikasi', 'id_pemverifikasi', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pencetakan', 'pencetakan', 'id_pencetakan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_mutu', 'mutu', 'id_mutu', 'CASCADE', 'CASCADE');

        // Create Table
        $this->forge->createTable('hpa');
    }

    public function down()
    {
        $this->forge->dropTable('hpa');
    }
}