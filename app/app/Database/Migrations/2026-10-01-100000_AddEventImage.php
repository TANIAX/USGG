<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEventImage extends Migration
{
    public function up()
    {
        // Optional image of an event (random file name in public/uploads/events/), shown in the news and the agenda
        $this->forge->addColumn('event', [
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('event', 'image');
    }
}
