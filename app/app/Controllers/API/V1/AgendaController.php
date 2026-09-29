<?php

namespace App\Controllers\API\V1;

use DateTime;
use CodeIgniter\API\ResponseTrait;
use App\Repositories\EventRepository;

class AgendaController extends ApiController
{
    use ResponseTrait;

    /**
     * Maximum number of days that can be requested at once (a whole year: view "Année" of the agenda).
     */
    private const MAX_RANGE_DAYS = 366;

    private EventRepository $eventRepository;

    public function __construct()
    {
        parent::__construct();
        $this->eventRepository = service('repository', 'Event');
    }

    /**
     * Returns the events taking place between two dates.
     * GET api/v1/agenda?start=YYYY-MM-DD&end=YYYY-MM-DD
     */
    public function index()
    {
        $start = DateTime::createFromFormat('!Y-m-d', (string) $this->request->getGet('start'));
        $end = DateTime::createFromFormat('!Y-m-d', (string) $this->request->getGet('end'));

        if (!$start || !$end || $start->format('Y-m-d') !== $this->request->getGet('start') || $end->format('Y-m-d') !== $this->request->getGet('end'))
            return $this->error(400, 'Les paramètres start et end doivent être des dates au format YYYY-MM-DD.');

        if ($end < $start || $start->diff($end)->days > self::MAX_RANGE_DAYS)
            return $this->error(400, 'La période demandée est invalide (maximum ' . self::MAX_RANGE_DAYS . ' jours).');

        $events = $this->eventRepository->getBetween($start->format('Y-m-d'), $end->format('Y-m-d'));

        return $this->success($events);
    }

    /**
     * Returns one event.
     * GET api/v1/agenda/{id}
     */
    public function show($id)
    {
        $event = $this->eventRepository->getWithSections((int) $id);

        if ($event === null)
            return $this->error(404, 'Événement introuvable.');

        return $this->success($event);
    }
}
