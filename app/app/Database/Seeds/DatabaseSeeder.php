<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test data of a new database (Docker, development): roles, functions, accounts.
 * Does nothing if the database already has roles, so it can run at each start.
 * The sections, the pricing, the FAQ and the testimonials are inserted by the migrations.
 *
 * php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('role')->countAllResults() > 0) {
            echo "Base déjà initialisée : aucune donnée de test ajoutée.\n";
            return;
        }

        $this->call('RoleSeeder');
        $this->call('UserTypeSeeder');
        $this->call('UserSeeder');
        $this->call('UserRoleSeeder');
    }
}
