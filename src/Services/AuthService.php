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

        // Get user by email
        $sql = 'SELECT * FROM users WHERE email = ?';
        $user = $db->fetchRow($sql, [$email]);

        if (! $user) {
            return false;
        }

        // Verify password
        if (! Security::verifyPassword($password, $user['password'])) {
            return false;
        }

        // Store user in session
        $this->setUserSession($user);

        // Create remember me token if requested
        if ($remember) {
            $this->createRememberToken($user['id']);
        }

        // Log successful login
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
        // Get user ID before destroying session
        $userId = Session::get('user_id');

        // Clear session
        Session::destroy();

        // Remove remember me cookie
        Security::removeRememberCookie();

        // Clear remember token from database if user was logged in and column exists
        if ($userId) {
            $db = Database::getInstance();

            // Check if remember_token column exists in the users table
            try {
                $db->execute(
                    'UPDATE users SET remember_token = NULL WHERE id = ?',
                    [$userId]
                );

                // Log logout
                $db->execute(
                    'INSERT INTO logs (action, user_id, message) VALUES (?, ?, ?)',
                    ['USER_LOGOUT', $userId, 'User logged out']
                );
            } catch (\PDOException $e) {
                // Column doesn't exist or other DB error - just log logout
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
        // First check session
        if (Session::has('user_id')) {
            return true;
        }

        // Then check remember me cookie
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

        // Get user with remember token
        $user = $db->fetchRow(
            'SELECT * FROM users WHERE id = ? AND remember_token IS NOT NULL',
            [$cookieData['user_id']]
        );

        if (! $user || $user['remember_token'] !== $cookieData['token']) {
            Security::removeRememberCookie();

            return false;
        }

        // User is authenticated via remember me
        $this->setUserSession($user);

        // Refresh the remember me token for security
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
        // Regenerate session ID to prevent session fixation
        Session::regenerate();

        // Store user data in session
        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['role']);

        // Set last activity time for session timeout
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

        // Update user with remember token
        $success = $db->execute(
            'UPDATE users SET remember_token = ? WHERE id = ?',
            [$token, $userId]
        );

        if ($success) {
            // Set the cookie
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
        // Generate new token
        return $this->createRememberToken($userId);
    }
}
