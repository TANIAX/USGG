<?php

namespace App\Helpers;

use Exception;

/**
 * Rules and tools of the photo gallery:
 * - who can manage the albums of which branch (guide / scout),
 * - where the photos are stored (outside of the public folder, so that private photos can not be reached with their url),
 * - how the uploaded images are processed.
 *
 * @author Guillaume Cornez
 */
class GalleryHelper
{
    public const BRANCH_GUIDE = 'GUIDE';
    public const BRANCH_SCOUT = 'SCOUTE';
    public const BRANCHES = [
        self::BRANCH_GUIDE => 'Guides',
        self::BRANCH_SCOUT => 'Scouts',
    ];

    /**
     * Branches managed by each role. The super admin is the only one to choose.
     */
    private const BRANCHES_BY_ROLE = [
        'super_admin' => [self::BRANCH_GUIDE, self::BRANCH_SCOUT],
        'guide_admin' => [self::BRANCH_GUIDE],
        'scout_admin' => [self::BRANCH_SCOUT],
    ];

    /**
     * Maximum size (px) of the stored photo and of its thumbnail.
     */
    public const PHOTO_MAX_SIZE = 2000;
    public const THUMBNAIL_MAX_SIZE = 600;

    /**
     * Returns the branches the connected user can manage.
     *
     * @return array of string
     */
    public static function getManageableBranches()
    {
        $user = SessionHelper::getUserConnected();
        if (!$user)
            return [];

        $branches = [];
        foreach ($user->getRolesAsStrings() as $role) {
            $branches = array_merge($branches, self::BRANCHES_BY_ROLE[$role] ?? []);
        }

        return array_values(array_intersect(array_keys(self::BRANCHES), $branches));
    }

    public static function canManageBranch(?string $branch)
    {
        return in_array($branch, self::getManageableBranches(), true);
    }

    /**
     * Private photos are only visible to logged in users (whatever their role).
     */
    public static function canSeePrivatePhotos()
    {
        return SessionHelper::isUserConnected();
    }

    public static function getAlbumDirectory(int $albumId)
    {
        return WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR . $albumId . DIRECTORY_SEPARATOR;
    }

    public static function getPhotoPath(int $albumId, string $filename, bool $thumbnail = false)
    {
        return self::getAlbumDirectory($albumId) . ($thumbnail ? 'thumbnails' . DIRECTORY_SEPARATOR : '') . $filename . '.jpg';
    }

    /**
     * Stores an uploaded image: it is re-encoded in JPEG (which removes the EXIF data such as the GPS position),
     * turned according to its EXIF orientation and resized, with a thumbnail.
     *
     * @param string $source Path of the uploaded file
     * @param int $albumId
     * @return array ['filename' => string, 'width' => int, 'height' => int]
     * @throws Exception If the file is not a valid image
     */
    public static function storePhoto(string $source, int $albumId)
    {
        $image = ImageHelper::open($source);
        $filename = ImageHelper::randomName();

        $photo = ImageHelper::fit($image, self::PHOTO_MAX_SIZE);
        $thumbnail = ImageHelper::fit($photo, self::THUMBNAIL_MAX_SIZE);

        try {
            ImageHelper::saveJpeg($photo, self::getPhotoPath($albumId, $filename));
            ImageHelper::saveJpeg($thumbnail, self::getPhotoPath($albumId, $filename, true));
        } catch (Exception $exception) {
            self::deletePhotoFiles($albumId, $filename);
            throw new Exception('Impossible d\'enregistrer la photo sur le serveur.');
        }

        return ['filename' => $filename, 'width' => imagesx($photo), 'height' => imagesy($photo)];
    }

    public static function deletePhotoFiles(int $albumId, string $filename)
    {
        foreach ([false, true] as $thumbnail) {
            $path = self::getPhotoPath($albumId, $filename, $thumbnail);
            if (is_file($path))
                unlink($path);
        }
    }

    public static function deleteAlbumDirectory(int $albumId)
    {
        $directory = self::getAlbumDirectory($albumId);
        foreach ([$directory . 'thumbnails' . DIRECTORY_SEPARATOR, $directory] as $folder) {
            if (!is_dir($folder))
                continue;
            foreach (glob($folder . '*.jpg') as $file) {
                unlink($file);
            }
            @rmdir($folder);
        }
    }
}
