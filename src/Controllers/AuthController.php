<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Helpers\Security;
use App\Models\User;
use App\Services\AuthService;

class AuthController
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService;

        // Redirect to home if already logged in (except for logout)
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($this->authService->isLoggedIn() &&
            $currentPath !== '/logout' &&
            ! in_array($currentPath, ['/account', '/profile'])
        ) {
            header('Location: /');
            exit;
        }
    }

    /**
     * Display the login form
     */
    public function loginForm()
    {
        // Generate CSRF token for the form
        $csrfToken = Security::generateCsrfToken();

        echo View::renderWithLayout('auth/login', 'main', [
            'title' => 'Login - Court Kart',
            'csrf_token' => $csrfToken,
            'error' => Session::flash('error'),
            'success' => Session::flash('success'),
            'page_css' => 'auth' // Explicitly set auth CSS
        ]);
    }

    /**
     * Process login form submission
     */
    public function login()
    {
        // Verify CSRF token
        if (! isset($_POST['csrf_token']) ||
            ! Security::verifyCsrfToken($_POST['csrf_token'])
        ) {
            Session::set('error', 'Invalid form submission.');
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember_me']);

        // Validate input
        if (empty($email) || empty($password)) {
            Session::set('error', 'Email and password are required.');
            header('Location: /login');
            exit;
        }

        // Attempt login
        if ($this->authService->login($email, $password, $remember)) {
            // Fetch user data including profile image
            $db = \App\Core\Database::getInstance();
            $user = $db->fetchRow('SELECT id, name, email, role, profile_image FROM users WHERE email = ?', [$email]);

            if ($user) {
                // Store all necessary user data in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['profile_image'] = $user['profile_image']; // Ensure profile image is stored in session

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /admin');
                } else {
                    header('Location: /');
                }
                exit;
            }
        } else {
            Session::set('error', 'Invalid email or password.');
            header('Location: /login');
        }
        exit;
    }

    /**
     * Display the registration form
     */
    public function registerForm()
    {
        // Generate CSRF token for the form
        $csrfToken = Security::generateCsrfToken();

        echo View::renderWithLayout('auth/register', 'main', [
            'title' => 'Register - Court Kart',
            'csrf_token' => $csrfToken,
            'error' => Session::flash('error'),
            'page_css' => 'auth' // Explicitly set auth CSS
        ]);
    }

    /**
     * Process registration form submission
     */
    public function register()
    {
        // Verify CSRF token
        if (! isset($_POST['csrf_token']) ||
            ! Security::verifyCsrfToken($_POST['csrf_token'])
        ) {
            Session::set('error', 'Invalid form submission.');
            header('Location: /register');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate input
        if (empty($name) || empty($email) || empty($password)) {
            Session::set('error', 'All fields are required.');
            header('Location: /register');
            exit;
        }

        if ($password !== $confirmPassword) {
            Session::set('error', 'Passwords do not match.');
            header('Location: /register');
            exit;
        }

        // Check if email is already in use
        $db = \App\Core\Database::getInstance();
        $existingUser = $db->fetchRow('SELECT id FROM users WHERE email = ?', [$email]);

        if ($existingUser) {
            Session::set('error', 'Email is already in use.');
            header('Location: /register');
            exit;
        }

        // Create user
        $hashedPassword = Security::hashPassword($password);

        $db->execute(
            "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())",
            [$name, $email, $hashedPassword]
        );

        $userId = $db->getLastInsertId();

        // Log user creation with error handling
        try {
            $db->execute(
                'INSERT INTO logs (action, user_id, message) VALUES (?, ?, ?)',
                ['USER_REGISTER', $userId, 'New user registered: '.$email]
            );
        } catch (\Exception $e) {
            error_log('Failed to log user registration: ' . $e->getMessage());
        }

        // Redirect to login with success message
        Session::set('success', 'Registration successful! You can now log in.');
        header('Location: /login');
        exit;
    }

    /**
     * Log the user out
     */
    public function logout()
    {
        $this->authService->logout();

        Session::set('success', 'You have been logged out successfully.');
        header('Location: /login');
        exit;
    }
}
