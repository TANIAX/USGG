<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test data of a new database (Docker, development): roles, functions, accounts.
 * Does nothing if the database already has roles, so it can run at each start.
 * The sections, the pricing, the FAQ, the testimonials and the pages (charter, privacy policy) are inserted by the migrations;
 * the missing pages are also added here (PageSeeder).
 *
 * php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Charter and privacy policy: only the missing pages (the texts changed in the administration are kept)
        $this->call('PageSeeder');

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
