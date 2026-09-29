<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\EventRepository;
use App\Controllers\API\V1\NewsController;
use App\Repositories\SectionLeaderRepository;

/**
 * This class represents the Home Controller that handles the landing page and othet stuff.
 * It retrieves the section leaders and the first upcoming events of the agenda (news)
 * and passes them to the welcome_message view.
 *
 * @author   Guillaume cornez
 */
class HomeController extends BaseController
{
    private SectionLeaderRepository $leaderRepository;
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->leaderRepository = service('Repository', 'SectionLeader');
        $this->eventRepository = service('Repository', 'Event');
    }

    /**
     * 
     * This method returns the landing page
     *
     * @author Guillaume cornez
     */
    public function index()
    {
        // Section leaders, managed in admin/responsables
        $leaders = $this->leaderRepository->getLeaders();
        // News = upcoming events of the agenda, the next ones are loaded 3 by 3 (api/v1/actualites)
        $news = $this->eventRepository->getUpcoming(0, NewsController::PAGE_SIZE);

        return view('pages/welcome_message', [
            'leaders' => $leaders,
            'news' => $this->toJson(['items' => $news['events'], 'has_more' => $news['has_more']]),
        ]);
    }

    public function contact()
    {
        return view('pages/contact');
    }
}
