<?php

namespace App\Helpers;

use Exception;

/**
 * Profile pictures (accounts, section leaders): square, 400 px, re-encoded without metadata, in public/uploads/pp/.
 */
class ProfilePictureHelper
{
    private const SIZE = 400;

    /**
     * Stores the picture sent in the field $field of the request.
     *
     * @return array ['replace' => false] | ['replace' => true, 'picture' => string] | ['error' => string]
     */
    public static function store(string $field = 'picture'): array
    {
        $file = service('request')->getFile($field);
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE)
            return ['replace' => false];

        if (!$file->isValid())
            return ['error' => $file->getError() === UPLOAD_ERR_INI_SIZE ? 'La photo est trop lourde pour le serveur.' : 'La photo n\'a pas pu être envoyée.'];
        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true))
            return ['error' => 'Format de photo non accepté (JPEG, PNG, WebP ou GIF).'];

        try {
            $name = ImageHelper::randomName() . '.jpg';
            ImageHelper::saveJpeg(ImageHelper::square(ImageHelper::open($file->getTempName()), self::SIZE), self::path($name));
        } catch (Exception $exception) {
            LogHelper::exception('Photo de profil non enregistrée', $exception);
            return ['error' => $exception->getMessage()];
        }

        return ['replace' => true, 'picture' => $name];
    }

    public static function delete(?string $picture): void
    {
        // Only a file of the profile pictures directory, never a path given by someone
        if ($picture && basename($picture) === $picture && is_file(self::path($picture)))
            unlink(self::path($picture));
    }

    public static function url(?string $picture): ?string
    {
        return $picture ? base_url(FileHelper::PROFIL_PICTURE_DIRECTORY . $picture) : null;
    }

    private static function path(string $name): string
    {
        return ROOTPATH . 'public' . DIRECTORY_SEPARATOR . FileHelper::PROFIL_PICTURE_DIRECTORY . $name;
    }
}
