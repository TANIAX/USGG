<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\EventRepository;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Page of a news, i.e. of an event of the agenda.
 */
class NewsController extends BaseController
{
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->eventRepository = service('repository', 'Event');
    }

    public function show($id)
    {
        $event = $this->eventRepository->getWithSections((int) $id);
        if ($event === null)
            throw PageNotFoundException::forPageNotFound();

        return view('pages/news/show', [
            'event' => $event,
            'eventJson' => $this->toJson($event),
        ]);
    }
}
