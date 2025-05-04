<?php

namespace App\Controllers;

use App\Core\View;

class AuthController
{
    /**
     * Display the login form
     */
    public function loginForm()
    {
        echo View::renderWithLayout('auth/login', 'main', [
            'title' => 'Login - Court Kart Store'
        ]);
    }
    
    /**
     * Process login form submission
     */
    public function login()
    {
        // In a real app, we would validate credentials and authenticate the user
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // For demo purposes, just redirect to home
        header('Location: /');
        exit;
    }
    
    /**
     * Display the registration form
     */
    public function registerForm()
    {
        echo View::renderWithLayout('auth/register', 'main', [
            'title' => 'Register - Court Kart Store'
        ]);
    }
    
    /**
     * Process registration form submission
     */
    public function register()
    {
        // In a real app, we would validate input and create a new user
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // For demo purposes, just redirect to login
        header('Location: /login');
        exit;
    }
    
    /**
     * Log the user out
     */
    public function logout()
    {
        // In a real app, we would destroy the session
        
        // Redirect to home
        header('Location: /');
        exit;
    }
}
