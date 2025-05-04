<?php

namespace App\Controllers;

use App\Core\View;

class CartController
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        echo View::renderWithLayout('cart/index', 'main', [
            'title' => 'Your Cart - Court Kart',
            'cart_items' => [1, 2, 3], // Sample data
        ]);
    }

    /**
     * Add an item to the cart
     */
    public function add()
    {
        // In a real app, we would process the form submission
        $product_id = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        // Redirect to cart page
        header('Location: /cart');
        exit;
    }

    /**
     * Update an item in the cart
     */
    public function update()
    {
        // In a real app, we would process the form submission
        $product_id = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        // Redirect to cart page
        header('Location: /cart');
        exit;
    }

    /**
     * Remove an item from the cart
     */
    public function remove()
    {
        // In a real app, we would process the form submission
        $product_id = $_POST['product_id'] ?? null;

        // Redirect to cart page
        header('Location: /cart');
        exit;
    }
}
