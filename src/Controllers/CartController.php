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
        $returnUrl = $_POST['return_url'] ?? '/shop';
        
        $isAjaxRequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        // Validate input
        if (!$productId || $quantity < 1) {
            if ($isAjaxRequest) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid product or quantity.']);
                return;
            }
            Session::set('error', 'Invalid product or quantity.');
            header('Location: ' . $returnUrl);
            exit;
        }
        
        // Check product exists and has enough stock
        $product = Product::getById($productId);
        if (!$product) {
            if ($isAjaxRequest) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Product not found.']);
                return;
            }
            Session::set('error', 'Product not found.');
            header('Location: ' . $returnUrl);
            exit;
        }
        
        if ($quantity > $product['stock']) {
            if ($isAjaxRequest) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Not enough stock available.']);
                return;
            }
            Session::set('error', 'Not enough stock available.');
            header('Location: ' . $returnUrl);
            exit;
        }
        
        // Add to cart
        $success = Cart::addItem($userId, $productId, $quantity);
        
        if ($isAjaxRequest) {
            header('Content-Type: application/json');
            $cartCount = Cart::getItemCount($userId);
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Product added to your cart.' : 'Failed to add product to cart.',
                'count' => $cartCount
            ]);
            return;
        } else {
            if ($success) {
                Session::set('success', 'Product added to your cart.');
            } else {
                Session::set('error', 'Failed to add product to cart.');
            }
            
            // Redirect back to previous page or cart
            header('Location: ' . $returnUrl);
            exit;
        }
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

    /**
     * Get the current cart item count (for AJAX requests)
     */
    public function count()
    {
        $userId = Session::get('user_id');
        $count = Cart::getItemCount($userId);
        
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }
}
