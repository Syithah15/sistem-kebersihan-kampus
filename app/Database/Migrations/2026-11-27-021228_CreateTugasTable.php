<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTugasTable extends Migration
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
            'petugas_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'prioritas' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'RENDAH',
                    'SEDANG',
                    'TINGGI',
                    'DARURAT',
                ],
                'default' => 'SEDANG',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'DITUGASKAN',
                    'DIKERJAKAN',
                    'SELESAI',
                    'DIBATALKAN',
                ],
                'default' => 'DITUGASKAN',
            ],
            'waktu_diambil' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'waktu_selesai' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'foto_sebelum' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'foto_sesudah' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'catatan' => [
                'type' => 'TEXT',
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

        $this->forge->addKey('id', true);
        $this->forge->addKey('laporan_id');
        $this->forge->addKey('petugas_id');

        $this->forge->addForeignKey(
            'laporan_id',
            'laporan',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'petugas_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('tugas');
    }

    public function down()
    {
        $this->forge->dropTable('tugas');
    }
}