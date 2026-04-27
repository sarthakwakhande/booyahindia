<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Application;

class AuthService
{
    public static function userId(): ?int
    {
        $id = Application::$app->session->get('auth_user_id');
        return $id ? (int)$id : null;
    }

    public static function requireLogin(): int
    {
        $id = self::userId();
        if ($id === null) {
            header('Location: /login');
            exit;
        }

        return $id;
    }

    public static function requireAdmin(): int
    {
        $id = self::requireLogin();
        $role = Application::$app->session->get('auth_user_role', 'user');

        if ($role !== 'admin') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }

        return $id;
    }
}
