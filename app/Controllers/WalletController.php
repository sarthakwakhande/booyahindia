<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\WalletModel;
use App\Services\AuthService;

class WalletController
{
    private WalletModel $wallets;

    public function __construct()
    {
        $this->wallets = new WalletModel();
    }

    public function index(Request $request, Response $response): string
    {
        $userId = AuthService::requireLogin();
        $wallet = $this->wallets->getByUserId($userId);

        return View::render('wallet/index', [
            'balance' => $wallet['balance'] ?? 0,
            'upi' => $wallet['upi_id'] ?? 'not-set@upi',
        ]);
    }

    public function updateUpi(Request $request, Response $response): string
    {
        $userId = AuthService::requireLogin();
        $upi = (string)$request->input('upi_id');

        if (!preg_match('/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/', $upi)) {
            return $response->json(['status' => 'error', 'message' => 'Invalid UPI ID'], 422);
        }

        $this->wallets->updateUpi($userId, $upi);
        return $response->json(['status' => 'ok', 'message' => 'UPI updated']);
    }
}
