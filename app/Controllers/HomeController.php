<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\DTO\Response\News\NewsListResponseDTO;
use App\Repositories\BaseRepository;
use App\DTO\Response\User\UserListResponseDTO;

class HomeController extends BaseController
{
    private $userRepository;
    private $newsRepository;

    public function __construct()
    {
        $this->userRepository =  service('Repository','User');
        $this->newsRepository =  service('Repository','News');
    }

    public function index()
    {
        $users = $this->userRepository->GetAllMainLeaders(BaseRepository::RESULT_AS_CUSTOM, UserListResponseDTO::class);
        $news = $this->newsRepository->GetLast3News(BaseRepository::RESULT_AS_CUSTOM, NewsListResponseDTO::class);

        return view('welcome_message', [
            'users' => $users,
            'news' => $news
        ]);
    }
}
