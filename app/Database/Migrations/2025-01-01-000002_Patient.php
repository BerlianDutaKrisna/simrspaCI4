<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Patient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pasien' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'norm_pasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => false,
                'unique'     => true,
            ],
            'nama_pasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'alamat_pasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diisi',
                'null' => true,
            ],
            'tanggal_lahir_pasien' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'jenis_kelamin_pasien' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P', 'Belum Dipilih'],
                'default'    => 'Belum Dipilih',
                'null'       => true,
            ],
            'status_pasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Diisi',
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

        $this->forge->addKey('id_pasien', true);
        $this->forge->createTable('patient');
    }

    //ALTER TABLE frs DROP FOREIGN KEY frs_id_pasien_foreign;
    // ALTER TABLE hpa DROP FOREIGN KEY hpa_id_pasien_foreign;
    // ALTER TABLE srs DROP FOREIGN KEY srs_id_pasien_foreign;
    // ALTER TABLE ihc DROP FOREIGN KEY ihc_id_pasien_foreign;

    // HAPUS AUTOINCREMEN PADA ID_PASIEN

    // ALTER TABLE frs ADD CONSTRAINT frs_id_pasien_foreign
    // FOREIGN KEY (id_pasien) REFERENCES patient(id_pasien)
    // ON DELETE CASCADE ON UPDATE CASCADE;

    // ALTER TABLE hpa ADD CONSTRAINT hpa_id_pasien_foreign
    // FOREIGN KEY (id_pasien) REFERENCES patient(id_pasien)
    // ON DELETE CASCADE ON UPDATE CASCADE;

    // ALTER TABLE srs ADD CONSTRAINT srs_id_pasien_foreign
    // FOREIGN KEY (id_pasien) REFERENCES patient(id_pasien)
    // ON DELETE CASCADE ON UPDATE CASCADE;

    // ALTER TABLE ihc ADD CONSTRAINT ihc_id_pasien_foreign
    // FOREIGN KEY (id_pasien) REFERENCES patient(id_pasien)
    // ON DELETE CASCADE ON UPDATE CASCADE;


    public function down()
    {
        $this->forge->dropTable('patient');
    }
}
