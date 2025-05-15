<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Models\Cart;
use App\Models\Order;
use Exception;

class CheckoutController
{
    /**
     * Display checkout page with order summary
     */
    public function index()
    {
        if (! Session::get('user_id')) {
            Session::flash('error', 'Please login to checkout');
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $cartItems = Cart::getItems($userId);

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
        if (! Session::get('user_id')) {
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

        $totalPrice = 0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = \App\Models\Product::getById($item['product_id']);
            if (! $product) {
                Session::flash('error', 'One of the products in your cart is no longer available');
                header('Location: /cart');
                exit;
            }

            if ($product['stock'] < $item['quantity']) {
                Session::flash('error', 'Not enough stock available for '.$product['name']);
                header('Location: /cart');
                exit;
            }

            $itemPrice = $product['price'];
            $itemSubtotal = $itemPrice * $item['quantity'];
            $totalPrice += $itemSubtotal;

            $orderItems[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $itemPrice,
            ];
        }

        if (empty($_POST['shipping_address']) || empty($_POST['payment_method'])) {
            Session::flash('error', 'Please fill all required fields');
            header('Location: /checkout');
            exit;
        }

        $shippingAddress = $_POST['shipping_address'];
        $paymentMethod = $_POST['payment_method'];
        $email = Session::get('user_email');

        $orderData = [
            'fullname' => $_POST['fullname'] ?? '',
            'email' => $_POST['email'] ?? $email,
            'phone' => $_POST['phone'] ?? '',
            'address' => $shippingAddress,
            'city' => $_POST['city'] ?? '',
            'state' => $_POST['state'] ?? '',
            'zip' => $_POST['zip'] ?? '',
            'country' => $_POST['country'] ?? '',
            'payment_method' => $paymentMethod,
        ];

        Session::set('checkout_data', $orderData);
        Session::set('checkout_total', $totalPrice);

        $orderId = Order::createOrder($userId, $orderItems, $totalPrice);

        if ($orderId) {
            try {
                Order::finalizeOrder($orderId, $userId);

                Session::set('last_order_id', $orderId);

                header('Location: /checkout/success');
                exit;
            } catch (Exception $e) {
                Session::flash('error', 'Error finalizing order: '.$e->getMessage());
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

        if (! $orderId) {
            header('Location: /');
            exit;
        }

        $checkoutData = Session::get('checkout_data') ?? [];
        $totalPrice = Session::get('checkout_total') ?? 0;

        Session::remove('checkout_data');
        Session::remove('checkout_total');

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

        Session::remove('last_order_id');
    }
}
