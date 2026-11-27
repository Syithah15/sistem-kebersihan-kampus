<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRiwayatStatusTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status_sebelumnya' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'MENUNGGU',
                    'DIPROSES',
                    'SELESAI',
                    'DIBATALKAN',
                ],
                'null' => true,
            ],
            'status_baru' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'MENUNGGU',
                    'DIPROSES',
                    'SELESAI',
                    'DIBATALKAN',
                ],
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('laporan_id');
        $this->forge->addKey('user_id');

        $this->forge->addForeignKey(
            'laporan_id',
            'laporan',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->createTable('riwayat_status');
    }

    public function down()
    {
        $this->forge->dropTable('riwayat_status');
    }
}