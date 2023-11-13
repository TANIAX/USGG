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
    private $db;
    private $builder;
    private $userTypeRepository;
    private $roleRepository;


    public function __construct()
    {
        $this->db = Database::connect();
        $this->builder = $this->db->table('user');
        $this->userTypeRepository = service('Repository', 'UserType');
        $this->roleRepository = service('Repository', 'Role');
    }

        
    /**
     * getAll
     *
     * @param  int $result_type
     * @param  object $result_class
     * @return array of objects
     */
    public function getAll($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder->get();
        return $this->getResultAs($query, $result_type, $result_class);
    }
    
    /**
     * getById
     *
     * @param  int $id
     * @param  int $result_type
     * @param  object $result_class
     * @return object
     */
    public function getById($id, $result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder->where('id', $id)->get();
        $data = $this->getResultAs($query, $result_type, $result_class);

        if ($data)
            return $data[0];
        
        return null;
    }
    
    /**
     * insert
     *
     * @param  object $data
     * @param  int $result_type
     * @param  object $result_class
     * @return bool whether the insert was successful or not
     */
    public function insert($data,$result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        return $this->builder->insert($data);
    }
    
    /**
     * update
     *
     * @param  int $id
     * @param  object $data
     * @param  int $result_type
     * @param  object $result_class
     * @return bool whether the update was successful or not
     */
    public function update($id, $data,$result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        return $this->builder->where('id', $id)->update($data);
    }
    
    /**
     * delete
     * @param  int $id
     * @param  int $result_type
     * @param  object $result_class
     * @return boolean
     */
    public function delete($id,$result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        return $this->builder->where('id', $id)->delete();
    }

    /**
     * deleteRange
     * @param  array $ids
     * @param  int $result_type
     * @param  object $result_class
     * @return boolean
     */
    public function deleteRange($ids,$result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        return $this->builder->whereIn('id', $ids)->delete();
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