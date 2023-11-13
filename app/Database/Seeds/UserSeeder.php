<?php

namespace App\Database\Seeds;

use Faker\Factory;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $super_admin = [
            'totem' => 'Super admin',
            'name' => 'Cornez',
            'firstname' => 'Guillaume',
            'phone' => "+324". $faker->randomNumber(2,true) . "/" . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true), 
            'birthdate' => $faker->dateTimeThisCentury()->format('Y-m-d H:i:s'), // '1979-06-09 10:30:00
            'email' => 'super_admin@gmail.com',
            'password' => password_hash('super_admin@gmail.com', PASSWORD_DEFAULT),
            'user_type_id' => 1,
            'picture' => 'person1.jpg'
        ];

        $guide_admin = [
            'totem' => 'Guide admin',
            'name' => 'Cornez',
            'firstname' => 'Guillaume',
            'phone' => "+324". $faker->randomNumber(2,true) . "/" . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true), 
            'birthdate' => $faker->dateTimeThisCentury()->format('Y-m-d H:i:s'), // '1979-06-09 10:30:00
            'email' => 'guide_admin@gmail.com',
            'password' => password_hash('guide_admin@gmail.com', PASSWORD_DEFAULT),
            'user_type_id' => 1,
            'picture' => 'person2.jpg'
        ];
        
        $scout_admin = [
            'totem' => 'Scout admin',
            'name' => 'Cornez',
            'firstname' => 'Guillaume',
            'phone' => "+324". $faker->randomNumber(2,true) . "/" . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true), 
            'birthdate' => $faker->dateTimeThisCentury()->format('Y-m-d H:i:s'), // '1979-06-09 10:30:00
            'email' => 'scoutAdmin@gmail.com',
            'password' => password_hash('scout_admin@gmail.com', PASSWORD_DEFAULT),
            'user_type_id' => 1,
            'picture' => 'person3.jpg'
        ];

        $asbl_admin = [
            'totem' => 'Asbl admin',
            'name' => 'Cornez',
            'firstname' => 'Guillaume',
            'phone' => "+324". $faker->randomNumber(2,true) . "/" . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true) . "." . $faker->randomNumber(2,true), 
            'birthdate' => $faker->dateTimeThisCentury()->format('Y-m-d H:i:s'), // '1979-06-09 10:30:00
            'email' => 'asbl_admin@gmail.com',
            'password' => password_hash('asbl_admin@gmail.com,', PASSWORD_DEFAULT),
            'user_type_id' => 2,
            'picture' => 'person4.jpg'
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
            'user_type_id' => 5,
        ];
    }
}
