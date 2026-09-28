<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

/**
 * EventRepository class represents a repository for the event model (agenda).
 * An event is linked to one or several sections through the event_section table.
 *
 * @author Guillaume Cornez
 */
class EventRepository extends BaseRepository
{
    private const EVENT_FIELDS = 'id, title, description, location, start_at, end_at, all_day, registration_url';

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('event');
    }

    /**
     * Gets the events (with their sections) taking place, even partially, between two dates.
     *
     * @param  string $start Y-m-d
     * @param  string $end Y-m-d
     * @return array of objects
     */
    public function getBetween(string $start, string $end)
    {
        $events = $this->builder
                    ->select(self::EVENT_FIELDS)
                    ->where('exists', true)
                    ->where('start_at <=', $end . ' 23:59:59')
                    ->where('end_at >=', $start . ' 00:00:00')
                    ->orderBy('start_at', 'ASC')
                    ->get()
                    ->getResultObject();

        return $this->attachSections($events);
    }

    /**
     * Gets the upcoming (not finished yet) or the past events with their sections.
     *
     * @param  bool $upcoming
     * @return array of objects
     */
    public function getByPeriod(bool $upcoming = true)
    {
        $events = $this->builder
                    ->select(self::EVENT_FIELDS)
                    ->where('exists', true)
                    ->where($upcoming ? 'end_at >=' : 'end_at <', date('Y-m-d H:i:s'))
                    ->orderBy('start_at', $upcoming ? 'ASC' : 'DESC')
                    ->get()
                    ->getResultObject();

        return $this->attachSections($events);
    }

    /**
     * Gets one event with its sections.
     *
     * @param  int $id
     * @return object|null
     */
    public function getWithSections(int $id)
    {
        $events = $this->builder
                    ->select(self::EVENT_FIELDS)
                    ->where('id', $id)
                    ->where('exists', true)
                    ->get()
                    ->getResultObject();

        return $this->attachSections($events)[0] ?? null;
    }

    /**
     * Creates an event and links it to the given sections.
     *
     * @param  array $data
     * @param  array $sectionIds
     * @return int The id of the created event
     */
    public function create(array $data, array $sectionIds)
    {
        $this->db->transStart();
        $this->builder->insert($data + ['created_at' => date('Y-m-d H:i:s')]);
        $id = (int) $this->db->insertID();
        $this->setSections($id, $sectionIds);
        $this->db->transComplete();

        return $id;
    }

    /**
     * Updates an event and replaces its sections.
     *
     * @param  int $id
     * @param  array $data
     * @param  array $sectionIds
     * @return bool
     */
    public function updateWithSections(int $id, array $data, array $sectionIds)
    {
        $this->db->transStart();
        $this->builder->where('id', $id)->update($data + ['updated_at' => date('Y-m-d H:i:s')]);
        $this->setSections($id, $sectionIds);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Replaces the sections linked to an event.
     *
     * @param  int $eventId
     * @param  array $sectionIds
     * @return void
     */
    private function setSections(int $eventId, array $sectionIds)
    {
        $this->db->table('event_section')->where('event_id', $eventId)->delete();

        $rows = array_map(fn($sectionId) => ['event_id' => $eventId, 'section_id' => (int) $sectionId], array_unique($sectionIds));
        if ($rows)
            $this->db->table('event_section')->insertBatch($rows);
    }

    /**
     * Adds a "sections" property to each event (one query for all the events).
     *
     * @param  array $events
     * @return array of objects
     */
    private function attachSections(array $events)
    {
        if (!$events)
            return [];

        $rows = $this->db->table('event_section')
                    ->select('event_section.event_id, section.id, section.name, section.slug, section.color, section.logo')
                    ->join('section', 'section.id = event_section.section_id')
                    ->whereIn('event_section.event_id', array_column($events, 'id'))
                    ->where('section.exists', true)
                    ->orderBy('section.position', 'ASC')
                    ->get()
                    ->getResultObject();

        $sectionsByEvent = [];
        foreach ($rows as $row) {
            $eventId = $row->event_id;
            unset($row->event_id);
            $row->id = (int) $row->id;
            $sectionsByEvent[$eventId][] = $row;
        }

        foreach ($events as $event) {
            $event->sections = $sectionsByEvent[$event->id] ?? [];
            $event->id = (int) $event->id;
            $event->all_day = (bool) $event->all_day;
        }

        return $events;
    }
}
