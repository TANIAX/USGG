<?php

namespace App\Repositories;

/**
 * Ordered contents of the home page: questions of the FAQ (table faq) and testimonials (table testimonial).
 */
class ContentRepository extends BaseRepository
{
    public const TABLES = ['faq' => 'faq', 'temoignage' => 'testimonial'];

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('faq');
    }

    /**
     * @param string $type faq or temoignage
     * @param bool $activeOnly only the items shown on the site
     */
    public function getItems(string $type, bool $activeOnly = false)
    {
        $builder = $this->db->table(self::TABLES[$type])->orderBy('position', 'ASC')->orderBy('id', 'ASC');
        if ($activeOnly === true)
            $builder->where('is_active', true);

        $items = $builder->get()->getResultObject();
        foreach ($items as $item) {
            $item->id = (int) $item->id;
            $item->is_active = (bool) $item->is_active;
        }
        return $items;
    }

    public function get(string $type, int $id)
    {
        foreach ($this->getItems($type) as $item) {
            if ($item->id === $id)
                return $item;
        }
        return null;
    }

    public function save(string $type, ?int $id, array $data): int
    {
        $table = $this->db->table(self::TABLES[$type]);
        if ($id !== null) {
            $table->where('id', $id)->update($data);
            return $id;
        }

        $data['position'] = (int) $this->db->table(self::TABLES[$type])->selectMax('position')->get()->getRow()->position + 1;
        $table->insert($data + ['created_at' => date('Y-m-d H:i:s')]);
        return (int) $this->db->insertID();
    }

    public function remove(string $type, int $id): void
    {
        $this->db->table(self::TABLES[$type])->where('id', $id)->delete();
    }

    /**
     * Moves an item before / after its neighbour.
     */
    public function move(string $type, int $id, string $direction): void
    {
        $items = $this->getItems($type);
        $index = array_search($id, array_column($items, 'id'), true);
        $neighbour = $index === false ? null : ($items[$direction === 'up' ? $index - 1 : $index + 1] ?? null);
        if ($neighbour === null)
            return;

        [$items[$index], $items[$direction === 'up' ? $index - 1 : $index + 1]] = [$neighbour, $items[$index]];
        foreach (array_values($items) as $position => $item) {
            $this->db->table(self::TABLES[$type])->where('id', $item->id)->update(['position' => $position]);
        }
    }
}
