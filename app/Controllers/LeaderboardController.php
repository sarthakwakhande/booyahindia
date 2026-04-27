<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\UserModel;

class LeaderboardController
{
    public function index(Request $request, Response $response): string
    {
        $rows = (new UserModel())->leaderboard();
        return View::render('leaderboard/index', ['rows' => $rows]);
    }
}
