<?php

namespace App\Repositories;

use App\Entities\Role;
use App\Helpers\FileHelper;
use Config\Database;
use App\Interfaces\IRepository;
use App\Repositories\BaseRepository;

/**
 * UserTypeRepository class represents a repository for the user type model.
 * It implements the IRepository interface and extends the BaseRepository class.
 * It provides methods to interact with the user type table in the database.
 *
 * @author Guillaume Cornez
 */
class UserTypeRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('user_type');
    }
}