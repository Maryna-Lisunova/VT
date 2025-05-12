<?php

declare (strict_types = 1);

class DatabaseManager {
    private static ?DatabaseManager $instance = null;
    private mysqli $mysqli;
    public function connectToDatabase(): void {
        $env = parse_ini_file(__DIR__ . '/../.env');
        $this->mysqli = new mysqli($env['DB_HOST'], $env['DB_USER'], $env['DB_PASSWORD'], $env['DB_NAME']);
        $this->mysqli->set_charset('UTF8');
    }

    public static function getInstance(): DatabaseManager {
        if (self::$instance == null) {
            self::$instance = new DatabaseManager();
            self::$instance->connectToDatabase();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli {
        return $this->mysqli;
    }
}