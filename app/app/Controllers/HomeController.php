<?php

namespace App\Controllers;

use SQLite3;
use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\DTO\Response\News\NewsListResponseDTO;
use App\DTO\Response\User\UserListResponseDTO;

/**
 * This class represents the Home Controller that handles the landing page and othet stuff.
 * It retrieves the main leaders and the last 3 news from their respective repositories
 * and passes them to the welcome_message view.
 *
 * @author   Guillaume cornez
 */
class HomeController extends BaseController
{
    private $userRepository;
    private $newsRepository;

    public function __construct()
    {
        $this->userRepository =  service('Repository','User');
        $this->newsRepository =  service('Repository','News');
    }

    /**
     * 
     * This method returns the landing page
     *
     * @author Guillaume cornez
     */
    public function index()
    {
        // phpinfo();die();
        //working
        // $db = new SQLite3(WRITEPATH."database/sample.db");
        // $result = $db->query("SELECT * FROM user");
        
        // while($data = $result->fetchArray()){

        //     echo $data['id']."<hr>";
        // }
        //       die();

            

        // Inserer ici les requêtes

        // Deconnexion
        $bd = null;

        $users = $this->userRepository->GetAllMainLeaders(BaseRepository::RESULT_AS_CUSTOM, UserListResponseDTO::class);
        $news = $this->newsRepository->GetLast3News(BaseRepository::RESULT_AS_CUSTOM, NewsListResponseDTO::class);

        return view('welcome_message',[
            'users' => $users,
            'news' => $news
        ]);
    }

    public function contact()
    {
        return view('contact',[
            'page_title' => 'Contact'
        ]);
    }
}
