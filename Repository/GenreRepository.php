<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Genre;
use DatabaseManager;

class GenreRepository {
    private \mysqli $db;

    public function __construct() {
        $this->db = DatabaseManager::getInstance()->getConnection();
    }

    // CREATE
    public function create(Genre $genre): int {
        $stmt = $this->db->prepare("INSERT INTO genres (name) VALUES (?)");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("s", $genre->name);
        if (!$stmt->execute()) {
            throw new \Exception("Execute failed: " . $stmt->error);
        }
        $insertedId = $stmt->insert_id;
        $stmt->close();
        return $insertedId;
    }

    // READ: получить по id
    public function getById(int $id): ?Genre {
        $stmt = $this->db->prepare("SELECT id, name FROM genres WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($id, $name);
        if ($stmt->fetch()) {
            $genre = new Genre($id, $name);
            $stmt->close();
            return $genre;
        }
        $stmt->close();
        return null;
    }

    // READ: получить все жанры
    public function getAll(): array {
        $result = $this->db->query("SELECT id, name FROM genres");
        $genres = [];
        while ($row = $result->fetch_assoc()) {
            $genres[] = new Genre((int)$row['id'], $row['name']);
        }
        return $genres;
    }

    // UPDATE
    public function update(Genre $genre): bool {
        if ($genre->id === null) {
            throw new \Exception("Genre ID is null");
        }
        $stmt = $this->db->prepare("UPDATE genres SET name = ? WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("si", $genre->name, $genre->id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // DELETE
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM genres WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
