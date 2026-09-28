<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

/**
 * AlbumRepository class represents a repository for the album model (photo gallery).
 * The number of photos and the cover of an album depend on who is looking at it:
 * without an account, only the public photos are taken into account.
 *
 * @author Guillaume Cornez
 */
class AlbumRepository extends BaseRepository
{
    /**
     * Number of photos shown in turn on the cover of an album.
     */
    public const COVER_PHOTOS = 3;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('album');
    }

    /**
     * Gets the albums of the given branches with their number of photos and their cover.
     *
     * @param  array $branches
     * @param  bool $withPrivatePhotos Count the private photos
     * @param  bool $onlyNotEmpty Hide the albums without (visible) photo
     * @return array of objects
     */
    public function getAlbums(array $branches, bool $withPrivatePhotos, bool $onlyNotEmpty = true)
    {
        if (!$branches)
            return [];

        $visibility = $withPrivatePhotos ? '' : ' AND photo.is_public = 1';

        $albums = $this->builder
                    ->select('album.id, album.title, album.description, album.branch, album.album_date, album.created_at')
                    ->select('(SELECT COUNT(*) FROM photo WHERE photo.album_id = album.id' . $visibility . ') AS photo_count', false)
                    ->select('(SELECT COUNT(*) FROM photo WHERE photo.album_id = album.id AND photo.is_public = 0) AS private_count', false)
                    ->whereIn('album.branch', $branches)
                    ->orderBy('album.album_date IS NULL', 'ASC', false)
                    ->orderBy('album.album_date', 'DESC')
                    ->orderBy('album.id', 'DESC')
                    ->get()
                    ->getResultObject();

        $albums = array_map([$this, 'castAlbum'], $albums);
        $coverIds = $this->getCoverIds(array_column($albums, 'id'), $withPrivatePhotos);
        foreach ($albums as $album) {
            $album->cover_ids = $coverIds[$album->id] ?? [];
        }

        if ($onlyNotEmpty)
            $albums = array_values(array_filter($albums, fn($album) => $album->photo_count > 0));

        return $albums;
    }

    /**
     * Gets one album.
     *
     * @param  int $id
     * @return object|null
     */
    public function getAlbum(int $id)
    {
        $album = $this->builder
                    ->select('id, title, description, branch, album_date, created_at')
                    ->where('id', $id)
                    ->get()
                    ->getRowObject();

        return $album ? $this->castAlbum($album) : null;
    }

    public function create(array $data)
    {
        $this->builder->insert($data + ['created_at' => date('Y-m-d H:i:s')]);
        return (int) $this->db->insertID();
    }

    public function updateAlbum(int $id, array $data)
    {
        return $this->builder->where('id', $id)->update($data + ['updated_at' => date('Y-m-d H:i:s')]);
    }

    public function deleteAlbum(int $id)
    {
        $this->db->table('photo')->where('album_id', $id)->delete();
        return $this->builder->where('id', $id)->delete();
    }

    /**
     * Picks the photos of the cover of each album: spread over the album (beginning, middle, end)
     * rather than the first ones, which are often very similar.
     *
     * @param  array $albumIds
     * @param  bool $withPrivatePhotos
     * @return array [album id => [photo ids]]
     */
    private function getCoverIds(array $albumIds, bool $withPrivatePhotos)
    {
        if (!$albumIds)
            return [];

        $builder = $this->db->table('photo')->select('id, album_id')->whereIn('album_id', $albumIds);
        if (!$withPrivatePhotos)
            $builder->where('is_public', true);

        $photosByAlbum = [];
        foreach ($builder->orderBy('album_id')->orderBy('position')->orderBy('id')->get()->getResultArray() as $photo) {
            $photosByAlbum[$photo['album_id']][] = (int) $photo['id'];
        }

        $coverIds = [];
        foreach ($photosByAlbum as $albumId => $photoIds) {
            $count = count($photoIds);
            $picked = min($count, self::COVER_PHOTOS);
            for ($i = 0; $i < $picked; $i++) {
                $coverIds[$albumId][] = $photoIds[intdiv($i * $count, $picked)];
            }
        }

        return $coverIds;
    }

    private function castAlbum($album)
    {
        $album->id = (int) $album->id;
        foreach (['photo_count', 'private_count'] as $field) {
            if (property_exists($album, $field))
                $album->$field = $album->$field === null ? null : (int) $album->$field;
        }
        return $album;
    }
}
