<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    public function run()
    {
        $chef_unite = [
            'name' => 'Chef d\'unité',
        ];
        
        $president = [
            'name' => 'Président',
        ];

        $tresorier = [
            'name' => 'Trésorier',
        ];

        $grand_chef = [
            'name' => 'Grand chef',
        ];

        $chef = [
            'name' => 'Chef',
        ];

        $this->db->table('user_type')->insert($chef_unite);
        $this->db->table('user_type')->insert($president);
        $this->db->table('user_type')->insert($tresorier);
        $this->db->table('user_type')->insert($grand_chef);
        $this->db->table('user_type')->insert($chef);

    }
}
