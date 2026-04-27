<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Application;

class TournamentModel
{
    public function allUpcoming(): array
    {
        $stmt = Application::$app->db->pdo()->prepare(
            'SELECT id, title, mode, entry_fee, prize_pool, max_slots, joined_slots, start_at, join_locked
             FROM tournaments
             WHERE start_at >= UTC_TIMESTAMP()
             ORDER BY start_at ASC
             LIMIT 50'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = Application::$app->db->pdo()->prepare(
            'SELECT id, title, mode, entry_fee, prize_pool, max_slots, joined_slots, start_at, join_locked, room_id, room_password
             FROM tournaments WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function userHasJoined(int $tournamentId, int $userId): bool
    {
        $stmt = Application::$app->db->pdo()->prepare(
            'SELECT id FROM tournament_entries WHERE tournament_id = :tournament_id AND user_id = :user_id LIMIT 1'
        );
        $stmt->execute(['tournament_id' => $tournamentId, 'user_id' => $userId]);
        return (bool)$stmt->fetch();
    }

    public function join(int $tournamentId, int $userId): bool
    {
        $pdo = Application::$app->db->pdo();
        $pdo->beginTransaction();

        $slotStmt = $pdo->prepare('SELECT max_slots, joined_slots, join_locked FROM tournaments WHERE id = :id FOR UPDATE');
        $slotStmt->execute(['id' => $tournamentId]);
        $match = $slotStmt->fetch();

        if (!$match || (int)$match['join_locked'] === 1 || (int)$match['joined_slots'] >= (int)$match['max_slots']) {
            $pdo->rollBack();
            return false;
        }

        $ins = $pdo->prepare('INSERT IGNORE INTO tournament_entries (tournament_id, user_id) VALUES (:tournament_id, :user_id)');
        $ins->execute(['tournament_id' => $tournamentId, 'user_id' => $userId]);

        if ($ins->rowCount() === 0) {
            $pdo->rollBack();
            return false;
        }

        $upd = $pdo->prepare('UPDATE tournaments SET joined_slots = joined_slots + 1 WHERE id = :id');
        $upd->execute(['id' => $tournamentId]);
        $pdo->commit();
        return true;
    }
}
