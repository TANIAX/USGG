<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\NewsRepository;
use App\DTO\Response\News\NewsListResponseDTO;


class ArticleController extends BaseController
{
    private NewsRepository $newsRepository;
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->newsRepository = new NewsRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    public function index($year = null)
    {
        if ($year == null)
            $year = date('Y', time());

        $news = $this->newsRepository->getAllByYearCreation($year, BaseRepository::RESULT_AS_CUSTOM, NewsListResponseDTO::class) ?? [];
        $years = $this->newsRepository->getAllYearsThatHaveNews() ?? [];

        foreach ($years as $y) {
            $y->isSelected = $y->id == $year;
        }

        return view('pages/admin/article/index', [
            'news' => json_encode($news),
            'years' => json_encode($years)
        ]);
    }

    public function create()
    {
        $categories = $this->categoryRepository->getAll() ?? [];
        return view('pages/admin/article/create', [
            'categories' => json_encode($categories)
        ]);

    }

    public function upload()
    {
        $dir_path = 'uploads/news/';
        try {
            //We check if the file is an image
            $check = getimagesize($_FILES["upload"]["tmp_name"]);
            if ($check == false) {
                echo "Le fichier n'est pas une image.";
            }

            //Make sure the file is not too big (max 5MB)
            if ($_FILES["upload"]["size"] > 5000000) {
                echo "Le fichier est trop volumineux. (Max 5MB)";
            }

            //Set the file path with a unique name
            $fileName =  uniqid() . '.' . pathinfo($_FILES["upload"]["name"], PATHINFO_EXTENSION);
            
            //test if folder news exists
            if (!file_exists($dir_path))
                mkdir($dir_path, 0777, true);

            //Move the file to the folder
            if (!move_uploaded_file($_FILES["upload"]["tmp_name"], $dir_path.$fileName)) {
                echo "Une erreur est survenue lors de l'upload du fichier.";
            } else {
                echo base_url() . '/' . $dir_path. $fileName;
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }



    }
}
