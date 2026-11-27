<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLokasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'laporan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
            ],
            'nama_lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'akurasi_gps' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
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

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('laporan_id');

        $this->forge->addForeignKey(
            'laporan_id',
            'laporan',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('lokasi');
    }

    public function down()
    {
        $this->forge->dropTable('lokasi');
    }
}