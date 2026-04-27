<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\TournamentModel;
use App\Services\AuthService;

class TournamentController
{
    private TournamentModel $tournaments;

    public function __construct()
    {
        $this->tournaments = new TournamentModel();
    }

    public function list(Request $request, Response $response): string
    {
        return View::render('tournaments/list', ['matches' => $this->tournaments->allUpcoming()]);
    }

    public function join(Request $request, Response $response): string
    {
        $userId = AuthService::requireLogin();
        $tournamentId = (int)$request->input('tournament_id');

        if ($tournamentId <= 0) {
            return $response->json(['status' => 'error', 'message' => 'Invalid tournament id'], 422);
        }

        $joined = $this->tournaments->join($tournamentId, $userId);
        if (!$joined) {
            return $response->json(['status' => 'error', 'message' => 'Unable to join match'], 409);
        }

        return $response->json(['status' => 'ok', 'message' => 'Joined successfully']);
    }

    public function roomCredentials(Request $request, Response $response): string
    {
        $userId = AuthService::requireLogin();
        $tournamentId = (int)$request->input('tournament_id');
        $match = $this->tournaments->findById($tournamentId);

        if (!$match || !$this->tournaments->userHasJoined($tournamentId, $userId)) {
            return $response->json(['status' => 'error', 'message' => 'Access denied'], 403);
        }

        $seconds = strtotime($match['start_at'] . ' UTC') - time();
        if ($seconds > 240) {
            return $response->json(['status' => 'locked', 'message' => 'Room details unlock 4 minutes before start']);
        }

        return $response->json([
            'status' => 'ok',
            'room_id' => $match['room_id'],
            'room_password' => $match['room_password'],
        ]);
    }
}
