<?php

namespace App\Helpers;

use App\Core\Session;

class Security
{
    /**
     * Hash a password using PHP's password_hash function
     *
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify if a password matches a hash
     *
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool True if password matches hash
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate a CSRF token and store it in the session
     *
     * @return string The CSRF token
     */
    public static function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
        return $token;
    }

    /**
     * Verify if the provided CSRF token is valid
     *
     * @param string $token The token to verify
     * @return bool True if token is valid
     */
    public static function verifyCsrfToken(string $token): bool
    {
        $storedToken = Session::get('csrf_token');
        
        if (!$storedToken || $token !== $storedToken) {
            return false;
        }
        
        // Regenerate token after verification for enhanced security
        self::generateCsrfToken();
        return true;
    }

    /**
     * Generate a secure random token
     *
     * @param int $length Length of the token
     * @return string Random token
     */
    public static function generateRandomToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Create a remember me cookie
     *
     * @param int $userId User ID
     * @param string $token Remember me token
     * @param int $days Cookie expiration in days
     * @return bool True if cookie was set
     */
    public static function createRememberCookie(int $userId, string $token, int $days = 30): bool
    {
        $expiry = time() + 60 * 60 * 24 * $days;
        $value = "$userId:$token";
        
        return setcookie(
            'remember_me',
            $value,
            [
                'expires' => $expiry,
                'path' => '/',
                'domain' => '',
                'secure' => true, // Only send over HTTPS
                'httponly' => true, // Not accessible via JavaScript
                'samesite' => 'Strict' // CSRF protection
            ]
        );
    }

    /**
     * Remove the remember me cookie
     *
     * @return bool True if cookie was removed
     */
    public static function removeRememberCookie(): bool
    {
        return setcookie(
            'remember_me',
            '',
            [
                'expires' => time() - 3600, 
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
    }

    /**
     * Parse the remember me cookie value
     *
     * @return array|null Array with user_id and token, or null if invalid
     */
    public static function parseRememberCookie(): ?array
    {
        if (empty($_COOKIE['remember_me'])) {
            return null;
        }

        $parts = explode(':', $_COOKIE['remember_me'], 2);
        if (count($parts) !== 2) {
            self::removeRememberCookie();
            return null;
        }

        return [
            'user_id' => (int)$parts[0],
            'token' => $parts[1],
        ];
    }
}
