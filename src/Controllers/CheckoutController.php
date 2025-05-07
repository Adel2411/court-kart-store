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
        foreach ($cartItems as $item) {
            $product = \App\Models\Product::getById($item['product_id']);
            $totalPrice += $product['price'] * $item['quantity'];
        }

        // Validate form data
        if (empty($_POST['shipping_address']) || empty($_POST['payment_method'])) {
            Session::flash('error', 'Please fill all required fields');
            header('Location: /checkout');
            exit;
        }

        // Store shipping and payment details (could be saved to database in a real implementation)
        $shippingAddress = $_POST['shipping_address'];
        $paymentMethod = $_POST['payment_method'];

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
}
