<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\LeaderboardController;
use App\Controllers\RedeemController;
use App\Controllers\TournamentController;
use App\Controllers\WalletController;
use App\Core\Router;

return static function (Router $router): void {
    $router->get('/', [HomeController::class, 'index']);

    $router->get('/login', [AuthController::class, 'login']);
    $router->post('/auth/otp/send', [AuthController::class, 'mobileOtp']);
    $router->post('/auth/otp/verify', [AuthController::class, 'verifyOtp']);
    $router->get('/auth/google/redirect', [AuthController::class, 'googleRedirect']);
    $router->get('/auth/google/callback', [AuthController::class, 'googleCallback']);
    $router->post('/logout', [AuthController::class, 'logout']);

    $router->get('/tournaments', [TournamentController::class, 'list']);
    $router->post('/tournaments/join', [TournamentController::class, 'join']);
    $router->post('/tournaments/room', [TournamentController::class, 'roomCredentials']);

    $router->get('/wallet', [WalletController::class, 'index']);
    $router->post('/wallet/upi', [WalletController::class, 'updateUpi']);

    $router->post('/redeem/purchase', [RedeemController::class, 'purchase']);
    $router->post('/redeem/reveal', [RedeemController::class, 'reveal']);

    $router->get('/leaderboard', [LeaderboardController::class, 'index']);

    $router->get('/dashboard', [AdminController::class, 'dashboard']);
    $router->post('/admin/users/role', [AdminController::class, 'changeRole']);
};
