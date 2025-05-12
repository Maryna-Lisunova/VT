<?php

declare(strict_types=1);

namespace App\Models;

class Work {
    public ?int $id;
    public int $userId;
    public int $genreId;
    public string $calendarDate;
    public string $title;
    public string $contentType; 
    public string $content;

    public function __construct(
        ?int $id,
        int $userId,
        int $genreId,
        string $calendarDate,
        string $title,
        string $contentType,
        string $content
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->genreId = $genreId;
        $this->calendarDate = $calendarDate;
        $this->title = $title;
        $this->contentType = $contentType;
        $this->content = $content;
    }
}
