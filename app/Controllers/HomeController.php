<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\DTO\Response\User\UserListResponseDTO;

class HomeController extends BaseController
{
    private $userRepository;

    public function index()
    {
        return view('welcome_message');
    }
}
