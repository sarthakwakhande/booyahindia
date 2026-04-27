<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Application;

class RedeemCodeModel
{
    public function purchase(int $userId, int $batchId): ?array
    {
        $pdo = Application::$app->db->pdo();
        $pdo->beginTransaction();

        $batchStmt = $pdo->prepare('SELECT custom_price FROM redeem_code_batches WHERE id = :id LIMIT 1');
        $batchStmt->execute(['id' => $batchId]);
        $batch = $batchStmt->fetch();

        if (!$batch) {
            $pdo->rollBack();
            return null;
        }

        $walletStmt = $pdo->prepare('SELECT balance FROM wallets WHERE user_id = :user_id FOR UPDATE');
        $walletStmt->execute(['user_id' => $userId]);
        $wallet = $walletStmt->fetch();

        if (!$wallet || (float)$wallet['balance'] < (float)$batch['custom_price']) {
            $pdo->rollBack();
            return null;
        }

        $codeStmt = $pdo->prepare('SELECT id, code_value FROM redeem_codes WHERE batch_id = :batch_id AND sold_to IS NULL LIMIT 1 FOR UPDATE');
        $codeStmt->execute(['batch_id' => $batchId]);
        $code = $codeStmt->fetch();

        if (!$code) {
            $pdo->rollBack();
            return null;
        }

        $pdo->prepare('UPDATE wallets SET balance = balance - :amount WHERE user_id = :user_id')
            ->execute(['amount' => $batch['custom_price'], 'user_id' => $userId]);

        $pdo->prepare('UPDATE redeem_codes SET sold_to = :user_id, sold_at = UTC_TIMESTAMP() WHERE id = :id')
            ->execute(['user_id' => $userId, 'id' => $code['id']]);

        $pdo->commit();

        return ['code_id' => (int)$code['id'], 'code_value' => $code['code_value'], 'price' => (float)$batch['custom_price']];
    }

    public function revealOnce(int $userId, int $codeId): ?string
    {
        $pdo = Application::$app->db->pdo();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('SELECT code_value, revealed_at FROM redeem_codes WHERE id = :id AND sold_to = :user_id LIMIT 1 FOR UPDATE');
        $stmt->execute(['id' => $codeId, 'user_id' => $userId]);
        $code = $stmt->fetch();

        if (!$code || $code['revealed_at'] !== null) {
            $pdo->rollBack();
            return null;
        }

        $pdo->prepare('UPDATE redeem_codes SET revealed_at = UTC_TIMESTAMP() WHERE id = :id')->execute(['id' => $codeId]);
        $pdo->commit();
        return (string)$code['code_value'];
    }
}
