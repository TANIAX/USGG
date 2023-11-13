<?php

namespace App\Helpers;


/**
 * A helper class for handling file operations.
 *
 * @author Guillaume cornez
 */
class FileHelper
{

    const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
    const VIDEO_EXTENSIONS = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
    const AUDIO_EXTENSIONS = ['mp3', 'wav', 'ogg', 'wma', 'aac', 'flac', 'alac'];

    const PROFIL_PICTURE_DIRECTORY = 'uploads/pp/';
    const DEFAULT_PROFIL_PICTURE = 'assets/img/question-mark.jpg';

    const NEWS_PICTURE_DIRECTORY = 'uploads/news/';
    const DEFAULT_NEWS_PICTURE = 'assets/img/question-mark.jpg';//TODO CHANGE ME 

    /**
     * Converts a file to a base64 encoded string
     *
     * @param string $file_path  The path to the file to be converted
     * @param bool $delete_file  Flag to indicate whether to delete the file after encoding
     *
     * @return string  The base64 encoded string
     */
    public static function fileToBase64(string $file_path, bool $delete_file = false)
    {
        if (file_exists($file_path)) {
            $data = file_get_contents($file_path);
            $base64 = base64_encode($data);

            if ($delete_file) {
                unlink($file_path);
            }

            return $base64;
        } else {
            return 'File does not exist';
        }
    }
}
