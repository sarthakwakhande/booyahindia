<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Application;

class UserModel
{
    public function find(int $id): ?array
    {
        $stmt = Application::$app->db->pdo()->prepare('SELECT id, name, role, status FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updateRole(int $targetUserId, string $role): bool
    {
        $stmt = Application::$app->db->pdo()->prepare('UPDATE users SET role = :role WHERE id = :id');
        return $stmt->execute(['role' => $role, 'id' => $targetUserId]);
    }

    public function leaderboard(): array
    {
        $stmt = Application::$app->db->pdo()->prepare('SELECT * FROM leaderboard ORDER BY highest_earnings DESC LIMIT 100');
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
