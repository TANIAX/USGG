<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDocumentVisibility extends Migration
{
    public function up()
    {
        $this->forge->addColumn('file', [
            // An inactive document is hidden from the public (still visible in the administration)
            'is_active' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            // Random name of the file in writable/uploads/documents/ (not reachable with an url).
            // NULL for the documents uploaded before, still stored in public/uploads/documents/{path}/{name}
            'stored_name' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true,
            ],
            'mime_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'size' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('file', ['is_active', 'stored_name', 'mime_type', 'size', 'updated_at']);
    }
}
