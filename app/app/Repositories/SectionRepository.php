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
                    ->select('id, name, slug, branch, color, logo, ages')
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

    /**
     * Active sections of a branch (GUIDE / SCOUTE) with their presentation texts, in their display order.
     */
    public function getPresentations(string $branch)
    {
        return $this->db->table('section')
                    ->select('id, name, slug, branch, color, logo, ages, title, group_name, description')
                    ->where('branch', $branch)
                    ->where('exists', true)
                    ->orderBy('position', 'ASC')
                    ->get()
                    ->getResultObject();
    }

    /**
     * All the sections, the inactive ones included (administration).
     */
    public function getAllForAdmin()
    {
        $sections = $this->db->table('section')->orderBy('position', 'ASC')->orderBy('id', 'ASC')->get()->getResultObject();
        foreach ($sections as $section) {
            $section->id = (int) $section->id;
            $section->position = (int) $section->position;
            $section->exists = (bool) $section->exists;
        }
        return $sections;
    }

    public function get(int $id)
    {
        foreach ($this->getAllForAdmin() as $section) {
            if ($section->id === $id)
                return $section;
        }
        return null;
    }

    public function updateSection(int $id, array $data): void
    {
        $this->db->table('section')->where('id', $id)->update($data + ['updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Moves a section before / after its neighbour of the same branch (swap of the positions).
     */
    public function move(int $id, string $direction): bool
    {
        $sections = $this->getAllForAdmin();
        $section = $this->get($id);
        if ($section === null)
            return false;

        $siblings = array_values(array_filter($sections, fn($other) => $other->branch === $section->branch));
        $index = array_search($id, array_column($siblings, 'id'), true);
        $neighbour = $siblings[$direction === 'up' ? $index - 1 : $index + 1] ?? null;
        if ($neighbour === null)
            return false;

        // Positions made unique first (the initial positions may be equal)
        foreach ($sections as $position => $other) {
            $this->db->table('section')->where('id', $other->id)->update(['position' => $position]);
        }
        $positions = array_flip(array_column($sections, 'id'));
        $this->db->table('section')->where('id', $section->id)->update(['position' => $positions[$neighbour->id]]);
        $this->db->table('section')->where('id', $neighbour->id)->update(['position' => $positions[$section->id]]);
        return true;
    }
}
