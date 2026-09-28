<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSection extends Migration
{
    /**
     * Sections of the unit. They are inserted here (and not in a seeder) because the agenda can not work without them.
     */
    private const SECTIONS = [
        ['name' => 'Unité',      'slug' => 'unite',      'branch' => 'UNITE',  'color' => '#1f2937', 'logo' => 'assets/img/logo.png'],
        ['name' => 'Nutons',     'slug' => 'nutons',     'branch' => 'GUIDE',  'color' => '#ea580c', 'logo' => 'assets/img/logo-nutons.png'],
        ['name' => 'Lutins',     'slug' => 'lutins',     'branch' => 'GUIDE',  'color' => '#2563eb', 'logo' => 'assets/img/logo-lutins.png'],
        ['name' => 'Aventures',  'slug' => 'aventures',  'branch' => 'GUIDE',  'color' => '#7c3aed', 'logo' => 'assets/img/logo-aventures.png'],
        ['name' => 'Horizons',   'slug' => 'horizons',   'branch' => 'GUIDE',  'color' => '#0d9488', 'logo' => 'assets/img/logo-horizons.png'],
        ['name' => 'Baladins',   'slug' => 'baladins',   'branch' => 'SCOUTE', 'color' => '#0ea5e9', 'logo' => 'assets/img/logo-baladins.png'],
        ['name' => 'Louveteaux', 'slug' => 'louveteaux', 'branch' => 'SCOUTE', 'color' => '#16a34a', 'logo' => 'assets/img/logo-louveteaux.png'],
        ['name' => 'Éclaireurs', 'slug' => 'eclaireurs', 'branch' => 'SCOUTE', 'color' => '#1e3a8a', 'logo' => 'assets/img/logo-eclaireurs.png'],
        ['name' => 'Pionniers',  'slug' => 'pionniers',  'branch' => 'SCOUTE', 'color' => '#dc2626', 'logo' => 'assets/img/logo-pionniers.png'],
    ];

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'unique' => true,
            ],
            'branch' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => '7',
                'null' => false,
            ],
            'logo' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'position' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'default' => 0,
            ],
            'exists' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('section');

        foreach (self::SECTIONS as $position => $section) {
            $this->db->table('section')->insert($section + ['position' => $position]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('section');
    }
}
