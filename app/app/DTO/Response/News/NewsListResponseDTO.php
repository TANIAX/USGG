<?php

namespace App\DTO\Response\News;

use DateTime;

/**
 * Class NewsListResponseDTO
 * 
 * Represents a response DTO for a list of news.
 */
class NewsListResponseDTO
{
    /**
     * @var int The ID of the news.
     */
    public int $id;

    /**
     * @var string The title of the news.
     */
    public string $title;

    /**
     * @var string The content of the news.
     */
    public string $content;

    /**
     * @var DateTime The creation date of the news.
     */
    public DateTime $created_at;

    /**
     * @var string The picture URL of the news.
     */
    public string $picture;

    /**
     * @var mixed The category of the news.
     * @see NewsRepository for more information about the type of this property.
     */
    public mixed $category;

    /**
     * @var mixed The author of the news.
     * @see NewsRepository for more information about the type of this property.
     */
    public mixed $author;
}