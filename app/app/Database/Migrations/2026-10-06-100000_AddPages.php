<?php

namespace App\Database\Migrations;

use App\Database\Seeds\PageSeeder;
use CodeIgniter\Database\Migration;

/**
 * Pages written by the super admins (admin/pages): charter of the unit and privacy policy,
 * inserted with their default text (App\Database\Seeds\PageSeeder).
 */
class AddPages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 9, 'unsigned' => true, 'auto_increment' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 50],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'content' => ['type' => 'TEXT'],
            'updated_by' => ['type' => 'INT', 'constraint' => 9, 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('page');

        foreach (PageSeeder::PAGES as $slug => $page) {
            $this->db->table('page')->insert(['slug' => $slug, 'title' => $page['title'], 'content' => $page['content'], 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('page');
    }
}
