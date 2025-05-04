<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class AdminController
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Get real dashboard statistics from database
        $totalOrders = Order::getCount();
        $totalSales = Order::getTotalSales();
        $totalUsers = User::getCount();
        $totalProducts = Product::getCount();
        
        // Get recent orders
        $recentOrders = Order::getRecent(5);
        
        echo View::renderWithLayout('admin/index', 'admin', [
            'title' => 'Admin Dashboard - Court Kart Store',
            'totalOrders' => $totalOrders,
            'totalSales' => $totalSales,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'recentOrders' => $recentOrders
        ]);
    }
    
    /**
     * Display products management page
     */
    public function products()
    {
        // Get real products data from database
        $products = Product::getAll(10);
        
        echo View::renderWithLayout('admin/products', 'admin', [
            'title' => 'Product Management - Court Kart Store',
            'products' => $products
        ]);
    }
    
    /**
     * Display orders management page
     */
    public function orders()
    {
        // Get real orders data from database
        $orders = Order::getAll(10);
        
        // Get items count for each order
        foreach ($orders as &$order) {
            $order['items_count'] = Order::getItemsCount($order['id']);
        }
        
        echo View::renderWithLayout('admin/orders', 'admin', [
            'title' => 'Order Management - Court Kart Store',
            'orders' => $orders
        ]);
    }
    
    /**
     * Display users management page
     */
    public function users()
    {
        // Get real users data from database
        $users = User::getAll(10);
        
        echo View::renderWithLayout('admin/users', 'admin', [
            'title' => 'User Management - Court Kart Store',
            'users' => $users
        ]);
    }
}
