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
            ->distinct()
            ->get();

        return $this->getResultAs($query, $result_type, $result_class);
    }
}
