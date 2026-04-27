<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\UserModel;
use App\Services\AuditLogService;
use App\Services\AuthService;

class AdminController
{
    private UserModel $users;
    private AuditLogService $audit;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->audit = new AuditLogService();
    }

    public function dashboard(Request $request, Response $response): string
    {
        AuthService::requireAdmin();

        return View::render('admin/dashboard', [
            'metrics' => [
                'users' => 0,
                'live_matches' => 0,
                'pending_withdrawals' => 0,
                'redeem_codes_available' => 0,
            ],
        ]);
    }

    public function changeRole(Request $request, Response $response): string
    {
        $adminId = AuthService::requireAdmin();
        $targetUserId = (int)$request->input('user_id');
        $role = (string)$request->input('role');

        if (!in_array($role, ['user', 'admin'], true) || $targetUserId <= 0) {
            return $response->json(['status' => 'error', 'message' => 'Invalid role update payload'], 422);
        }

        if ($targetUserId === $adminId) {
            return $response->json(['status' => 'error', 'message' => 'Self-role change is blocked'], 403);
        }

        $this->users->updateRole($targetUserId, $role);
        $this->audit->log($adminId, 'role_changed', 'user', $targetUserId, ['new_role' => $role]);

        return $response->json(['status' => 'ok', 'message' => 'Role updated']);
    }
}
