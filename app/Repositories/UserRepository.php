<?php

namespace App\Repositories;

use App\Entities\Role;
use Config\Database;
use App\Interfaces\IRepository;
use App\Repositories\BaseRepository;

/**
 * UserRepository class represents a repository for the user model.
 * It implements the IRepository interface and extends the BaseRepository class.
 * It provides methods to interact with the user table in the database.
 *
 * @author Guillaume Cornez
 */
class UserRepository extends BaseRepository
{
    private $db;
    private $builder;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->builder = $this->db->table('user');
    }

        
    /**
     * getAll
     *
     * @param  int $result_type
     * @param  object $result_class
     * @return array of objects
     */
    public function getAllWithRoles($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder->get();
        $users = $this->getResultAs($query, $result_type, $result_class);
        foreach ($users as $user) {
            $user->roles = $this->getRolesByUserId($user->id);
        }
        return $users;
    }


    private function getRolesByUserId($user_id)
    {
        $query = $this->builder->select('role.*')
            ->join('user_role', 'user_role.user_id = user.id')
            ->join('role', 'role.id = user_role.role_id')
            ->where('user.id', $user_id)
            ->distinct()
            ->get();
        return $this->getResultAs($query, self::RESULT_AS_OBJECT);
    }
    
}