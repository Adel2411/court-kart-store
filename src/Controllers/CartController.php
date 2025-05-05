<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Models\Cart;
use App\Models\Product;

class CartController
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $userId = Session::get('user_id');
        
        // Get cart items from database
        $cartItems = Cart::getItems($userId);
        $totalPrice = 0;
        
        // Calculate total price and add product details
        foreach ($cartItems as &$item) {
            $product = Product::getById($item['product_id']);
            $item['name'] = $product['name'];
            $item['price'] = $product['price'];
            $item['image_url'] = $product['image_url'];
            $item['subtotal'] = $product['price'] * $item['quantity'];
            $totalPrice += $item['subtotal'];
        }

        echo View::renderWithLayout('cart/index', 'main', [
            'title' => 'Your Cart - Court Kart',
            'cart_items' => $cartItems,
            'total_price' => $totalPrice,
            'page_css' => 'cart',
            'page_js' => 'cart',
        ]);
    }

    /**
     * Add an item to the cart
     */
    public function add()
    {
        $userId = Session::get('user_id');
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        // Validate input
        if (!$productId || $quantity < 1) {
            Session::set('error', 'Invalid product or quantity.');
            header('Location: /shop');
            exit;
        }
        
        // Check product exists and has enough stock
        $product = Product::getById($productId);
        if (!$product) {
            Session::set('error', 'Product not found.');
            header('Location: /shop');
            exit;
        }
        
        if ($quantity > $product['stock']) {
            Session::set('error', 'Not enough stock available.');
            header('Location: /shop/product/' . $productId);
            exit;
        }
        
        // Add to cart
        $success = Cart::addItem($userId, $productId, $quantity);
        
        if ($success) {
            Session::set('success', 'Product added to your cart.');
        } else {
            Session::set('error', 'Failed to add product to cart.');
        }
        
        // Redirect back to previous page or cart
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/cart';
        header('Location: ' . $redirect);
        exit;
    }

    /**
     * Update an item in the cart
     */
    public function update()
    {
        $userId = Session::get('user_id');
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        // Validate input
        if (!$productId) {
            Session::set('error', 'Invalid product.');
            header('Location: /cart');
            exit;
        }
        
        // If quantity is 0 or less, remove item
        if ($quantity <= 0) {
            return $this->remove();
        }
        
        // Check product exists and has enough stock
        $product = Product::getById($productId);
        if (!$product || $quantity > $product['stock']) {
            Session::set('error', 'Not enough stock available.');
            header('Location: /cart');
            exit;
        }
        
        // Update cart item
        $success = Cart::updateItem($userId, $productId, $quantity);
        
        if ($success) {
            Session::set('success', 'Cart updated successfully.');
        } else {
            Session::set('error', 'Failed to update cart.');
        }
        
        header('Location: /cart');
        exit;
    }

    /**
     * Remove an item from the cart
     */
    public function remove()
    {
        $userId = Session::get('user_id');
        $productId = $_POST['product_id'] ?? null;
        
        // Validate input
        if (!$productId) {
            Session::set('error', 'Invalid product.');
            header('Location: /cart');
            exit;
        }
        
        // Remove from cart
        $success = Cart::removeItem($userId, $productId);
        
        if ($success) {
            Session::set('success', 'Product removed from your cart.');
        } else {
            Session::set('error', 'Failed to remove product from cart.');
        }
        
        header('Location: /cart');
        exit;
    }
}
