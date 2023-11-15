<?php

namespace App\Repositories;

use App\Entities\Role;
use App\Helpers\FileHelper;
use Config\Database;
use App\Interfaces\IRepository;
use App\Repositories\BaseRepository;

/**
 * CategoryRepository class represents a repository for the category model.
 * It implements the IRepository interface and extends the BaseRepository class.
 * It provides methods to interact with the category table in the database.
 *
 * @author Guillaume Cornez
 */
class CategoryRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('category');
    }
}
