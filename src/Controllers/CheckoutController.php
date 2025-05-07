<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Models\Order;
use App\Models\Cart;
use Exception;

class CheckoutController
{
    /**
     * Display checkout page with order summary
     */
    public function index()
    {
        if (!Session::get('user_id')) {
            Session::flash('error', 'Please login to checkout');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $cartItems = Cart::getItems($userId);
        
        // Calculate totals and add product details
        $totalPrice = 0;
        foreach ($cartItems as &$item) {
            $product = \App\Models\Product::getById($item['product_id']);
            $item['name'] = $product['name'];
            $item['price'] = $product['price'];
            $item['image_url'] = $product['image_url'];
            $item['subtotal'] = $product['price'] * $item['quantity'];
            $totalPrice += $item['subtotal'];
        }
        
        if (empty($cartItems)) {
            Session::flash('error', 'Your cart is empty');
            header('Location: /cart');
            exit;
        }

        echo View::renderWithLayout('checkout/index', 'main', [
            'title' => 'Checkout - Court Kart',
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'page_css' => 'checkout',
            'page_js' => 'checkout',
        ]);
    }

    /**
     * Process the checkout form and create the order
     */
    public function process()
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

        // Calculate total
        $totalPrice = 0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = \App\Models\Product::getById($item['product_id']);
            if (!$product) {
                Session::flash('error', 'One of the products in your cart is no longer available');
                header('Location: /cart');
                exit;
            }
            
            // Check if there's enough stock
            if ($product['stock'] < $item['quantity']) {
                Session::flash('error', 'Not enough stock available for ' . $product['name']);
                header('Location: /cart');
                exit;
            }
            
            $itemPrice = $product['price'];
            $itemSubtotal = $itemPrice * $item['quantity'];
            $totalPrice += $itemSubtotal;
            
            // Build order item for database
            $orderItems[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $itemPrice
            ];
        }

        // Validate form data
        if (empty($_POST['shipping_address']) || empty($_POST['payment_method'])) {
            Session::flash('error', 'Please fill all required fields');
            header('Location: /checkout');
            exit;
        }

        // Store shipping and payment details
        $shippingAddress = $_POST['shipping_address'];
        $paymentMethod = $_POST['payment_method'];
        $email = Session::get('user_email');
        
        // Store additional data for order records
        $orderData = [
            'fullname' => $_POST['fullname'] ?? '',
            'email' => $_POST['email'] ?? $email,
            'phone' => $_POST['phone'] ?? '',
            'address' => $shippingAddress,
            'city' => $_POST['city'] ?? '',
            'state' => $_POST['state'] ?? '',
            'zip' => $_POST['zip'] ?? '',
            'country' => $_POST['country'] ?? '',
            'payment_method' => $paymentMethod
        ];
        
        // Store in session for the success page
        Session::set('checkout_data', $orderData);
        Session::set('checkout_total', $totalPrice);

        // Create the order - trigger will verify stock levels
        $orderId = Order::createOrder($userId, $orderItems, $totalPrice);
        
        if ($orderId) {
            try {
                // Finalize the order - stored procedure will empty cart
                Order::finalizeOrder($orderId, $userId);
                
                // Store order ID in session for success page
                Session::set('last_order_id', $orderId);
                
                // Redirect to success page
                header("Location: /checkout/success");
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
    
    /**
     * Display order success page
     */
    public function success()
    {
        $orderId = Session::get('last_order_id');
        
        // If no order ID in session, redirect to home
        if (!$orderId) {
            header('Location: /');
            exit;
        }
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data') ?? [];
        $totalPrice = Session::get('checkout_total') ?? 0;
        
        // Clear checkout session data
        Session::remove('checkout_data');
        Session::remove('checkout_total');
        
        // Keep order ID in session for one more request
        // Will be removed after displaying the success page
        $email = $checkoutData['email'] ?? Session::get('user_email');
        $paymentMethod = $checkoutData['payment_method'] ?? 'Credit Card';
        
        echo View::renderWithLayout('checkout/success', 'main', [
            'title' => 'Order Confirmed - Court Kart',
            'orderId' => $orderId,
            'email' => $email,
            'totalPrice' => $totalPrice,
            'paymentMethod' => $paymentMethod,
            'page_css' => 'checkout',
        ]);
        
        // Remove order ID from session after displaying the page
        Session::remove('last_order_id');
    }
}
