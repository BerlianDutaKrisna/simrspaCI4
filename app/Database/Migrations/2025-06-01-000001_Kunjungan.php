<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kunjungan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idtransaksi' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false
            ],
            'tanggal' => [
                'type'       => 'DATETIME',
                'null'       => true
            ],
            'idpasien' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'norm' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true
            ],
            'tgl_lhr' => [
                'type'       => 'DATE',
                'null'       => true
            ],
            'pasien_usia' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true
            ],
            'beratbadan' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true
            ],
            'tinggibadan' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true
            ],
            'alamat' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'jeniskelamin' => [
                'type'       => 'CHAR',
                'constraint' => 1,
                'null'       => true
            ],
            'kota' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true
            ],
            'jenispasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true
            ],
            'iddokterperujuk' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'dokterperujuk' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true
            ],
            'iddokterpa' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'dokterpa' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true
            ],
            'pelayananasal' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true
            ],
            'idunitasal' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'unitasal' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true
            ],
            'register' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true
            ],
            'pemeriksaan' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true
            ],
            'responsetime' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true
            ],
            'statuslokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true
            ],
            'diagnosaklinik' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'hasil' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'diagnosapatologi' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'mutusediaan' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true
            ],
            'tagihan' => [
                'type'       => 'BIGINT',
                'null'       => true
            ],
        ]);

        $this->forge->addKey('idtransaksi', true);
        $this->forge->createTable('kunjungan', true);
    }

    public function down()
    {
        $this->forge->dropTable('kunjungan', true);
    }
}
