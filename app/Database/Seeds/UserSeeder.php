<?php

namespace App\Database\Seeds;

use Faker\Factory;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $super_admin = [
            'totem' => 'SuperAdmin',
            'name' => 'Cornez',
            'firstname' => 'Guillaume',
            'phone' => "+324". "00" . "/" . "00" . "." . "00" . "." . "00",
            'birthdate' => '1979-06-09 10:30:00',
            'email' => 'superadmin@gmail.com',
            'password' => password_hash('superAdmin@gmail.com', PASSWORD_DEFAULT),
            'created_at' => '2024-01-01 00:00:00',
            'updated_at' => '2024-01-01 00:00:00',
        ];

        $guide_admin = [
            'totem' => 'GuideAdmin',
            'name' => 'Cornez',
            'firstname' => 'Guillaume',
            'phone' => "+324". "00" . "/" . "00" . "." . "00" . "." . "00",
            'birthdate' => '1979-06-09 10:30:00',
            'email' => 'guideAdmin@gmail.com',
            'password' => password_hash('guideAdmin@gmail.com', PASSWORD_DEFAULT),
            'created_at' => '2024-01-01 00:00:00',
            'updated_at' => '2024-01-01 00:00:00',
        ];
        
        $scout_admin = [
            'totem' => 'ScoutAdmin',
            'name' => 'Cornez',
            'firstname' => 'Guillaume',
            'phone' => "+324". "00" . "/" . "00" . "." . "00" . "." . "00",
            'birthdate' => '1979-06-09 10:30:00',
            'email' => 'scoutAdmin@gmail.com',
            'password' => password_hash('scoutAdmin@gmail.com', PASSWORD_DEFAULT),
            'created_at' => '2024-01-01 00:00:00',
            'updated_at' => '2024-01-01 00:00:00',
        ];

        $asbl_admin = [
            'totem' => 'AsblAdmin',
            'name' => 'Cornez',
            'firstname' => 'Guillaume',
            'phone' => "+324". "00" . "/" . "00" . "." . "00" . "." . "00",
            'birthdate' => '1979-06-09 10:30:00',
            'email' => 'asblAdmin@gmail.com',
            'password' => password_hash('asblAdmin@gmail.com,', PASSWORD_DEFAULT),
            'created_at' => '2024-01-01 00:00:00',
            'updated_at' => '2024-01-01 00:00:00',
        ];
        
        $this->db->table('user')->insert($super_admin);
        $this->db->table('user')->insert($guide_admin);
        $this->db->table('user')->insert($scout_admin);
        $this->db->table('user')->insert($asbl_admin);

        // Add 10 more users
        for ($i = 0; $i < 10; $i++) { 
            $this->db->table('user')->insert($this->generateUser());
        }
    }

    private function generateUser(): array
    {
        $faker = Factory::create();
        return [
            'totem' => $faker->word(),
            'name' => $faker->name(),
            'firstname' => $faker->firstName(),
            'phone' => "+324". $faker->randomNumber(2,true) . "/" . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true), 
            'birthdate' => $faker->dateTimeThisCentury()->format('Y-m-d H:i:s'), // '1979-06-09 10:30:00
            'email' => $faker->email(),
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            'updated_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
        ];
    }
}
