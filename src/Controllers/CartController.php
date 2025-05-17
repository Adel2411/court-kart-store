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

        $cartItems = Cart::getItems($userId);
        $totalPrice = 0;

        foreach ($cartItems as &$item) {
            $product = Product::getById($item['product_id']);
            $item['name'] = $product['name'];
            
            // Apply discount if available
            if (isset($product['discount']) && $product['discount'] > 0) {
                $item['original_price'] = $product['price'];
                $item['price'] = round($product['price'] * (1 - $product['discount']), 2);
                $item['discount'] = $product['discount'];
            } else {
                $item['price'] = $product['price'];
                $item['discount'] = 0;
            }
            
            $item['image_url'] = $product['image_url'];
            $item['subtotal'] = $item['price'] * $item['quantity'];
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
        $quantity = (int) ($_POST['quantity'] ?? 1);
        $returnUrl = $_POST['return_url'] ?? '/shop';

        $isAjaxRequest = ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if (! $productId || $quantity < 1) {
            if ($isAjaxRequest) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid product or quantity.']);

                return;
            }
            Session::set('error', 'Invalid product or quantity.');
            header('Location: '.$returnUrl);
            exit;
        }

        $product = Product::getById($productId);
        if (! $product) {
            if ($isAjaxRequest) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Product not found.']);

                return;
            }
            Session::set('error', 'Product not found.');
            header('Location: '.$returnUrl);
            exit;
        }

        if ($quantity > $product['stock']) {
            if ($isAjaxRequest) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Not enough stock available.']);

                return;
            }
            Session::set('error', 'Not enough stock available.');
            header('Location: '.$returnUrl);
            exit;
        }

        $success = Cart::addItem($userId, $productId, $quantity);

        if ($isAjaxRequest) {
            header('Content-Type: application/json');
            $cartCount = Cart::getItemCount($userId);
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Product added to your cart.' : 'Failed to add product to cart.',
                'count' => $cartCount,
            ]);

            return;
        } else {
            if ($success) {
                Session::set('success', 'Product added to your cart.');
            } else {
                Session::set('error', 'Failed to add product to cart.');
            }

            header('Location: '.$returnUrl);
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
        $quantity = (int) ($_POST['quantity'] ?? 0);

        if (! $productId) {
            Session::set('error', 'Invalid product.');
            header('Location: /cart');
            exit;
        }

        if ($quantity <= 0) {
            return $this->remove();
        }

        $product = Product::getById($productId);
        if (! $product || $quantity > $product['stock']) {
            Session::set('error', 'Not enough stock available.');
            header('Location: /cart');
            exit;
        }

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

        if (! $productId) {
            Session::set('error', 'Invalid product.');
            header('Location: /cart');
            exit;
        }

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
