<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

class HomeController
{
    public function index(Request $request, Response $response): string
    {
        return View::render('home/index', [
            'title' => 'BooyahIndia — Premium Esports Tournaments',
        ]);
    }
}
