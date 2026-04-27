<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Application;

class AuditLogService
{
    public function log(int $adminId, string $action, string $targetType, ?int $targetId = null, array $payload = []): void
    {
        $stmt = Application::$app->db->pdo()->prepare(
            'INSERT INTO admin_activity_logs (admin_id, action, target_type, target_id, payload, ip_address)
             VALUES (:admin_id, :action, :target_type, :target_id, :payload, :ip_address)'
        );

        $stmt->execute([
            'admin_id' => $adminId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'payload' => json_encode($payload, JSON_THROW_ON_ERROR),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }
}
