<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRegistrationAndContact extends Migration
{
    public function up()
    {
        // Registration requests of the form "En pratique > Inscription", handled by the administrators of the unit
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 9, 'unsigned' => true, 'auto_increment' => true],
            // Child
            'firstname' => ['type' => 'VARCHAR', 'constraint' => 100],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'totem' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'birthdate' => ['type' => 'DATE'],
            'section_id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'null' => true],
            // sibling (brother / sister already registered), former_member (child of a former member): phase 1 ; none: phase 2
            'relation' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'none'],
            // Address
            'street' => ['type' => 'VARCHAR', 'constraint' => 150],
            'number' => ['type' => 'VARCHAR', 'constraint' => 20],
            'zip_code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100],
            // Parent
            'parent_name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'parent_email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'parent_phone' => ['type' => 'VARCHAR', 'constraint' => 30],
            'remark' => ['type' => 'TEXT', 'null' => true],
            // Handling (App\Helpers\RegistrationHelper::STATUSES)
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'new'],
            'note' => ['type' => 'TEXT', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('section_id', 'section', 'id', '', 'SET NULL');
        $this->forge->createTable('registration');

        // Messages of the contact form
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 9, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'message' => ['type' => 'TEXT'],
            'is_read' => ['type' => 'BOOLEAN', 'default' => false],
            'is_handled' => ['type' => 'BOOLEAN', 'default' => false],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('contact_message');

        // Payment of the membership fee, until now written in the page
        $this->forge->addColumn('pricing', [
            'iban' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'payment_deadline' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        if ($this->db->table('pricing')->countAllResults() === 0)
            $this->db->table('pricing')->insert(['tier_1' => 53, 'tier_2' => 48, 'tier_3' => 38, 'reduction' => 5]);
        $this->db->table('pricing')->update(['iban' => 'BE84 7320 5580 4959', 'payment_deadline' => '31 octobre']);
    }

    public function down()
    {
        $this->forge->dropTable('registration');
        $this->forge->dropTable('contact_message');
        $this->forge->dropColumn('pricing', ['iban', 'payment_deadline', 'updated_at']);
    }
}
