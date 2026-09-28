<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoto extends Migration
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
            'album_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => false,
            ],
            // Random name of the file stored in writable/uploads/gallery/{album_id}/
            'filename' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
                'unique' => true,
            ],
            'width' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            'height' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            // Visible without an account (otherwise any logged in user can see it)
            'is_public' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            'position' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'default' => 0,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['album_id', 'position']);
        $this->forge->addForeignKey('album_id', 'album', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'user', 'id');
        $this->forge->createTable('photo');
    }

    public function down()
    {
        $this->forge->dropTable('photo');
    }
}
