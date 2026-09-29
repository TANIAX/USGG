<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

/**
 * PhotoRepository class represents a repository for the photo model (photo gallery).
 *
 * @author Guillaume Cornez
 */
class PhotoRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('photo');
    }

    /**
     * Gets the photos of an album, in their display order.
     *
     * @param  int $albumId
     * @param  bool $withPrivatePhotos
     * @return array of objects
     */
    public function getForAlbum(int $albumId, bool $withPrivatePhotos)
    {
        $builder = $this->builder
                    ->select('id, width, height, is_public')
                    ->where('album_id', $albumId);

        if (!$withPrivatePhotos)
            $builder->where('is_public', true);

        $photos = $builder->orderBy('position', 'ASC')->orderBy('id', 'ASC')->get()->getResultObject();

        foreach ($photos as $photo) {
            $photo->id = (int) $photo->id;
            $photo->width = (int) $photo->width;
            $photo->height = (int) $photo->height;
            $photo->is_public = (bool) $photo->is_public;
        }

        return $photos;
    }

    /**
     * Gets a photo with the branch of its album.
     *
     * @param  int $id
     * @return object|null
     */
    public function getPhoto(int $id)
    {
        $photo = $this->builder
                    ->select('photo.id, photo.album_id, photo.filename, photo.is_public, album.branch')
                    ->join('album', 'album.id = photo.album_id')
                    ->where('photo.id', $id)
                    ->get()
                    ->getRowObject();

        if ($photo) {
            $photo->id = (int) $photo->id;
            $photo->album_id = (int) $photo->album_id;
            $photo->is_public = (bool) $photo->is_public;
        }

        return $photo;
    }

    /**
     * Adds a photo at the end of an album.
     *
     * @return int The id of the photo
     */
    public function add(array $data)
    {
        $position = $this->builder->selectMax('position')->where('album_id', $data['album_id'])->get()->getRow()->position;
        $this->builder->insert($data + [
            'position' => $position === null ? 0 : $position + 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->insertID();
    }

    public function setVisibility(int $id, bool $isPublic)
    {
        return $this->builder->where('id', $id)->update(['is_public' => $isPublic]);
    }

    /**
     * Gets the photos of an album among the given ids (ids of other albums are ignored).
     *
     * @return array of objects (id, filename)
     */
    public function getInAlbum(int $albumId, array $ids)
    {
        if (!$ids)
            return [];

        return $this->builder
                    ->select('id, filename')
                    ->where('album_id', $albumId)
                    ->whereIn('id', $ids)
                    ->get()
                    ->getResultObject();
    }

    public function setVisibilityForIds(array $ids, bool $isPublic)
    {
        return $ids ? $this->builder->whereIn('id', $ids)->update(['is_public' => $isPublic]) : true;
    }

    public function deleteIds(array $ids)
    {
        return $ids ? $this->builder->whereIn('id', $ids)->delete() : true;
    }

    public function deletePhoto(int $id)
    {
        return $this->builder->where('id', $id)->delete();
    }
}
