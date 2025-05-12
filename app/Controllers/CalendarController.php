<?php
declare(strict_types=1);

use Services\CalendarService;
require_once __DIR__ . '/../../DataBase/DatabaseManager.php';

class CalendarController {
    private CalendarService $calendarService;

    public function __construct() {
        $this->calendarService = new CalendarService();
    }

    public function index(): void {
        $data = $this->calendarService->getCalendarData();
        extract($data);
        require_once __DIR__ . '/../Views/Templates/calendar.php';
        exit;
    }

    public function listWorks(): array {
        $databaseManager = DatabaseManager::getInstance();
        $mysqli = $databaseManager->getConnection();

        $result = $mysqli->query("SELECT * FROM works");
        $works = [];
        while ($row = $result->fetch_assoc()) {
            $works[] = $row;
        }
        return $works;
    }
}
