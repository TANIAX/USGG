<?php

namespace App\Repositories;

/**
 * Messages of the contact form (table contact_message).
 */
class ContactMessageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('contact_message');
    }

    public function create(array $data): int
    {
        $this->db->table('contact_message')->insert($data);
        return (int) $this->db->insertID();
    }

    /**
     * All the messages, the most recent first.
     */
    public function getAllRecent(): array
    {
        $rows = $this->db->table('contact_message')->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->get()->getResultObject();
        foreach ($rows as $row) {
            $row->id = (int) $row->id;
            $row->is_read = (bool) $row->is_read;
            $row->is_handled = (bool) $row->is_handled;
        }
        return $rows;
    }

    public function updateMessages(array $ids, array $data): void
    {
        if ($ids)
            $this->db->table('contact_message')->whereIn('id', $ids)->update($data);
    }

    public function deleteMessages(array $ids): void
    {
        if ($ids)
            $this->db->table('contact_message')->whereIn('id', $ids)->delete();
    }

    public function countUnread(): int
    {
        return $this->db->table('contact_message')->where('is_read', false)->countAllResults();
    }
}
