<?php

namespace App\Repositories;

use App\Helpers\FileHelper;
use App\Repositories\BaseRepository;

/**
 * Leaders of the sections (portraits of the home page): a user account linked to a section.
 *
 * @author Guillaume Cornez
 */
class SectionLeaderRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('section_leader');
    }

    /**
     * Gets the leaders in their display order (order of the sections, then order in the section).
     *
     * @return array of objects
     */
    public function getLeaders()
    {
        $leaders = $this->builder
                    ->select('section_leader.id, section_leader.position, section_leader.user_id, section_leader.user_type_id')
                    ->select('user.email, user.totem, user.firstname, user.name, user.phone, user.picture')
                    ->select('user_type.name AS function')
                    ->select('section.id AS section_id, section.name AS section_name, section.slug AS section_slug, section.color AS section_color, section.logo AS section_logo')
                    ->join('user', 'user.id = section_leader.user_id')
                    ->join('section', 'section.id = section_leader.section_id')
                    ->join('user_type', 'user_type.id = section_leader.user_type_id', 'left')
                    ->where('user.exists', true)
                    ->where('section.exists', true)
                    ->orderBy('section.position', 'ASC')
                    ->orderBy('section_leader.position', 'ASC')
                    ->orderBy('section_leader.id', 'ASC')
                    ->get()
                    ->getResultObject();

        foreach ($leaders as $leader) {
            foreach (['id', 'position', 'user_id', 'user_type_id', 'section_id'] as $field) {
                $leader->$field = (int) $leader->$field;
            }
            $leader->picture_url = self::pictureUrl($leader->picture);
            $leader->display_name = $leader->totem ?: $leader->firstname;
        }

        return $leaders;
    }

    public function getLeader(int $id)
    {
        foreach ($this->getLeaders() as $leader) {
            if ($leader->id === $id)
                return $leader;
        }
        return null;
    }

    public function isLeader(int $userId, int $sectionId, ?int $exceptId = null)
    {
        $builder = $this->builder->where('user_id', $userId)->where('section_id', $sectionId);
        if ($exceptId !== null)
            $builder->where('id !=', $exceptId);
        return $builder->countAllResults() > 0;
    }

    /**
     * Adds a leader at the end of a section, with their function in this section.
     */
    public function add(int $userId, int $sectionId, int $userTypeId)
    {
        $this->builder->insert([
            'user_id' => $userId,
            'section_id' => $sectionId,
            'user_type_id' => $userTypeId,
            'position' => $this->nextPosition($sectionId),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return (int) $this->db->insertID();
    }

    /**
     * Changes the function of a leader, and their section (at the end of it) if it changes.
     */
    public function updateLeader(int $id, int $sectionId, int $userTypeId, bool $sectionChanged)
    {
        $data = ['user_type_id' => $userTypeId];
        if ($sectionChanged)
            $data += ['section_id' => $sectionId, 'position' => $this->nextPosition($sectionId)];
        return $this->builder->where('id', $id)->update($data);
    }

    /**
     * Swaps a leader with the previous (-1) or next (+1) leader of the same section.
     */
    public function move(int $id, int $direction)
    {
        $leader = $this->builder->where('id', $id)->get()->getRowObject();
        if (!$leader)
            return false;

        $leaders = $this->builder->select('id')->where('section_id', $leader->section_id)
                        ->orderBy('position', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray();
        $ids = array_map('intval', array_column($leaders, 'id'));
        $index = array_search($id, $ids, true);
        $target = $index + ($direction < 0 ? -1 : 1);
        if ($index === false || !isset($ids[$target]))
            return false;

        [$ids[$index], $ids[$target]] = [$ids[$target], $ids[$index]];
        foreach ($ids as $position => $leaderId) {
            $this->builder->where('id', $leaderId)->update(['position' => $position]);
        }
        return true;
    }

    public function remove(int $id)
    {
        return $this->builder->where('id', $id)->delete();
    }

    public static function pictureUrl(?string $picture)
    {
        if ($picture && is_file(ROOTPATH . 'public' . DIRECTORY_SEPARATOR . FileHelper::PROFIL_PICTURE_DIRECTORY . $picture))
            return base_url(FileHelper::PROFIL_PICTURE_DIRECTORY . $picture);
        return null;
    }

    private function nextPosition(int $sectionId)
    {
        $max = $this->db->table('section_leader')->selectMax('position')->where('section_id', $sectionId)->get()->getRow()->position;
        return $max === null ? 0 : $max + 1;
    }
}
