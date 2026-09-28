<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordReset extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            // SHA-256 of the token sent by e-mail: the token itself is never stored
            'token_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => false,
                'unique' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'user', 'id', '', 'CASCADE');
        $this->forge->createTable('password_reset');
    }

    public function down()
    {
        $this->forge->dropTable('password_reset');
    }
}
