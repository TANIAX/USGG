<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEventSection extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'event_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => false,
            ],
            'section_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => false,
            ],
        ]);
        $this->forge->addPrimaryKey(['event_id', 'section_id']);
        $this->forge->addForeignKey('event_id', 'event', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'section', 'id');
        $this->forge->createTable('event_section');
    }

    public function down()
    {
        $this->forge->dropTable('event_section');
    }
}
