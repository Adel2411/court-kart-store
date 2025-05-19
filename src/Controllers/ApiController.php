<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Wishlist;

class ApiController
{
    /**
     * Get product details for API
     */
    public function getProduct($id)
    {
        $product = Product::getById($id);
        
        if (!$product) {
            $this->jsonResponse(['error' => 'Product not found'], 404);
            return;
        }
        
        // Calculate discounted price if applicable
        if (isset($product['discount']) && $product['discount'] > 0) {
            $product['original_price'] = $product['price'];
            $product['price'] = round($product['price'] * (1 - $product['discount']), 2);
            $product['discount_percent'] = round($product['discount'] * 100);
        }
        
        // Check if product is in user's wishlist if logged in
        if (isset($_SESSION['user_id'])) {
            $wishlistModel = new Wishlist();
            $product['in_wishlist'] = $wishlistModel->isInWishlist($_SESSION['user_id'], $id);
        }
        
        $this->jsonResponse($product);
    }
    
    /**
     * Get order details for API
     */
    public function getOrder($id)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }
        
        // Get order details from Order model
        $order = Order::getOrderDetails($id);
        
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            $this->jsonResponse(['error' => 'Order not found'], 404);
            return;
        }
        
        $this->jsonResponse($order);
    }
    
    /**
     * Check if an item is in user's wishlist
     */
    public function checkWishlistItem($productId)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['in_wishlist' => false]);
            return;
        }
        
        $wishlistModel = new Wishlist();
        $isInWishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $productId);
        
        $this->jsonResponse(['in_wishlist' => $isInWishlist]);
    }
    
    /**
     * Send JSON response
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
