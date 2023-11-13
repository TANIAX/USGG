<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserType extends Migration
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
                'constraint' => '100',
                'null' => false,
                'unique' => false
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
        'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user_type');
    }

    public function down()
    {
        $this->forge->dropTable('user_type');
    }
}
