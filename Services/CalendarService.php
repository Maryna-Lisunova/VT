<?php
namespace Services;

class CalendarService {
    public function getCalendarData(): array {
        $dataFile = __DIR__ . '/../Public/html/text.html';

        $calendarContent = file_exists($dataFile)
            ? file_get_contents($dataFile)
            : '<!-- Контент не найден -->';

        $calendarContent = preg_replace_callback(
            '/<img([^>]+)src=([\'"])(.*?)\2([^>]*)>/i',
            function ($matches) {
                $originalSrc = $matches[3];
                if (strpos($originalSrc, 'http://') !== 0 && strpos($originalSrc, 'https://') !== 0) {
                    $newSrc = '/apps/my_project/Public/' . ltrim($originalSrc, '/');
                } else {
                    $newSrc = $originalSrc;
                }
                return "<img{$matches[1]}src={$matches[2]}{$newSrc}{$matches[2]}{$matches[4]}>";
            },
            $calendarContent
        );

        return [
            'title'           => 'Мой календарь',
            'calendarContent' => $calendarContent,
            'bgImage'         => 'images/christmas_paper1280.jpg',
        ];
    }
}
