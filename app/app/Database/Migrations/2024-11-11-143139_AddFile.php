<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFile extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'path' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'exists' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            'file_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
                'default' => 'GUIDE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => current_timestamp,
                'null' => false,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('file');
    }

    public function down()
    {
        $this->forge->dropTable('file');
    }
}
