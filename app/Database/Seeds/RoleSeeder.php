<?php

namespace App\Database\Seeds;

use Faker\Factory;
use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {

        $super_admin = [
            'name' => 'super_admin',
        ];

        $guide_admin = [
            'name' => 'guide_admin',
        ];

        $scout_admin = [
            'name' => 'scout_admin',
        ];

        $asbl_admin = [
            'name' => 'asbl_admin',
        ];

        $user = [
            'name' => 'user',
        ];

        $this->db->table('role')->insert($super_admin);
        $this->db->table('role')->insert($guide_admin);
        $this->db->table('role')->insert($scout_admin);
        $this->db->table('role')->insert($asbl_admin);
        $this->db->table('role')->insert($user);
    }
}
