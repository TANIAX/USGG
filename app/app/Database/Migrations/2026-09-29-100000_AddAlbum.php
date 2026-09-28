<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAlbum extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'branch' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
            ],
            'album_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('branch');
        $this->forge->addForeignKey('user_id', 'user', 'id');
        $this->forge->createTable('album');
    }

    public function down()
    {
        $this->forge->dropTable('album');
    }
}
