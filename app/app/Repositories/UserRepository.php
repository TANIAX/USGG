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
    public function getAllMainLeaders($result_type = self::RESULT_AS_OBJECT, $result_class = null)
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

    /**
     * Finds an account by e-mail (case insensitive), including the deactivated accounts.
     *
     * @return object|null
     */
    public function findAnyByEmail(string $email)
    {
        return $this->builder
                    ->select('id, email, totem, firstname, name, phone, picture, user_type_id, exists')
                    ->where('LOWER(email)', strtolower(trim($email)))
                    ->get()
                    ->getRowObject();
    }

    /**
     * Creates an account with the given role (by name).
     *
     * @return int The id of the account
     */
    public function createAccount(array $data, string $roleName = 'user')
    {
        $now = date('Y-m-d H:i:s');
        $this->db->transStart();
        $this->builder->insert($data + ['exists' => true, 'created_at' => $now]);
        $id = (int) $this->db->insertID();

        $role = $this->db->table('role')->select('id')->where('name', $roleName)->get()->getRowObject();
        if ($role)
            $this->db->table('user_role')->insert(['user_id' => $id, 'role_id' => $role->id, 'exists' => true, 'created_at' => $now]);
        $this->db->transComplete();

        return $id;
    }

    /**
     * Updates the profile of an account (the e-mail, which identifies the account, is not changed here).
     */
    public function updateProfile(int $id, array $data)
    {
        unset($data['email'], $data['password']);
        return $this->builder->where('id', $id)->update($data + ['updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Functions (user types) without duplicates, by name.
     *
     * @return array of objects (id, name)
     */
    public function getUserTypes()
    {
        $types = [];
        foreach ($this->db->table('user_type')->select('id, name')->where('exists', true)->orderBy('id')->get()->getResultObject() as $type) {
            $types[$type->name] = $types[$type->name] ?? (object) ['id' => (int) $type->id, 'name' => $type->name];
        }
        return array_values($types);
    }
}
