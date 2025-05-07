<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

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
            'title' => 'Admin Dashboard - Court Kart',
            'totalOrders' => $totalOrders,
            'totalSales' => $totalSales,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'recentOrders' => $recentOrders,
            'page_css' => 'admin', // Make sure admin.css is loaded
        ]);
    }

    /**
     * Display products management page
     */
    public function products()
    {
        // Get all products from database
        $products = Product::getAll();

        echo View::renderWithLayout('admin/products', 'admin', [
            'title' => 'Product Management - Court Kart',
            'products' => $products,
            'page_css' => 'admin',
            'page_js' => 'admin_products',
        ]);
    }
    
    /**
     * Save a product (create or update)
     */
    public function saveProduct()
    {
        // Validate CSRF token
        // ...
        
        $id = $_POST['id'] ?? null;
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0,
            'category' => $_POST['category'] ?? '',
            'image_url' => $_POST['image_url'] ?? '',
        ];
        
        // Validate product data
        if (empty($data['name']) || empty($data['description']) || 
            $data['price'] <= 0 || $data['stock'] < 0 || 
            empty($data['category']) || empty($data['image_url'])) {
            
            Session::set('error', 'Please fill in all required fields correctly.');
            header('Location: /admin/products');
            exit;
        }
        
        $success = false;
        
        if ($id) {
            // Update existing product
            $success = Product::update($id, $data);
            $message = 'Product updated successfully.';
        } else {
            // Add new product
            $success = Product::add($data);
            $message = 'Product added successfully.';
        }
        
        if ($success) {
            Session::set('success', $message);
        } else {
            Session::set('error', 'Failed to save product.');
        }
        
        header('Location: /admin/products');
        exit;
    }
    
    /**
     * Delete a product
     */
    public function deleteProduct()
    {
        // Validate CSRF token
        // ...
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            Session::set('error', 'Invalid product ID.');
            header('Location: /admin/products');
            exit;
        }
        
        $success = Product::delete($id);
        
        if ($success) {
            Session::set('success', 'Product deleted successfully.');
        } else {
            Session::set('error', 'Failed to delete product.');
        }
        
        header('Location: /admin/products');
        exit;
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
            'title' => 'Order Management - Court Kart',
            'orders' => $orders,
        ]);
    }

    /**
     * Display users management page
     */
    public function users()
    {
        // Get real users data from database
        $users = User::getAll(10);
        $totalUsers = User::getCount();

        echo View::renderWithLayout('admin/users', 'admin', [
            'title' => 'User Management - Court Kart',
            'users' => $users,
            'totalUsers' => $totalUsers,
            'page_css' => 'admin-users',
            'page_js' => 'admin-users',
        ]);
    }
}
