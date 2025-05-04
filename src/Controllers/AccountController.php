<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
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
     */
    public function index()
    {
        $userId = Session::get('user_id');
        $user = User::getById($userId);

        echo View::renderWithLayout('account/index', 'main', [
            'title' => 'My Account - Court Kart',
            'user' => $user,
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
}
