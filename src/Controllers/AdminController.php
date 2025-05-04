<?php

namespace App\Controllers;

use App\Core\View;

class AdminController
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        echo View::renderWithLayout('admin/index', 'admin', [
            'title' => 'Admin Dashboard - Court Kart Store'
        ]);
    }
    
    /**
     * Display products management page
     */
    public function products()
    {
        echo View::renderWithLayout('admin/products', 'admin', [
            'title' => 'Product Management - Court Kart Store'
        ]);
    }
    
    /**
     * Display orders management page
     */
    public function orders()
    {
        echo View::renderWithLayout('admin/orders', 'admin', [
            'title' => 'Order Management - Court Kart Store'
        ]);
    }
    
    /**
     * Display users management page
     */
    public function users()
    {
        echo View::renderWithLayout('admin/users', 'admin', [
            'title' => 'User Management - Court Kart Store'
        ]);
    }
}
