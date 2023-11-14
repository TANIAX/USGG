<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\DTO\Response\News\NewsListResponseDTO;
use App\Repositories\BaseRepository;
use App\DTO\Response\User\UserListResponseDTO;

/**
 * This class represents the Home Controller that handles the landing page and othet stuff.
 * It retrieves the main leaders and the last 3 news from their respective repositories
 * and passes them to the welcome_message view.
 *
 * @author   Guillaume cornez
 */
class AuthController extends BaseController
{
    private $userRepository;
    private $newsRepository;

    public function __construct()
    {
        $this->userRepository =  service('Repository','User');
        $this->newsRepository =  service('Repository','News');
    }

    public function login()
    {
        return view('auth/login');
    }
}
