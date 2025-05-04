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
        $authService = new AuthService();
        
        if (!$authService->isLoggedIn()) {
            // Store the requested URL for redirect after login
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            
            // Redirect to login page
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
        $authService = new AuthService();
        
        if (!$authService->isLoggedIn()) {
            // Store the requested URL for redirect after login
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            
            // Redirect to login page
            header('Location: /login');
            exit;
        }
        
        if (!$authService->isAdmin()) {
            // User is logged in but not an admin
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
        $authService = new AuthService();
        
        if ($authService->isLoggedIn()) {
            // User is already logged in, redirect to home
            header('Location: /');
            exit;
        }
        
        return true;
    }
}
