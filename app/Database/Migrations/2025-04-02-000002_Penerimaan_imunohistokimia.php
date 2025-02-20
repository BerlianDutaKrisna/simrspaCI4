<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penerimaan_imunohistokimia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penerimaan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_imunohistokimia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique' => true,
            ],
            'id_user_penerimaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_penerimaan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Belum Dilakukan',
                'null' => true,
            ],
            'mulai_penerimaan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'selesai_penerimaan' => [
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

        $this->forge->addKey('id_penerimaan', true); // Primary Key
        $this->forge->addForeignKey('id_imunohistokimia', 'imunohistokimia', 'id_imunohistokimia', 'CASCADE', 'CASCADE'); // Sesuaikan dengan tabel imunohistokimia
        $this->forge->addForeignKey('id_user_penerimaan', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penerimaan_imunohistokimia');
    }

    public function down()
    {
        $this->forge->dropForeignKey('penerimaan_imunohistokimia', 'id_imunohistokimia');
        $this->forge->dropForeignKey('penerimaan_imunohistokimia', 'id_user_penerimaan');
        $this->forge->dropTable('penerimaan_imunohistokimia');
    }
}
