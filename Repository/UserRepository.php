<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use DatabaseManager;

class UserRepository {
    private \mysqli $db;

    public function __construct() {
        $this->db = DatabaseManager::getInstance()->getConnection();
    }

    // CREATE
    public function create(User $user): int {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("sss", $user->username, $user->email, $user->passwordHash);
        if (!$stmt->execute()) {
            throw new \Exception("Execute failed: " . $stmt->error);
        }
        $insertedId = $stmt->insert_id;
        $stmt->close();
        return $insertedId;
    }

    // READ: получить по id
    public function getById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT id, username, email, password_hash FROM users WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($id, $username, $email, $passwordHash);
        if ($stmt->fetch()) {
            $user = new User($id, $username, $email, $passwordHash);
            $stmt->close();
            return $user;
        }
        $stmt->close();
        return null;
    }

    // READ: получить все записи
    public function getAll(): array {
        $result = $this->db->query("SELECT id, username, email, password_hash FROM users");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = new User((int)$row['id'], $row['username'], $row['email'], $row['password_hash']);
        }
        return $users;
    }

    // UPDATE
    public function update(User $user): bool {
        if ($user->id === null) {
            throw new \Exception("User id is null");
        }
        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, password_hash = ? WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("sssi", $user->username, $user->email, $user->passwordHash, $user->id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // DELETE
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        if (!$stmt) {
            throw new \Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
