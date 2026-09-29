<?php

namespace App\Repositories;

use App\Helpers\FileHelper;
use App\Repositories\BaseRepository;

/**
 * EventRepository class represents a repository for the event model (agenda).
 * An event is linked to one or several sections through the event_section table.
 * The upcoming events are also the news of the home page.
 *
 * @author Guillaume Cornez
 */
class EventRepository extends BaseRepository
{
    /**
     * Directory (in public/) of the images of the events.
     */
    public const IMAGE_DIRECTORY = 'uploads/events/';

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
        $events = $this->eventQuery()
                    ->where('event.start_at <=', $end . ' 23:59:59')
                    ->where('event.end_at >=', $start . ' 00:00:00')
                    ->orderBy('event.start_at', 'ASC')
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
        $events = $this->eventQuery()
                    ->where($upcoming ? 'event.end_at >=' : 'event.end_at <', date('Y-m-d H:i:s'))
                    ->orderBy('event.start_at', $upcoming ? 'ASC' : 'DESC')
                    ->get()
                    ->getResultObject();

        return $this->attachSections($events);
    }

    /**
     * Gets a page of the upcoming events (news of the home page), the soonest first.
     *
     * @param  int $offset
     * @param  int $limit
     * @return array ['events' => array of objects, 'has_more' => bool]
     */
    public function getUpcoming(int $offset, int $limit)
    {
        // One more event is read to know if there is a next page
        $events = $this->eventQuery()
                    ->where('event.end_at >=', date('Y-m-d H:i:s'))
                    ->orderBy('event.start_at', 'ASC')
                    ->orderBy('event.id', 'ASC')
                    ->limit($limit + 1, $offset)
                    ->get()
                    ->getResultObject();

        return [
            'events' => $this->attachSections(array_slice($events, 0, $limit)),
            'has_more' => count($events) > $limit,
        ];
    }

    /**
     * Gets one event with its sections.
     *
     * @param  int $id
     * @return object|null
     */
    public function getWithSections(int $id)
    {
        $events = $this->eventQuery()
                    ->where('event.id', $id)
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
     * Path of the image of an event (full size or card size).
     */
    public static function getImagePath(string $image, bool $small = false)
    {
        return ROOTPATH . 'public' . DIRECTORY_SEPARATOR . self::IMAGE_DIRECTORY . $image . ($small ? '-small' : '') . '.jpg';
    }

    public static function deleteImageFiles(?string $image)
    {
        if (!$image)
            return;

        foreach ([false, true] as $small) {
            if (is_file(self::getImagePath($image, $small)))
                unlink(self::getImagePath($image, $small));
        }
    }

    /**
     * Base query: the events that exist, with their author.
     */
    private function eventQuery()
    {
        return $this->builder
                    ->select('event.id, event.title, event.description, event.location, event.start_at, event.end_at, event.all_day, event.registration_url, event.image')
                    ->select('user.totem AS author_totem, user.firstname AS author_firstname, user.picture AS author_picture')
                    ->join('user', 'user.id = event.user_id', 'left')
                    ->where('event.exists', true);
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
     * Adds the sections (one query for all the events), the urls of the image and the author to each event.
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

            $hasImage = $event->image && is_file(self::getImagePath($event->image));
            $event->image_url = $hasImage ? base_url(self::IMAGE_DIRECTORY . $event->image . '.jpg') : null;
            $event->image_small_url = $hasImage ? base_url(self::IMAGE_DIRECTORY . $event->image . '-small.jpg') : null;

            // The events created before the author was recorded are signed by the unit
            $hasPicture = $event->author_picture && is_file(ROOTPATH . 'public' . DIRECTORY_SEPARATOR . FileHelper::PROFIL_PICTURE_DIRECTORY . $event->author_picture);
            $event->author = [
                'name' => $event->author_totem ?: ($event->author_firstname ?: 'L\'équipe d\'unité'),
                'picture_url' => $hasPicture ? base_url(FileHelper::PROFIL_PICTURE_DIRECTORY . $event->author_picture) : null,
            ];
            unset($event->author_totem, $event->author_firstname, $event->author_picture);
        }

        return $events;
    }
}
