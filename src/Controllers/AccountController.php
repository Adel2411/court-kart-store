<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Helpers\Security;
use App\Models\Order;
use App\Models\User;
use App\Services\AuthService;

class AccountController
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService;
    }

    /**
     * Display the user account page
     *
     * @return void
     */
    public function index()
    {
        if (! Session::get('user_id')) {
            Session::flash('error', 'Please login to view your account');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $user = User::getById($userId);

        $orders = Order::getCustomerOrderHistory($userId);

        echo View::renderWithLayout('account/index', 'main', [
            'title' => 'My Account - Court Kart',
            'user' => $user,
            'orders' => $orders,
            'page_css' => 'account',
        ]);
    }

    /**
     * Display the user orders
     *
     * @return void
     */
    public function orders()
    {
        $userId = Session::get('user_id');

        $db = \App\Core\Database::getInstance();
        $orders = $db->fetchRows(
            'SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC',
            [$userId]
        );

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
     *
     * @return void
     */
    public function edit()
    {
        if (! Session::get('user_id')) {
            Session::set('error', 'You need to login first.');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $user = User::getById($userId);

        $csrfToken = Security::generateCsrfToken();

        echo View::renderWithLayout('account/edit', 'main', [
            'title' => 'Edit Profile - Court Kart',
            'user' => $user,
            'csrf_token' => $csrfToken,
            'success' => Session::flash('success'),
            'error' => Session::flash('error'),
            'page_css' => 'account',
            'page_js' => 'account-edit',
        ]);
    }

    /**
     * Process profile update
     *
     * @return void
     */
    public function update()
    {
        if (! Session::get('user_id')) {
            Session::set('error', 'You need to login first.');
            header('Location: /login');
            exit;
        }

        if (! isset($_POST['csrf_token']) || ! Security::verifyCsrfToken($_POST['csrf_token'])) {
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

        if (empty($name) || empty($email)) {
            Session::set('error', 'Name and email are required.');
            header('Location: /account/edit');
            exit;
        }

        $db = \App\Core\Database::getInstance();

        $existingUser = $db->fetchRow(
            'SELECT id FROM users WHERE email = ? AND id != ?',
            [$email, $userId]
        );

        if ($existingUser) {
            Session::set('error', 'Email is already in use by another user.');
            header('Location: /account/edit');
            exit;
        }

        $user = User::getById($userId);

        if (! empty($newPassword)) {
            if (empty($currentPassword) || ! Security::verifyPassword($currentPassword, $user['password'])) {
                Session::set('error', 'Current password is incorrect.');
                header('Location: /account/edit');
                exit;
            }

            if (strlen($newPassword) < 8) {
                Session::set('error', 'New password must be at least 8 characters long.');
                header('Location: /account/edit');
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                Session::set('error', 'New passwords do not match.');
                header('Location: /account/edit');
                exit;
            }

            $hashedPassword = Security::hashPassword($newPassword);
            $db->execute(
                'UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?',
                [$name, $email, $hashedPassword, $userId]
            );
        } else {
            $db->execute(
                'UPDATE users SET name = ?, email = ?, profile_image = ? WHERE id = ?',
                [$name, $email, $profileImage, $userId]
            );
        }

        $success = $db->execute(
            'UPDATE users SET name = ?, email = ?, profile_image = ? WHERE id = ?',
            [$name, $email, $profileImage, $userId]
        );

        if ($success) {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['profile_image'] = $profileImage;

            Session::set('success', 'Profile updated successfully.');
            header('Location: /account');
            exit;
        }

        Session::set('user_name', $name);
        Session::set('user_email', $email);
        Session::set('profile_image', $profileImage);

        Session::set('success', 'Your profile has been updated successfully.');
        header('Location: /account');
        exit;
    }

    /**
     * Update user profile
     *
     * @return void
     */
    public function updateProfile()
    {
        if (! isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $profileImage = $_POST['profile_image'] ?? '';

        $db = \App\Core\Database::getInstance();
        $success = $db->execute(
            'UPDATE users SET name = ?, email = ?, profile_image = ? WHERE id = ?',
            [$name, $email, $profileImage, $userId]
        );

        if ($success) {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['profile_image'] = $profileImage;

            Session::set('success', 'Profile updated successfully.');
            header('Location: /account');
            exit;
        } else {
            Session::set('error', 'Failed to update profile. Please try again.');
            header('Location: /account/edit');
            exit;
        }
    }
}
