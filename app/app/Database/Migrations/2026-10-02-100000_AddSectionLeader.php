<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectionLeader extends Migration
{
    public function up()
    {
        // A leader is a user account linked to a section ("Unité" for the unit staff), shown on the home page
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
            'section_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => false,
            ],
            // Function in this section (a person can have a different function in each section)
            'user_type_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'position' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'default' => 0,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['user_id', 'section_id']);
        $this->forge->addForeignKey('user_id', 'user', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'section', 'id', '', 'CASCADE');
        $this->forge->createTable('section_leader');

        // Typo in the functions ("Annimateur")
        $this->db->query("UPDATE user_type SET name = REPLACE(name, 'Annimateur', 'Animateur') WHERE name LIKE '%Annimateur%'");
    }

    public function down()
    {
        $this->forge->dropTable('section_leader');
    }
}
