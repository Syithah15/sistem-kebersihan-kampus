<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotifikasiTable extends Migration
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
                'unsigned'   => true,
            ],
            'laporan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'pesan' => [
                'type' => 'TEXT',
            ],
            'tipe' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'is_read' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
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
        $this->forge->addKey('laporan_id');

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'laporan_id',
            'laporan',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->createTable('notifikasi');
    }

    public function down()
    {
        $this->forge->dropTable('notifikasi');
    }
}