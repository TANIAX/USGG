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
     * Conditions of the filters of the list: todo (not handled), unread, handled, '' (all)
     */
    private const FILTERS = [
        'todo' => ['is_handled' => false],
        'unread' => ['is_read' => false],
        'handled' => ['is_handled' => true],
        '' => [],
    ];

    /**
     * Query of the messages of a filter (see FILTERS) for a paginated list, the most recent first.
     */
    public function adminQuery(string $filter)
    {
        return $this->db->table('contact_message')
                    ->where(self::FILTERS[$filter] ?? [])
                    ->orderBy('created_at', 'DESC')
                    ->orderBy('id', 'DESC');
    }

    /**
     * Number of messages of each filter: [filter => count]
     */
    public function countByFilter(): array
    {
        $counts = [];
        foreach (self::FILTERS as $filter => $conditions) {
            $counts[$filter] = $this->db->table('contact_message')->where($conditions)->countAllResults();
        }
        return $counts;
    }

    public static function cast($row)
    {
        $row->id = (int) $row->id;
        $row->is_read = (bool) $row->is_read;
        $row->is_handled = (bool) $row->is_handled;
        return $row;
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
