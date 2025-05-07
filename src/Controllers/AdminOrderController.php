<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Models\Order;

class AdminOrderController
{
    private $view;

    public function __construct()
    {
        $this->view = new View();
        
        // Check admin authentication
        if (!Session::get('is_admin')) {
            Session::flash('error', 'Admin access required');
            header('Location: /login');
            exit;
        }
    }

    /**
     * Show all orders in admin panel
     */
    public function index()
    {
        $orders = Order::getAll(50);
        
        return $this->view->render('admin/orders', [
            'orders' => $orders,
            'pageTitle' => 'Manage Orders'
        ]);
    }

    /**
     * Show order details in admin panel
     * @param int $id Order ID
     */
    public function show($id)
    {
        $orderDetails = Order::getOrderDetails($id);
        
        if (empty($orderDetails)) {
            Session::flash('error', 'Order not found');
            header('Location: /admin/orders');
            exit;
        }

        return $this->view->render('admin/order-details', [
            'orderDetails' => $orderDetails,
            'pageTitle' => 'Order #' . $id
        ]);
    }

    /**
     * Update order status
     * Triggers will handle stock updates if status changes to 'confirmed' or 'cancelled'
     */
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/orders');
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        
        if (Order::updateStatus($orderId, $status)) {
            Session::flash('success', 'Order status updated successfully');
        } else {
            Session::flash('error', 'Failed to update order status');
        }
        
        header('Location: /admin/orders');
        exit;
    }
}
