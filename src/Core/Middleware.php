<?php

namespace App\Core;

use App\Services\AuthService;

class Middleware
{
    /**
     * Check if user is authenticated
     *
     * @return bool True if authenticated
     */
    public static function auth(): bool
    {
        $authService = new AuthService;

        if (! $authService->isLoggedIn()) {
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit;
        }

        return true;
    }

    /**
     * Check if user is an admin
     *
     * @return bool True if admin
     */
    public static function admin(): bool
    {
        $authService = new AuthService;

        if (! $authService->isLoggedIn()) {
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit;
        }

        if (! $authService->isAdmin()) {
            header('Location: /unauthorized');
            exit;
        }

        return true;
    }

    /**
     * Check if user is a guest (not logged in)
     *
     * @return bool True if guest
     */
    public static function guest(): bool
    {
        $authService = new AuthService;

        if ($authService->isLoggedIn()) {
            header('Location: /');
            exit;
        }

        return true;
    }
}
