<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\EventRepository;

/**
 * Pages of the ASBL (non-profit organisation managing the premises and the unit's events).
 */
class AsblController extends BaseController
{
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->eventRepository = service('repository', 'Event');
    }

    public function index()
    {
        return view('pages/asbl/index');
    }

    /**
     * Events of the whole unit: the upcoming agenda events linked to the "Unité" section.
     */
    public function events()
    {
        $events = array_values(array_filter(
            $this->eventRepository->getByPeriod(true),
            fn($event) => in_array('unite', array_column($event->sections, 'slug'), true)
        ));

        return view('pages/asbl/events', ['events' => array_slice($events, 0, 6)]);
    }
}
