<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\RedeemCodeModel;
use App\Services\AuthService;

class RedeemController
{
    private RedeemCodeModel $codes;

    public function __construct()
    {
        $this->codes = new RedeemCodeModel();
    }

    public function purchase(Request $request, Response $response): string
    {
        $userId = AuthService::requireLogin();
        $batchId = (int)$request->input('batch_id');

        if ($batchId <= 0) {
            return $response->json(['status' => 'error', 'message' => 'Invalid batch id'], 422);
        }

        $purchase = $this->codes->purchase($userId, $batchId);
        if (!$purchase) {
            return $response->json(['status' => 'error', 'message' => 'Purchase failed'], 409);
        }

        return $response->json(['status' => 'ok', 'code_id' => $purchase['code_id'], 'message' => 'Purchased successfully']);
    }

    public function reveal(Request $request, Response $response): string
    {
        $userId = AuthService::requireLogin();
        $codeId = (int)$request->input('code_id');

        $code = $this->codes->revealOnce($userId, $codeId);
        if ($code === null) {
            return $response->json(['status' => 'error', 'message' => 'Code unavailable or already revealed'], 409);
        }

        return $response->json(['status' => 'ok', 'code' => $code]);
    }
}
