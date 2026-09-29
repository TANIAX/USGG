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

    /**
     * Functions, with the number of accounts and of leaders using them.
     */
    public function getAllWithUsage(): array
    {
        $types = $this->db->table('user_type')->select('id, name')->where('exists', true)->orderBy('name', 'ASC')->get()->getResultObject();
        foreach ($types as $type) {
            $type->id = (int) $type->id;
            $type->users = $this->db->table('user')->where('user_type_id', $type->id)->countAllResults();
            $type->leaders = $this->db->table('section_leader')->where('user_type_id', $type->id)->countAllResults();
        }
        return $types;
    }

    public function findByName(string $name, ?int $exceptId = null)
    {
        $builder = $this->db->table('user_type')->where('exists', true)->where('LOWER(name)', mb_strtolower($name));
        if ($exceptId !== null)
            $builder->where('id !=', $exceptId);
        return $builder->get()->getRowObject();
    }

    public function create(string $name): int
    {
        $this->db->table('user_type')->insert(['name' => $name]);
        return (int) $this->db->insertID();
    }

    public function rename(int $id, string $name): void
    {
        $this->db->table('user_type')->where('id', $id)->update(['name' => $name]);
    }

    /**
     * Moves the accounts and leaders of a function to another one, then deletes it.
     */
    public function merge(int $id, int $targetId): void
    {
        $this->db->transStart();
        $this->db->table('user')->where('user_type_id', $id)->update(['user_type_id' => $targetId]);
        $this->db->table('section_leader')->where('user_type_id', $id)->update(['user_type_id' => $targetId]);
        $this->db->table('user_type')->where('id', $id)->delete();
        $this->db->transComplete();
    }

    public function remove(int $id): void
    {
        $this->db->table('user_type')->where('id', $id)->delete();
    }
}
