<?php

namespace App\Repositories;

/**
 * Pages written by the super admins: charter of the unit (charte) and privacy policy (confidentialite).
 */
class PageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('page');
    }

    /**
     * All the pages, with the name of the last person who changed them.
     */
    public function getAllForAdmin(): array
    {
        return $this->db->table('page')
                    ->select('page.id, page.slug, page.title, page.updated_at, user.totem AS updated_by_totem, user.firstname AS updated_by_firstname')
                    ->join('user', 'user.id = page.updated_by', 'left')
                    ->orderBy('page.id')
                    ->get()
                    ->getResultObject();
    }

    public function findBySlug(string $slug)
    {
        return $this->db->table('page')->where('slug', $slug)->get()->getRowObject();
    }

    public function updatePage(string $slug, array $data): void
    {
        $this->db->table('page')->where('slug', $slug)->update($data + ['updated_at' => date('Y-m-d H:i:s')]);
    }
}
