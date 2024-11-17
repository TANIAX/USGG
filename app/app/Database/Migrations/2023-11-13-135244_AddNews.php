<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNews extends Migration
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
                'unique' => true
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'unique' => true
            ],
            'content' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'picture' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'exists' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            'category_id' => [
                        'type' => 'INT',
                        'constraint' => 5,
                        'null' => false,
            ],
        'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'user', 'id');
        $this->forge->addForeignKey('category_id', 'category', 'id');
        $this->forge->createTable('news');
    }

    public function down()
    {
        $this->forge->dropTable('news');
    }
}
