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
    private const JPEG_QUALITY = 85;

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
        //Big pictures (smartphones) need a lot of memory once decoded
        ini_set('memory_limit', '512M');

        $image = @imagecreatefromstring((string) file_get_contents($source));
        if ($image === false)
            throw new Exception('Le fichier n\'est pas une image valide.');

        $image = self::applyExifOrientation($image, $source);

        $filename = bin2hex(random_bytes(16));
        $directory = self::getAlbumDirectory($albumId);
        if (!is_dir($directory . 'thumbnails'))
            mkdir($directory . 'thumbnails', 0775, true);

        $photo = self::resize($image, self::PHOTO_MAX_SIZE);
        $thumbnail = self::resize($photo, self::THUMBNAIL_MAX_SIZE);

        $saved = imagejpeg($photo, self::getPhotoPath($albumId, $filename), self::JPEG_QUALITY)
            && imagejpeg($thumbnail, self::getPhotoPath($albumId, $filename, true), self::JPEG_QUALITY);

        $result = ['filename' => $filename, 'width' => imagesx($photo), 'height' => imagesy($photo)];

        if (!$saved) {
            self::deletePhotoFiles($albumId, $filename);
            throw new Exception('Impossible d\'enregistrer la photo sur le serveur.');
        }

        return $result;
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

    /**
     * Returns a copy of the image fitting in a square of $maxSize px (on a white background, for transparent PNG).
     */
    private static function resize($image, int $maxSize)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = min(1, $maxSize / max($width, $height));
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $result = imagecreatetruecolor($newWidth, $newHeight);
        imagefill($result, 0, 0, imagecolorallocate($result, 255, 255, 255));
        imagecopyresampled($result, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        return $result;
    }

    /**
     * Turns the image according to the EXIF orientation set by the camera.
     */
    private static function applyExifOrientation($image, string $source)
    {
        if (!function_exists('exif_read_data'))
            return $image;

        $exif = @exif_read_data($source);
        switch ($exif['Orientation'] ?? 1) {
            case 2: imageflip($image, IMG_FLIP_HORIZONTAL); break;
            case 3: $image = imagerotate($image, 180, 0); break;
            case 4: imageflip($image, IMG_FLIP_VERTICAL); break;
            case 5: $image = imagerotate($image, -90, 0); imageflip($image, IMG_FLIP_HORIZONTAL); break;
            case 6: $image = imagerotate($image, -90, 0); break;
            case 7: $image = imagerotate($image, 90, 0); imageflip($image, IMG_FLIP_HORIZONTAL); break;
            case 8: $image = imagerotate($image, 90, 0); break;
        }

        return $image;
    }
}
