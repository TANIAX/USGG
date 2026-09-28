<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEvent extends Migration
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
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'start_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'end_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'all_day' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false,
            ],
            'registration_url' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'exists' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['start_at', 'end_at']);
        $this->forge->addForeignKey('user_id', 'user', 'id');
        $this->forge->createTable('event');
    }

    public function down()
    {
        $this->forge->dropTable('event');
    }
}
