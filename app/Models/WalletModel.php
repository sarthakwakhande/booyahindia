<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Application;

class WalletModel
{
    public function getByUserId(int $userId): ?array
    {
        $stmt = Application::$app->db->pdo()->prepare(
            'SELECT w.balance, u.upi_id FROM wallets w INNER JOIN users u ON u.id = w.user_id WHERE w.user_id = :user_id LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updateUpi(int $userId, string $upiId): bool
    {
        $stmt = Application::$app->db->pdo()->prepare('UPDATE users SET upi_id = :upi WHERE id = :id');
        return $stmt->execute(['upi' => $upiId, 'id' => $userId]);
    }
}
