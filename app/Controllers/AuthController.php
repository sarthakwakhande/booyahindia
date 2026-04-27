<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Application;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;

class AuthController
{
    public function login(Request $request, Response $response): string
    {
        return View::render('auth/login');
    }

    public function mobileOtp(Request $request, Response $response): string
    {
        $phone = (string)$request->input('phone');
        if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
            return $response->json(['status' => 'error', 'message' => 'Invalid phone number'], 422);
        }

        $otp = (string)random_int(100000, 999999);
        Application::$app->session->set('otp_phone', $phone);
        Application::$app->session->set('otp_code', $otp);
        Application::$app->session->set('otp_expiry', time() + 300);

        return $response->json(['status' => 'ok', 'message' => 'OTP sent', 'otp_debug' => $otp]);
    }

    public function verifyOtp(Request $request, Response $response): string
    {
        $otp = (string)$request->input('otp');
        $expected = (string)Application::$app->session->get('otp_code');
        $expiry = (int)Application::$app->session->get('otp_expiry', 0);

        if ($expiry < time()) {
            return $response->json(['status' => 'error', 'message' => 'OTP expired'], 401);
        }

        if (!hash_equals($expected, $otp)) {
            return $response->json(['status' => 'error', 'message' => 'Invalid OTP'], 401);
        }

        Application::$app->session->set('auth_user_id', 1);
        Application::$app->session->set('auth_user_role', 'admin');
        return $response->json(['status' => 'ok', 'message' => 'Login successful']);
    }

    public function googleRedirect(Request $request, Response $response): void
    {
        $response->redirect('/auth/google/callback?mock=1');
    }

    public function googleCallback(Request $request, Response $response): void
    {
        Application::$app->session->set('auth_user_id', 1);
        Application::$app->session->set('auth_user_role', 'user');
        $response->redirect('/tournaments');
    }

    public function logout(Request $request, Response $response): void
    {
        Application::$app->session->remove('auth_user_id');
        Application::$app->session->remove('auth_user_role');
        $response->redirect('/');
    }
}
