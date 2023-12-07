<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PricingSeeder extends Seeder
{
    public function run()
    {
        $price = [
            'tier_1' => 53,
            'tier_2' => 48,
            'tier_3' => 38,
            'reduction' => 5,
        ];

        $this->db->table('pricing')->insert($price);
    }
}
