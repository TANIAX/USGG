<?php

namespace App\Repositories;

use App\Entities\Role;
use App\Helpers\FileHelper;
use Config\Database;
use App\Interfaces\IRepository;
use App\Repositories\BaseRepository;

/**
 * RoleRepository class represents a repository for the role model.
 * It implements the IRepository interface and extends the BaseRepository class.
 * It provides methods to interact with the role table in the database.
 *
 * @author Guillaume Cornez
 */
class RoleRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('role');
    }

    /**
     * Get roles by user ID.
     *
     * @param int $user_id The ID of the user.
     * @param int $result_type The type of result to return. Default is self::RESULT_AS_OBJECT.
     * @param string|null $result_class The class to use for the result. Default is null.
     * @return mixed The result of the query.
     */
    public function getRolesByUserId($user_id, $result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder->select('role.*')
            ->join('user_role', 'user_role.role_id = role.id')
            ->where('user_role.user_id', $user_id)
            ->where('user_role.exists', true)
            ->where('role.exists', true)
            ->distinct()
            ->get();

        return $this->getResultAs($query, $result_type, $result_class);
    }

    /**
     * Names of the roles of a user.
     *
     * @return array of string
     */
    public function getRoleNames(int $userId)
    {
        return array_column($this->getRolesByUserId($userId, self::RESULT_AS_ARRAY), 'name');
    }

    /**
     * Replaces the roles of a user by the given ones (names). The base role "user" is always kept.
     */
    public function setUserRoles(int $userId, array $roleNames)
    {
        $roleNames = array_unique(array_merge($roleNames, ['user']));
        $roles = $this->db->table('role')->select('id, name')->where('exists', true)->whereIn('name', $roleNames)->get()->getResultObject();
        $now = date('Y-m-d H:i:s');

        $this->db->transStart();
        $this->db->table('user_role')->where('user_id', $userId)->delete();
        foreach ($roles as $role) {
            $this->db->table('user_role')->insert(['user_id' => $userId, 'role_id' => $role->id, 'exists' => true, 'created_at' => $now]);
        }
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
