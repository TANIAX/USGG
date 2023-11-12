<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUser extends Migration
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
            'totem' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'unique' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'unique' => true
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'birthdate' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'picture' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
        'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user');
    }

    public function down()
    {
        $this->forge->dropTable('user');
    }
}
