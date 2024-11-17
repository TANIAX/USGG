<?php

namespace App\Repositories;

use App\Helpers\FileHelper;
use App\Repositories\BaseRepository;

/**
 * FileRepository class represents a repository for the file model.
 * It implements the IRepository interface and extends the BaseRepository class.
 * It provides methods to interact with the news table in the database.
 *
 * @author Guillaume Cornez
 */
class FileRepository extends BaseRepository
{

    //Constants
    public const FILE_TYPE_GUIDE = 'GUIDE';
    public const FILE_TYPE_SCOUTE = 'SCOUTE';
    public const FILE_TYPES = [
        self::FILE_TYPE_GUIDE,
        self::FILE_TYPE_SCOUTE
    ];

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('file');
    }
    
    /**
     * getLast3News
     *
     * @param  int $result_type
     * @param  string $result_class
     * @return array of objects
     */
    public function getAllFor($file_type, $result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder
                    ->select('id, name, created_at')
                    ->where('file_type', $file_type)
                    ->where('exists', true)
                    ->orderBy('created_at', 'DESC')
                    ->get();
        return $this->getResultAs($query, $result_type, $result_class);
    }
}

