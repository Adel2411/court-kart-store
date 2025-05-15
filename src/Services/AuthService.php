<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Session;
use App\Helpers\Security;
use App\Models\User;

class AuthService
{
    /**
     * Attempt to authenticate a user
     *
     * @param  string  $email  User email
     * @param  string  $password  User password
     * @param  bool  $remember  Whether to set remember me cookie
     * @return bool True if authentication succeeded
     */
    public function login(string $email, string $password, bool $remember = false): bool
    {
        $db = Database::getInstance();

        $sql = 'SELECT * FROM users WHERE email = ?';
        $user = $db->fetchRow($sql, [$email]);

        if (! $user) {
            return false;
        }

        if (! Security::verifyPassword($password, $user['password'])) {
            return false;
        }

        $this->setUserSession($user);

        if ($remember) {
            $this->createRememberToken($user['id']);
        }

        $db->execute(
            'INSERT INTO logs (action, user_id, message) VALUES (?, ?, ?)',
            ['USER_LOGIN', $user['id'], 'User logged in successfully']
        );

        return true;
    }

    /**
     * Log the user out
     */
    public function logout(): void
    {
        $userId = Session::get('user_id');

        Session::destroy();

        Security::removeRememberCookie();

        if ($userId) {
            $db = Database::getInstance();

            try {
                $db->execute(
                    'UPDATE users SET remember_token = NULL WHERE id = ?',
                    [$userId]
                );

                $db->execute(
                    'INSERT INTO logs (action, user_id, message) VALUES (?, ?, ?)',
                    ['USER_LOGOUT', $userId, 'User logged out']
                );
            } catch (\PDOException $e) {
                $db->execute(
                    'INSERT INTO logs (action, user_id, message) VALUES (?, ?, ?)',
                    ['USER_LOGOUT', $userId, 'User logged out']
                );
            }
        }
    }

    /**
     * Check if current user is authenticated
     *
     * @return bool True if user is logged in
     */
    public function isLoggedIn(): bool
    {
        if (Session::has('user_id')) {
            return true;
        }

        return $this->checkRememberMe();
    }

    /**
     * Check if current user is an admin
     *
     * @return bool True if user is admin
     */
    public function isAdmin(): bool
    {
        if (! $this->isLoggedIn()) {
            return false;
        }

        return Session::get('user_role') === 'admin';
    }

    /**
     * Get current authenticated user
     *
     * @return array|null User data or null if not logged in
     */
    public function getCurrentUser(): ?array
    {
        if (! $this->isLoggedIn()) {
            return null;
        }

        $userId = Session::get('user_id');

        return User::getById($userId);
    }

    /**
     * Check and process remember me cookie
     *
     * @return bool True if remember me authentication succeeded
     */
    private function checkRememberMe(): bool
    {
        $cookieData = Security::parseRememberCookie();
        if (! $cookieData) {
            return false;
        }

        $db = Database::getInstance();

        $user = $db->fetchRow(
            'SELECT * FROM users WHERE id = ? AND remember_token IS NOT NULL',
            [$cookieData['user_id']]
        );

        if (! $user || $user['remember_token'] !== $cookieData['token']) {
            Security::removeRememberCookie();

            return false;
        }

        $this->setUserSession($user);

        $this->refreshRememberToken($user['id']);

        return true;
    }

    /**
     * Store user data in session
     *
     * @param  array  $user  User data
     */
    private function setUserSession(array $user): void
    {
        Session::regenerate();

        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['role']);

        Session::set('last_activity', time());
    }

    /**
     * Create a remember me token and store it in cookie and database
     *
     * @param  int  $userId  User ID
     * @return bool True on success
     */
    private function createRememberToken(int $userId): bool
    {
        $token = Security::generateRandomToken(64);
        $db = Database::getInstance();

        $success = $db->execute(
            'UPDATE users SET remember_token = ? WHERE id = ?',
            [$token, $userId]
        );

        if ($success) {
            return Security::createRememberCookie($userId, $token);
        }

        return false;
    }

    /**
     * Refresh the remember me token for security
     *
     * @param  int  $userId  User ID
     * @return bool True on success
     */
    private function refreshRememberToken(int $userId): bool
    {
        return $this->createRememberToken($userId);
    }
}
