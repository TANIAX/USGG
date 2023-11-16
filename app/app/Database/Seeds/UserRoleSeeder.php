<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $super_admin = [
            'user_id' => 1,
            'role_id' => 1,
        ];

        $guide_admin = [
            'user_id' => 2,
            'role_id' => 2,
        ];

        $scout_admin = [
            'user_id' => 3,
            'role_id' => 3,
        ];

        $asbl_admin = [
            'user_id' => 4,
            'role_id' => 4,
        ];


        $this->db->table('user_role')->insert($super_admin);
        $this->db->table('user_role')->insert($guide_admin);
        $this->db->table('user_role')->insert($scout_admin);
        $this->db->table('user_role')->insert($asbl_admin);
        foreach ($this->getAllUsers() as $user) {
            $this->db->table('user_role')->insert($user);
        }
    }

    private function getAllUsers()
    {
        $data = [];

        $users = $this->db->table('user')->get()->getResult();
        
        //for each user, set the roles
        foreach ($users as $user) {
            array_push($data, [
                'user_id' => $user->id,
                'role_id' => 5,
            ]);
        }

        return $data;
    }
}
