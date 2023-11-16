<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserRole extends Migration
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
            ],
            'role_id' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
        'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id'); //no way to have a composite primary key with CodeIgniter ?? damnnnn
        $this->forge->addForeignKey('user_id', 'user', 'id');
        $this->forge->addForeignKey('role_id', 'role', 'id');

        $this->forge->createTable('user_role');
    }

    public function down()
    {
        $this->forge->dropTable('user_role');
    }
}
