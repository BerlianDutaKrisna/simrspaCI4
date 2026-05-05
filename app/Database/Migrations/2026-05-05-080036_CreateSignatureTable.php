<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSignatureTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'id_transaksi' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tanggal' => ['type' => 'DATE', 'null' => true],
            'register' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'noregister' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'idpasien' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'norm' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'tgl_lahir' => ['type' => 'DATE', 'null' => true],
            'jenis_kelamin' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'alamat' => ['type' => 'TEXT', 'null' => true],

            'dokter_pelaksana' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'petugas_pelaksana' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'pemberi_informasi' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],

            'hubungan_dengan_pasien' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nama_hubungan_pasien' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'tgl_lahir_hubungan_pasien' => ['type' => 'DATE', 'null' => true],
            'alamat_hubungan_pasien' => ['type' => 'TEXT', 'null' => true],

            'diagnosis_kerja' => ['type' => 'TEXT', 'null' => true],

            // Signature (base64 JSON)
            'concentSignaturePasien' => ['type' => 'LONGTEXT', 'null' => true],
            'concentSignatureDokter' => ['type' => 'LONGTEXT', 'null' => true],
            'concentSignaturePetugas' => ['type' => 'LONGTEXT', 'null' => true],

            'dateTimeSignature' => ['type' => 'DATETIME', 'null' => true],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('signature');
    }

    public function down()
    {
        $this->forge->dropTable('signature');
    }
}