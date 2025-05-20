<?php

namespace App\Controllers;

use App\Core\View;
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
        // Get product details from database
        $product = Product::getById($id);
        
        if (!$product) {
            $this->jsonResponse(['error' => 'Product not found'], 404);
            return;
        }
        
        // Calculate discounted price if discount exists
        $originalPrice = $product['price'];
        $discountedPrice = $originalPrice;
        
        if (isset($product['discount']) && $product['discount'] > 0) {
            $discountedPrice = round($originalPrice * (1 - $product['discount']), 2);
        }
        
        // Get review data - same as in ShopController
        $averageRating = Review::getAverageRating($id);
        $reviewsCount = Review::getCountByProductId($id);
        
        // Format response to match product.php expectations
        $response = [
            'id' => $product['id'],
            'name' => $product['name'],
            'description' => $product['description'],
            'price' => $discountedPrice,
            'original_price' => $originalPrice,
            'stock' => $product['stock'],
            'category' => $product['category'],
            'image_url' => $product['image_url'],
            'discount' => $product['discount'],
            'average_rating' => $averageRating,
            'reviews_count' => $reviewsCount,
            'is_new' => (time() - strtotime($product['created_at']) < 604800) // 7 days
        ];
        
        $this->jsonResponse($response);
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
     * 
     * @param mixed $data
     * @param int $statusCode
     * @return void
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
