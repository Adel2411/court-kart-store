<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Models\Order;
use App\Models\Cart;
use Exception;

class OrderController
{
    private $view;

    public function __construct()
    {
        $this->view = new View();
    }
    
    /**
     * Display orders for the logged-in user
     */
    public function index()
    {
        if (!Session::get('user_id')) {
            Session::flash('error', 'Please login to view your orders');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $orders = Order::getCustomerOrderHistory($userId);

        echo View::renderWithLayout('orders/index', 'main', [
            'title' => 'My Orders - Court Kart',
            'orders' => $orders,
            'page_css' => 'account',
            'page_js' => 'orders',
        ]);
    }

    /**
     * Show order history for the logged-in user
     */
    public function history()
    {
        if (!Session::get('user_id')) {
            Session::flash('error', 'Please login to view your orders');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $orders = Order::getCustomerOrderHistory($userId);

        return $this->view->render('orders/history', [
            'orders' => $orders,
            'pageTitle' => 'Order History'
        ]);
    }

    /**
     * Show details for a specific order
     * @param int $id Order ID
     */
    public function show($id)
    {
        if (!Session::get('user_id')) {
            Session::flash('error', 'Please login to view your order');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        
        // Cast the ID to integer to ensure it's treated properly
        $orderId = (int)$id;
        
        // Get order details
        $orderDetails = Order::getOrderDetails($orderId);
        
        if (empty($orderDetails)) {
            // Order not found
            Session::flash('error', 'Order not found');
            echo View::renderWithLayout('orders/show', 'main', [
                'title' => 'Order Not Found - Court Kart',
                'order' => null,
                'orderItems' => [],
                'page_css' => 'orders',
                'page_js' => 'orders',
                'error' => 'Order not found'
            ]);
            return;
        }
        
        // Check if order belongs to the user
        if ($orderDetails[0]['user_id'] != $userId) {
            // User doesn't have permission
            Session::flash('error', 'You do not have permission to view this order');
            echo View::renderWithLayout('orders/show', 'main', [
                'title' => 'Access Denied - Court Kart',
                'order' => null,
                'orderItems' => [],
                'page_css' => 'orders',
                'page_js' => 'orders',
                'error' => 'You do not have permission to view this order'
            ]);
            return;
        }

        // Process order details
        $order = (object)$orderDetails[0];
        $orderItems = [];
        
        foreach ($orderDetails as $item) {
            if (isset($item['product_id'])) {
                $orderItems[] = (object)[
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'] ?? 'Unknown Product',
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 0,
                    'image_url' => $item['image_url'] ?? '',
                    'subtotal' => ($item['price'] ?? 0) * ($item['quantity'] ?? 0)
                ];
            }
        }

        // Calculate order subtotal, shipping cost, and total
        $subtotal = 0;
        foreach ($orderItems as $item) {
            $subtotal += $item->subtotal;
        }
        
        $shippingCost = 0; // Free shipping
        $total = $subtotal + $shippingCost;
        
        $order->subtotal = $subtotal;
        $order->shipping_cost = $shippingCost;
        $order->total = $total;
        
        echo View::renderWithLayout('orders/show', 'main', [
            'title' => 'Order #' . $id . ' - Court Kart',
            'order' => $order,
            'orderItems' => $orderItems,
            'page_css' => 'orders',
            'page_js' => 'orders',
        ]);
    }

    /**
     * Process checkout and create a new order
     */
    public function checkout()
    {
        if (!Session::get('user_id')) {
            Session::flash('error', 'Please login to checkout');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $cartItems = Cart::getItems($userId);
        
        if (empty($cartItems)) {
            Session::flash('error', 'Your cart is empty');
            header('Location: /cart');
            exit;
        }

        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate form data here
            
            // Calculate total
            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            // Create the order - trigger will verify stock levels
            $orderId = Order::createOrder($userId, $cartItems, $totalPrice);
            
            if ($orderId) {
                try {
                    // Finalize the order - stored procedure will empty cart
                    Order::finalizeOrder($orderId, $userId);
                    Session::flash('success', 'Order placed successfully!');
                    header("Location: /orders/{$orderId}");
                    exit;
                } catch (Exception $e) {
                    Session::flash('error', 'Error finalizing order: ' . $e->getMessage());
                    header('Location: /cart');
                    exit;
                }
            } else {
                Session::flash('error', 'Failed to create order. Some items may be out of stock.');
                header('Location: /cart');
                exit;
            }
        }

        // Display checkout form
        return $this->view->render('orders/checkout', [
            'cartItems' => $cartItems,
            'pageTitle' => 'Checkout'
        ]);
    }

    /**
     * Cancel an order
     * @param int $id Order ID
     */
    public function cancel($id)
    {
        if (!Session::get('user_id')) {
            Session::flash('error', 'Please login to manage your orders');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $orderId = (int)$id;
        
        // Get cancellation reason from the form
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : 'No reason provided';
        
        if (Order::cancelOrder($orderId, $userId, $reason)) {
            Session::flash('success', 'Order cancelled successfully');
        } else {
            Session::flash('error', 'Failed to cancel order');
        }
        
        header('Location: /orders');
        exit;
    }
}
