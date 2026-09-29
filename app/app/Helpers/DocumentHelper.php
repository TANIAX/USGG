<?php

namespace App\Helpers;

/**
 * Storage of the documents (guide / scout downloads).
 * The documents are stored in writable/uploads/documents/ with a random name: they can only be downloaded
 * through the application, which checks that they are active. The documents uploaded before this storage
 * are still in public/uploads/documents/{path}/{name}; they are moved to the private storage when they are deactivated.
 *
 * @author Guillaume Cornez
 */
class DocumentHelper
{
    public const EXTENSIONS = ['pdf', 'doc', 'docx', 'odt', 'xls', 'xlsx', 'ods', 'txt', 'jpg', 'jpeg', 'png'];
    public const MAX_SIZE_KB = 10240;

    public static function privateDirectory()
    {
        return WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR;
    }

    /**
     * Path of the file of a document (private storage, or public folder for the old documents).
     */
    public static function getPath($document)
    {
        if (!empty($document->stored_name))
            return self::privateDirectory() . $document->stored_name;

        return ROOTPATH . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, FileHelper::DOCUMENTS_DIRECTORY)
            . $document->path . DIRECTORY_SEPARATOR . $document->name;
    }

    /**
     * Random name, with the extension of the document.
     */
    public static function newStoredName(string $extension)
    {
        return bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    }

    /**
     * Moves an old document (public folder) to the private storage, so that it can not be reached with its url anymore.
     *
     * @return string|null The new stored name, or null if the file could not be moved
     */
    public static function moveToPrivateStorage($document)
    {
        if (!empty($document->stored_name))
            return $document->stored_name;

        $source = self::getPath($document);
        if (!is_file($source))
            return null;

        if (!is_dir(self::privateDirectory()))
            mkdir(self::privateDirectory(), 0775, true);

        $storedName = self::newStoredName(pathinfo($document->name, PATHINFO_EXTENSION) ?: 'bin');
        return rename($source, self::privateDirectory() . $storedName) ? $storedName : null;
    }

    public static function deleteFile($document)
    {
        $path = self::getPath($document);
        if (is_file($path))
            unlink($path);
    }

    /**
     * Keeps a name usable as a file name: no path, no control characters.
     */
    public static function cleanName(string $name)
    {
        $name = preg_replace('/[\x00-\x1F\x7F\/\\\\:*?"<>|]+/u', ' ', $name);
        return trim(preg_replace('/\s+/', ' ', $name), ' .');
    }
}
