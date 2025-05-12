<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Work;
use DatabaseManager;

class WorkRepository {
    private \mysqli $db;

    public function __construct() {
        $this->db = DatabaseManager::getInstance()->getConnection();
    }
    
    // CREATE
    public function create(Work $work): int {
        $stmt = $this->db->prepare("INSERT INTO works (user_id, genre_id, calendar_date, title, content_type, content) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param(
            "iissss",
            $work->userId,
            $work->genreId,
            $work->calendarDate,
            $work->title,
            $work->contentType,
            $work->content
        );
        if (!$stmt->execute()) {
            throw new \Exception("Execute failed: " . $stmt->error);
        }
        $insertedId = $stmt->insert_id;
        $stmt->close();
        return $insertedId;
    }

    // READ: получить работу по id
    public function getById(int $id): ?Work {
        $stmt = $this->db->prepare("SELECT id, user_id, genre_id, calendar_date, title, content_type, content FROM works WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $genreId, $calendarDate, $title, $contentType, $content);
        if ($stmt->fetch()) {
            $work = new Work($id, $userId, $genreId, $calendarDate, $title, $contentType, $content);
            $stmt->close();
            return $work;
        }
        $stmt->close();
        return null;
    }

    // READ: получить все работы
    public function getAll(): array {
        $result = $this->db->query("SELECT id, user_id, genre_id, calendar_date, title, content_type, content FROM works");
        $works = [];
        while ($row = $result->fetch_assoc()) {
            $works[] = new Work(
                (int)$row['id'],
                (int)$row['user_id'],
                (int)$row['genre_id'],
                $row['calendar_date'],
                $row['title'],
                $row['content_type'],
                $row['content']
            );
        }
        return $works;
    }

    // UPDATE
    public function update(Work $work): bool {
        if ($work->id === null) {
            throw new \Exception("Work ID is null");
        }
        $stmt = $this->db->prepare("UPDATE works SET user_id = ?, genre_id = ?, calendar_date = ?, title = ?, content_type = ?, content = ? WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param(
            "iissssi",
            $work->userId,
            $work->genreId,
            $work->calendarDate,
            $work->title,
            $work->contentType,
            $work->content,
            $work->id
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // DELETE
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM works WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
