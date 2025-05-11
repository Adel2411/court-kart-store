<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Models\Order;
use App\Models\User;
use App\Services\AuthService;
use App\Helpers\Security;

class AccountController
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService;
    }

    /**
     * Display the user account page
     */
    public function index()
    {
        $userId = Session::get('user_id');
        $user = User::getById($userId);

        echo View::renderWithLayout('account/index', 'main', [
            'title' => 'My Account - Court Kart',
            'user' => $user,
            'page_css' => 'account'
        ]);
    }

    /**
     * Display the user orders
     */
    public function orders()
    {
        $userId = Session::get('user_id');

        // Get user orders from database
        $db = \App\Core\Database::getInstance();
        $orders = $db->fetchRows(
            'SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC',
            [$userId]
        );

        // Get items count for each order
        foreach ($orders as &$order) {
            $order['items_count'] = Order::getItemsCount($order['id']);
        }

        echo View::renderWithLayout('account/orders', 'main', [
            'title' => 'My Orders - Court Kart',
            'orders' => $orders,
        ]);
    }

    /**
     * Display the edit profile form
     */
    public function edit()
    {
        // Check if user is logged in
        if (!Session::get('user_id')) {
            Session::set('error', 'You need to login first.');
            header('Location: /login');
            exit;
        }
        
        $userId = Session::get('user_id');
        $user = User::getById($userId);
        
        // Generate CSRF token for the form
        $csrfToken = Security::generateCsrfToken();
        
        echo View::renderWithLayout('account/edit', 'main', [
            'title' => 'Edit Profile - Court Kart',
            'user' => $user,
            'csrf_token' => $csrfToken,
            'success' => Session::flash('success'),
            'error' => Session::flash('error'),
            'page_css' => 'account',
            'page_js' => 'account-edit'
        ]);
    }
    
    /**
     * Process profile update
     */
    public function update()
    {
        // Check if user is logged in
        if (!Session::get('user_id')) {
            Session::set('error', 'You need to login first.');
            header('Location: /login');
            exit;
        }
        
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
            Session::set('error', 'Invalid form submission.');
            header('Location: /account/edit');
            exit;
        }
        
        $userId = Session::get('user_id');
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $profileImage = $_POST['profile_image'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate input
        if (empty($name) || empty($email)) {
            Session::set('error', 'Name and email are required.');
            header('Location: /account/edit');
            exit;
        }
        
        $db = \App\Core\Database::getInstance();
        
        // Check if email is already in use by another user
        $existingUser = $db->fetchRow(
            'SELECT id FROM users WHERE email = ? AND id != ?',
            [$email, $userId]
        );
        
        if ($existingUser) {
            Session::set('error', 'Email is already in use by another user.');
            header('Location: /account/edit');
            exit;
        }
        
        // Get current user data to verify password
        $user = User::getById($userId);
        
        // If password change is requested
        if (!empty($newPassword)) {
            // Verify current password
            if (empty($currentPassword) || !Security::verifyPassword($currentPassword, $user['password'])) {
                Session::set('error', 'Current password is incorrect.');
                header('Location: /account/edit');
                exit;
            }
            
            // Validate new password
            if (strlen($newPassword) < 8) {
                Session::set('error', 'New password must be at least 8 characters long.');
                header('Location: /account/edit');
                exit;
            }
            
            // Check if passwords match
            if ($newPassword !== $confirmPassword) {
                Session::set('error', 'New passwords do not match.');
                header('Location: /account/edit');
                exit;
            }
            
            // Update user data with new password
            $hashedPassword = Security::hashPassword($newPassword);
            $db->execute(
                'UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?',
                [$name, $email, $hashedPassword, $userId]
            );
        } else {
            // Update user data without changing password
            $db->execute(
                'UPDATE users SET name = ?, email = ?, profile_image = ? WHERE id = ?',
                [$name, $email, $profileImage, $userId]
            );
        }
        
        // Update session data
        Session::set('user_name', $name);
        Session::set('user_email', $email);
        Session::set('profile_image', $profileImage);
        
        Session::set('success', 'Your profile has been updated successfully.');
        header('Location: /account');
        exit;
    }
}
