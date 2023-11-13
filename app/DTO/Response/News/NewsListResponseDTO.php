<?php

namespace App\DTO\Response\News;

use DateTime;

class NewsListResponseDTO
{
    public int $id;
    public string $title;
    public string $content;
    public DateTime $created_at;
    public string $picture;
    public mixed $category;
    public mixed $author;
}