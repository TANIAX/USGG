<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\DTO\Response\User\UserListResponseDTO;
use App\Repositories\EventRepository;
use App\Controllers\API\V1\NewsController;
use App\Repositories\UserRepository;

/**
 * This class represents the Home Controller that handles the landing page and othet stuff.
 * It retrieves the main leaders and the first upcoming events of the agenda (news)
 * and passes them to the welcome_message view.
 *
 * @author   Guillaume cornez
 */
class HomeController extends BaseController
{
    private UserRepository $userRepository;
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->userRepository = service('Repository', 'User');
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
        $users = $this->userRepository->getAllMainLeaders(BaseRepository::RESULT_AS_CUSTOM, UserListResponseDTO::class);
        // News = upcoming events of the agenda, the next ones are loaded 3 by 3 (api/v1/actualites)
        $news = $this->eventRepository->getUpcoming(0, NewsController::PAGE_SIZE);

        return view('pages/welcome_message', [
            'users' => $users,
            'news' => json_encode(['items' => $news['events'], 'has_more' => $news['has_more']], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
        ]);
    }

    public function contact()
    {
        return view('pages/contact');
    }
}
