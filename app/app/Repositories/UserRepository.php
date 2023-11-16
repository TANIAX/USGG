<?php

namespace App\Repositories;

use Config\Database;
use App\Entities\Role;
use App\Entities\User;
use App\Entities\UserType;
use App\Helpers\FileHelper;
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
        ->select('user.user_type_id AS user_type')
        ->select('user.*')
        ->whereIn('user_type_id', [6,2,3,4])
        ->get();
        $users = $this->getResultAs($query, $result_type, $result_class);
        foreach ($users as $user) {
            $user->user_type = $this->userTypeRepository->getById($user->user_type)->name;

            //If the user has a picture, we get it from the server otherwise we set a default picture
            if($user->picture && file_exists(FileHelper::PROFIL_PICTURE_DIRECTORY . $user->picture))
                $user->picture = base_url(FileHelper::PROFIL_PICTURE_DIRECTORY . $user->picture);
            else
                $user->picture = base_url(FileHelper::DEFAULT_PROFIL_PICTURE);
        }
        return $users;
    }

    /**
     * Retrieves a full user by an associative array of key-value pairs.
     *
     * @param array $associativeArray An associative array of key-value pairs.
     * @param int $result_type The type of result to return. Default is self::RESULT_AS_CUSTOM.
     * @param string $result_class The class to use for the result. Default is User::class.
     * @return mixed The full user object or null if not found.
     */
    public function getFullUserBy($associativeArray, $result_type = self::RESULT_AS_CUSTOM, $result_class = User::class)
    {
        $key = array_keys($associativeArray)[0];
        $value = $associativeArray[$key];

        $query = $this->builder
            ->select('user.*')
            ->select('user.user_type_id AS user_type')
            ->where('user.' . $key, $value)
            ->get();
        
        $user = $this->getResultAs($query, $result_type, $result_class);
        if($user)
        {
            $user = $user[0];
            $user->setUser_type($this->userTypeRepository->getById($user->getUser_type(), BaseRepository::RESULT_AS_CUSTOM, UserType::class));
            $user->setRoles($this->roleRepository->getRolesByUserId($user->id, BaseRepository::RESULT_AS_CUSTOM, Role::class));
        }

        return $user;
    }
}