<?php

declare(strict_types=1);

class CalendarController {
    public function index(): void {
        $dataFile = __DIR__ . '/../../Public/html/text.html';

        $calendarContent = file_exists($dataFile) ? file_get_contents($dataFile) : '<!-- Контент не найден -->';

        $data = [
            'title'           => 'Мой календарь',
            'calendarContent' => $calendarContent,
            'bgImage'     => 'images/christmas_paper1280.jpg',
        ];

        extract($data);
        require_once __DIR__ . '/../Views/Templates/calendar.php';
        exit;
    }
}
