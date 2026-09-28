<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

/**
 * SectionRepository class represents a repository for the section model.
 * It provides methods to interact with the section table in the database.
 *
 * @author Guillaume Cornez
 */
class SectionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('section');
    }

    /**
     * Gets all the sections in their display order.
     *
     * @param  int $result_type
     * @param  string $result_class
     * @return array of objects
     */
    public function getAllOrdered($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder
                    ->select('id, name, slug, branch, color, logo')
                    ->where('exists', true)
                    ->orderBy('position', 'ASC')
                    ->get();
        return $this->getResultAs($query, $result_type, $result_class);
    }

    /**
     * Gets the ids of all existing sections.
     *
     * @return array of int
     */
    public function getAllIds()
    {
        return array_map('intval', array_column($this->getAllOrdered(self::RESULT_AS_ARRAY), 'id'));
    }
}
