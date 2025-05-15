<?php

namespace App\Core;

class Session
{
    /**
     * Start a new session if one doesn't exist
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session value
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     */
    public static function get(string $key, $default = null)
    {
        self::start();

        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a session key exists
     */
    public static function has(string $key): bool
    {
        self::start();

        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session value
     */
    public static function remove(string $key): void
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Get and remove a session value (flash message pattern)
     */
    public static function flash(string $key, $default = null)
    {
        $value = self::get($key, $default);
        self::remove($key);

        return $value;
    }

    /**
     * Destroy the current session
     */
    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();

            // Also remove the session cookie
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
        }
    }

    /**
     * Regenerate the session ID
     */
    public static function regenerate(bool $deleteOldSession = true): bool
    {
        self::start();

        return session_regenerate_id($deleteOldSession);
    }
}
