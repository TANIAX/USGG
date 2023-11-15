<?php

namespace App\Repositories;

use App\Entities\Role;
use App\Helpers\FileHelper;
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
    private $userTypeRepository;
    private $roleRepository;


    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('user');
        $this->userTypeRepository = service('Repository', 'UserType');
        $this->roleRepository = service('Repository', 'Role');
    }

    /**
     * GetAllMainLeaders
     *
     * @param  int $result_type
     * @param  object $result_class
     * @return array of objects
     */
    public function GetAllMainLeaders($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder
        ->whereIn('user_type_id', [1,2,3,4])
        ->get();
        $users = $this->getResultAs($query, $result_type, $result_class);
        foreach ($users as $user) {
            $user->user_type = $this->userTypeRepository->getById($user->id)->name;

            //If the user has a picture, we get it from the server otherwise we set a default picture
            if($user->picture && file_exists(FileHelper::PROFIL_PICTURE_DIRECTORY . $user->picture))
                $user->picture = base_url(FileHelper::PROFIL_PICTURE_DIRECTORY . $user->picture);
            else
                $user->picture = base_url(FileHelper::DEFAULT_PROFIL_PICTURE);
        }
        return $users;
    }
}