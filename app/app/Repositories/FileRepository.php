<?php

namespace App\Repositories;

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
    
    private const FIELDS = 'id, name, path, file_type, is_active, stored_name, mime_type, size, created_at, updated_at';

    /**
     * Active documents of a type, for the public pages.
     *
     * @return array of objects
     */
    public function getPublicFor(string $fileType)
    {
        return $this->cast($this->builder
                    ->select(self::FIELDS)
                    ->where('file_type', $fileType)
                    ->where('exists', true)
                    ->where('is_active', true)
                    ->orderBy('name', 'ASC')
                    ->get()
                    ->getResultObject());
    }

    /**
     * All the documents (active or not) of the given types, for the administration.
     *
     * @return array of objects
     */
    public function getForAdmin(array $fileTypes)
    {
        if (!$fileTypes)
            return [];

        return $this->cast($this->builder
                    ->select(self::FIELDS)
                    ->whereIn('file_type', $fileTypes)
                    ->where('exists', true)
                    ->orderBy('created_at', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getResultObject());
    }

    /**
     * @return object|null
     */
    public function getDocument(int $id)
    {
        return $this->cast($this->builder
                    ->select(self::FIELDS)
                    ->where('id', $id)
                    ->where('exists', true)
                    ->get()
                    ->getResultObject())[0] ?? null;
    }

    /**
     * @return array of objects
     */
    public function getDocuments(array $ids)
    {
        if (!$ids)
            return [];

        return $this->cast($this->builder
                    ->select(self::FIELDS)
                    ->whereIn('id', $ids)
                    ->where('exists', true)
                    ->get()
                    ->getResultObject());
    }

    public function create(array $data)
    {
        $this->builder->insert($data + ['exists' => true, 'created_at' => date('Y-m-d H:i:s')]);
        return (int) $this->db->insertID();
    }

    public function updateDocument(int $id, array $data)
    {
        return $this->builder->where('id', $id)->update($data + ['updated_at' => date('Y-m-d H:i:s')]);
    }

    public function deleteDocument(int $id)
    {
        return $this->builder->where('id', $id)->delete();
    }

    private function cast(array $documents)
    {
        foreach ($documents as $document) {
            $document->id = (int) $document->id;
            $document->is_active = (bool) $document->is_active;
            $document->size = $document->size === null ? null : (int) $document->size;
            $document->extension = strtolower(pathinfo($document->name, PATHINFO_EXTENSION));
        }
        return $documents;
    }
}
