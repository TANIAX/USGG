<?php

namespace App\Controllers;

use App\Helpers\GalleryHelper;
use App\Controllers\BaseController;
use App\Repositories\AlbumRepository;
use App\Repositories\PhotoRepository;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Public part of the photo gallery.
 * Photos can be public (visible by everyone) or private (visible by any logged in user).
 */
class GalleryController extends BaseController
{
    /**
     * Branch as written in the url => branch stored in the database
     */
    private const URL_BRANCHES = [
        'guide' => GalleryHelper::BRANCH_GUIDE,
        'scout' => GalleryHelper::BRANCH_SCOUT,
    ];

    private AlbumRepository $albumRepository;
    private PhotoRepository $photoRepository;

    public function __construct()
    {
        $this->albumRepository = service('repository', 'Album');
        $this->photoRepository = service('repository', 'Photo');
    }

    /**
     * Lists the albums, of every branch or of one branch (galerie/guide, galerie/scout).
     */
    public function index(?string $branch = null)
    {
        if ($branch !== null && !isset(self::URL_BRANCHES[$branch]))
            throw PageNotFoundException::forPageNotFound();

        $branches = $branch ? [self::URL_BRANCHES[$branch]] : array_keys(GalleryHelper::BRANCHES);
        $connected = GalleryHelper::canSeePrivatePhotos();

        $albums = $this->albumRepository->getAlbums($branches, $connected, false);

        return view('pages/gallery/index', [
            //Albums without visible photo are hidden
            'albums' => array_values(array_filter($albums, fn($album) => $album->photo_count > 0)),
            'hasHiddenPhotos' => !$connected && array_filter($albums, fn($album) => $album->private_count > 0),
            'branch' => $branch,
            'connected' => $connected,
        ]);
    }

    /**
     * Displays the photos of an album.
     */
    public function album($id)
    {
        $album = $this->albumRepository->getAlbum((int) $id);
        if ($album === null)
            throw PageNotFoundException::forPageNotFound();

        $connected = GalleryHelper::canSeePrivatePhotos();
        $photos = $this->photoRepository->getForAlbum($album->id, $connected);
        $hiddenCount = $connected ? 0 : count($this->photoRepository->getForAlbum($album->id, true)) - count($photos);

        //An album without visible photo does not exist for the visitor
        if (!$photos && !$hiddenCount)
            throw PageNotFoundException::forPageNotFound();

        return view('pages/gallery/album', [
            'album' => $album,
            'photos' => json_encode($photos),
            'hiddenCount' => $hiddenCount,
            'connected' => $connected,
            'branchUrl' => array_search($album->branch, self::URL_BRANCHES, true),
        ]);
    }

    /**
     * Sends a photo (or its thumbnail) if the visitor is allowed to see it.
     * The files are stored outside of the public folder, so this is the only way to reach them.
     */
    public function photo($id, ?string $size = null)
    {
        $photo = $this->photoRepository->getPhoto((int) $id);

        //A private photo is answered as missing to visitors without account, so that its existence is not revealed
        if ($photo === null || (!$photo->is_public && !GalleryHelper::canSeePrivatePhotos()))
            throw PageNotFoundException::forPageNotFound();

        $path = GalleryHelper::getPhotoPath($photo->album_id, $photo->filename, $size === 'miniature');
        if (!is_file($path))
            throw PageNotFoundException::forPageNotFound();

        return $this->response
            ->setHeader('Content-Type', 'image/jpeg')
            //"private": the photos must not be kept by shared caches (their visibility can change)
            ->setHeader('Cache-Control', 'private, max-age=3600')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setBody(file_get_contents($path));
    }
}
