<?php

namespace App\Repositories;

/**
 * Registration requests (table registration).
 */
class RegistrationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('registration');
    }

    public function create(array $data): int
    {
        $this->db->table('registration')->insert($data);
        return (int) $this->db->insertID();
    }

    /**
     * Query of the requests of the given branches for a paginated list (App\Libraries\ListQuery), the most recent first.
     * $status: '' for all; $section: '' for all, 'none' for the requests without section, or the id of a section.
     */
    public function adminQuery(array $branches, string $status = '', string $section = '')
    {
        $builder = $this->select()
                    ->groupStart()
                        ->whereIn('section.branch', $branches ?: [''])
                        ->orWhere('registration.section_id', null)
                    ->groupEnd();
        if ($status !== '')
            $builder->where('registration.status', $status);
        if ($section === 'none')
            $builder->where('registration.section_id', null);
        elseif ($section !== '')
            $builder->where('registration.section_id', (int) $section);

        return $builder->orderBy('registration.created_at', 'DESC')->orderBy('registration.id', 'DESC');
    }

    /**
     * Number of requests of the given branches by status: [status => count]
     */
    public function countByStatus(array $branches): array
    {
        if (!$branches)
            return [];

        $rows = $this->db->table('registration')
                    ->select('registration.status, COUNT(*) AS total')
                    ->join('section', 'section.id = registration.section_id', 'left')
                    ->groupStart()
                        ->whereIn('section.branch', $branches)
                        ->orWhere('registration.section_id', null)
                    ->groupEnd()
                    ->groupBy('registration.status')
                    ->get()
                    ->getResultObject();

        return array_map('intval', array_column($rows, 'total', 'status'));
    }

    /**
     * Among the given ids, the ones of the requests of the given branches.
     */
    public function filterManageable(array $branches, array $ids): array
    {
        if (!$branches || !$ids)
            return [];

        $rows = $this->adminQuery($branches)->select('registration.id')->whereIn('registration.id', $ids)->get()->getResultObject();
        return array_map(fn($row) => (int) $row->id, $rows);
    }

    public function castRows(array $rows): array
    {
        return array_map([$this, 'castRow'], $rows);
    }

    public function get(int $id)
    {
        $row = $this->select()->where('registration.id', $id)->get()->getRowObject();
        return $row ? $this->castRow($row) : null;
    }

    public function updateRequest(int $id, array $data): void
    {
        $this->db->table('registration')->where('id', $id)->update($data + ['updated_at' => date('Y-m-d H:i:s')]);
    }

    public function deleteRequests(array $ids): void
    {
        if ($ids)
            $this->db->table('registration')->whereIn('id', $ids)->delete();
    }

    private function select()
    {
        return $this->db->table('registration')
                    ->select('registration.*, section.name AS section_name, section.color AS section_color, section.branch AS section_branch')
                    ->select('user.firstname AS updated_by_firstname, user.totem AS updated_by_totem')
                    ->join('section', 'section.id = registration.section_id', 'left')
                    ->join('user', 'user.id = registration.updated_by', 'left');
    }

    private function castRow($row)
    {
        $row->id = (int) $row->id;
        $row->section_id = $row->section_id !== null ? (int) $row->section_id : null;
        $row->updated_by_name = $row->updated_by_totem ?: $row->updated_by_firstname;
        unset($row->updated_by_totem, $row->updated_by_firstname);
        return $row;
    }
}
