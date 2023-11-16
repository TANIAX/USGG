<?php

namespace App\Repositories;

use App\Entities\Role;
use App\Helpers\FileHelper;
use Config\Database;
use App\Interfaces\IRepository;
use App\Repositories\BaseRepository;

/**
 * NewsRepository class represents a repository for the news model.
 * It implements the IRepository interface and extends the BaseRepository class.
 * It provides methods to interact with the news table in the database.
 *
 * @author Guillaume Cornez
 */
class NewsRepository extends BaseRepository
{
    private $categoryRepository;
    private $userRepository;
    private $userTypeRepository;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('news');
        $this->categoryRepository = service('Repository', 'Category');
        $this->userRepository = service('Repository', 'User');
        $this->userTypeRepository = service('Repository', 'UserType');
    }
    
    /**
     * GetLast3News
     *
     * @param  int $result_type
     * @param  object $result_class
     * @return array of objects
     */
    public function GetLast3News($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder
                    ->select('id, title, content, picture, category_id as category, user_id as author, created_at')
                    ->limit(3)
                    ->orderBy('created_at', 'DESC')
                    ->get();
        $news = $this->getResultAs($query, $result_type, $result_class);

        foreach ($news as $item) {
            $item->category = $this->categoryRepository->getById($item->category);
            $item->author = $this->userRepository->getById($item->author);
            $item->author->user_type = $this->userTypeRepository->getById($item->author->user_type_id);

            //If the item has a picture, we get it from the server otherwise we set a default picture
            if ($item->picture && file_exists(FileHelper::NEWS_PICTURE_DIRECTORY . $item->picture))
                $item->picture = base_url(FileHelper::NEWS_PICTURE_DIRECTORY . $item->picture);
            else
                $item->picture = base_url(FileHelper::DEFAULT_NEWS_PICTURE);

            //if the author has a picture, we get it from the server otherwise we set a default picture
            if ($item->author->picture && file_exists(FileHelper::PROFIL_PICTURE_DIRECTORY . $item->author->picture))
                $item->author->picture = base_url(FileHelper::PROFIL_PICTURE_DIRECTORY . $item->author->picture);
            else
                $item->author->picture = base_url(FileHelper::DEFAULT_PROFIL_PICTURE);
            
        }

        return $news;
    }
}
