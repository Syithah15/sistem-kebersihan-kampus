<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLaporanTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'MENUNGGU',
                    'DIPROSES',
                    'SELESAI',
                    'DIBATALKAN',
                ],
                'default' => 'MENUNGGU',
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
        $this->forge->addKey('user_id');

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('laporan');
    }

    public function down()
    {
        $this->forge->dropTable('laporan');
    }
}