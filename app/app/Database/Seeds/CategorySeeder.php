<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {        
        $evenements = [
            'name' => 'Évenements',
        ];

        $services = [
            'name' => 'Services',
        ];
        

        $this->db->table('category')->insert($evenements);
        $this->db->table('category')->insert($services);
    }
}
