<?php

declare(strict_types=1);

class CalendarController {
    public function index(): void {
        // Определяем путь к файлу с данными
        $dataFile = __DIR__ . '/../../Public/html/text.html';

        // Если файл существует, считываем его содержимое
        $calendarContent = file_exists($dataFile) ? file_get_contents($dataFile) : '<!-- Контент не найден -->';

        // Собираем данные для шаблона. Вы можете дополнительно подготовить другие переменные.
        $data = [
            'title'           => 'Мой календарь',
            'calendarContent' => $calendarContent,
            'bgImage'     => 'images/christmas_paper1280.jpg',
        ];

        // Делаем переменные доступными в шаблоне
        extract($data);

        // Подключаем шаблон
        require_once __DIR__ . '/../Views/Templates/calendar.php';
        exit;
    }
}
