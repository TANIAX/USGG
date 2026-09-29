<?php

namespace App\Helpers;

use Exception;

/**
 * Processing of the uploaded images (gallery, events, portraits).
 * The images are always re-encoded in JPEG: this removes their metadata (EXIF, GPS position...),
 * after having turned them according to their EXIF orientation.
 *
 * @author Guillaume Cornez
 */
class ImageHelper
{
    private const JPEG_QUALITY = 85;

    /**
     * Opens an uploaded image, turned upright.
     *
     * @return \GdImage
     * @throws Exception If the file is not a valid image
     */
    public static function open(string $source)
    {
        //Big pictures (smartphones) need a lot of memory once decoded
        ini_set('memory_limit', '512M');

        $image = @imagecreatefromstring((string) file_get_contents($source));
        if ($image === false)
            throw new Exception('Le fichier n\'est pas une image valide.');

        return self::applyExifOrientation($image, $source);
    }

    /**
     * Returns a copy of the image fitting in a square of $maxSize px (on a white background, for transparent images).
     */
    public static function fit($image, int $maxSize)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = min(1, $maxSize / max($width, $height));

        return self::copy($image, 0, 0, $width, $height, max(1, (int) round($width * $ratio)), max(1, (int) round($height * $ratio)));
    }

    /**
     * Returns a square copy of the image (centered crop), of $size px.
     */
    public static function square($image, int $size)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $side = min($width, $height);
        $size = min($size, $side);

        return self::copy($image, intdiv($width - $side, 2), intdiv($height - $side, 2), $side, $side, $size, $size);
    }

    /**
     * Saves an image in JPEG (the directory is created if needed).
     *
     * @throws Exception If the image can not be saved
     */
    public static function saveJpeg($image, string $path)
    {
        $directory = dirname($path);
        if (!is_dir($directory))
            mkdir($directory, 0775, true);

        if (!imagejpeg($image, $path, self::JPEG_QUALITY))
            throw new Exception('Impossible d\'enregistrer l\'image sur le serveur.');
    }

    /**
     * Saves a logo in PNG, fitting in a square of $maxSize px, the transparency being kept.
     *
     * @throws Exception If the image can not be saved
     */
    public static function saveTransparentPng($image, string $path, int $maxSize = 512)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = min(1, $maxSize / max($width, $height));
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $result = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($result, false);
        imagesavealpha($result, true);
        imagefill($result, 0, 0, imagecolorallocatealpha($result, 0, 0, 0, 127));
        imagecopyresampled($result, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $directory = dirname($path);
        if (!is_dir($directory))
            mkdir($directory, 0775, true);
        if (!imagepng($result, $path, 9))
            throw new Exception('Impossible d\'enregistrer l\'image sur le serveur.');
    }

    /**
     * Random file name (without extension), impossible to guess.
     */
    public static function randomName()
    {
        return bin2hex(random_bytes(16));
    }

    private static function copy($image, int $sourceX, int $sourceY, int $sourceWidth, int $sourceHeight, int $width, int $height)
    {
        $result = imagecreatetruecolor($width, $height);
        imagefill($result, 0, 0, imagecolorallocate($result, 255, 255, 255));
        imagecopyresampled($result, $image, 0, 0, $sourceX, $sourceY, $width, $height, $sourceWidth, $sourceHeight);

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
