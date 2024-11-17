<?php

namespace App\Repositories;

use App\Helpers\FileHelper;
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
     * getLast3News
     *
     * @param  int $result_type
     * @param  object $result_class
     * @return array of objects
     */
    public function getLast3News($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder
                    ->select('id, title, content, picture, slug, category_id as category, user_id as author , created_at')
                    ->limit(3)
                    ->orderBy('created_at', 'DESC')
                    ->get();
        $news = $this->getResultAs($query, $result_type, $result_class);

        $this->processNewsItems($news);

        return $news;
    }

    /**
     * Get all news items created in a specific year.
     *
     * @param int $year The year to filter the news items by.
     * @param int $result_type (optional) The type of result to return. Defaults to self::RESULT_AS_OBJECT.
     * @param string|null $result_class (optional) The class to use for the result if $result_type is self::RESULT_AS_CLASS.
     * @return array|object The news items created in the specified year.
     */
    public function getAllByYearCreation($year, $result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder
                    ->select('id, title, content, picture, slug, category_id as category, user_id as author, created_at')
                    ->like('created_at', $year, 'after')
                    ->orderBy('created_at', 'DESC')
                    ->get();
        $news = $this->getResultAs($query, $result_type, $result_class);

        $this->processNewsItems($news);

        return $news;
    }

    public function getAllYearsThatHaveNews($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder
                    ->select('SUBSTRING(created_at,1,4) as id')
                    ->distinct()
                    ->groupBy('id')
                    ->orderBy('id', 'DESC')
                    ->get();

       return $this->getResultAs($query,$result_type,$result_class);
    }

    /**
     * Process the news items by fetching additional information and setting default values.
     *
     * @param array $news The array of news items to process.
     * @return void
     */
    private function processNewsItems($news)
    {
        foreach ($news as $item) {
            $item->category = $this->categoryRepository->getById($item->category);
            $item->author = $this->userRepository->getById($item->author);
            $item->author->user_type = $this->userTypeRepository->getById($item->author->user_type_id);

            // If the item has a picture, we get it from the server otherwise we set a default picture
            if ($item->picture && file_exists(FileHelper::NEWS_PICTURE_DIRECTORY . $item->picture))
                $item->picture = base_url(FileHelper::NEWS_PICTURE_DIRECTORY . $item->picture);
            else
                $item->picture = base_url(FileHelper::DEFAULT_NEWS_PICTURE);

            // If the author has a picture, we get it from the server otherwise we set a default picture
            if ($item->author->picture && file_exists(FileHelper::PROFIL_PICTURE_DIRECTORY . $item->author->picture))
                $item->author->picture = base_url(FileHelper::PROFIL_PICTURE_DIRECTORY . $item->author->picture);
            else
                $item->author->picture = base_url(FileHelper::DEFAULT_PROFIL_PICTURE);
        }
    }
}

