<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pemverifikasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemverifikasi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_hpa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_user_pemverifikasi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_pemverifikasi' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Diverifikasi', 'Proses Verifikasi', 'Selesai Verifikasi'],
                'default'    => 'Belum Diverifikasi',
                'null'       => true,
            ],
            'mulai_pemverifikasi' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_pemverifikasi' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_user_dokter_pemverifikasi' => [
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

        $this->forge->addKey('id_pemverifikasi', true); // Primary Key
        $this->forge->addForeignKey('id_hpa', 'hpa', 'id_hpa', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel hpa
        $this->forge->addForeignKey('id_user_pemverifikasi', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users
        $this->forge->addForeignKey('id_user_dokter_pemverifikasi', 'users', 'id_user', 'CASCADE', 'CASCADE'); // Foreign Key ke tabel users (Dokter)

        $this->forge->createTable('pemverifikasi');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemverifikasi', 'id_hpa');
        $this->forge->dropForeignKey('pemverifikasi', 'id_user_pemverifikasi');
        $this->forge->dropForeignKey('pemverifikasi', 'id_user_dokter_pemverifikasi');
        $this->forge->dropTable('pemverifikasi');
    }
}
