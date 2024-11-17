<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPricing extends Migration
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
            'tier_1' => [
                'type' => 'FLOAT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'tier_2' => [
                'type' => 'FLOAT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'tier_3' => [
                'type' => 'FLOAT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'reduction' => [
                'type' => 'FLOAT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'exists' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
        'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pricing');
    }

    public function down()
    {
        $this->forge->dropTable('pricing');
    }
}
