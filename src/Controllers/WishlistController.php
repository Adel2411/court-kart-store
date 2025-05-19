<?php

namespace App\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use \App\Core\View;

class WishlistController
{
    private $wishlistModel;
    private $productModel;
    
    public function __construct()
    {
        $this->wishlistModel = new Wishlist();
        $this->productModel = new Product();
    }
    
    /**
     * Display user's wishlist
     */
    public function index()
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            header("Location: /login?redirect=/wishlist");
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $wishlistItems = $this->wishlistModel->getUserWishlist($userId);
        
        // Calculate original prices for items with discounts
        foreach ($wishlistItems as &$item) {
            if (isset($item['discount']) && $item['discount'] > 0) {
                $item['original_price'] = $item['price'];
                $item['price'] = round($item['price'] * (1 - $item['discount']), 2);
            }
        }
        
        // Replace the custom view method with View class
        echo View::renderWithLayout('wishlist/index', 'main', [
            'items' => $wishlistItems,
            'count' => count($wishlistItems),
            'title' => 'My Wishlist'
        ]);
    }
    
    /**
     * Add item to wishlist via AJAX
     */
    public function add()
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            $this->jsonResponse(['success' => false, 'message' => 'Please log in to add items to your wishlist', 'redirect' => '/login'], 401);
            return;
        }
        
        // Check if request is AJAX
        if (!$this->isAjaxRequest()) {
            header("Location: /");
            exit;
        }
        
        // Validate product ID
        if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid product ID'], 400);
            return;
        }
        
        $productId = (int)$_POST['product_id'];
        $userId = $_SESSION['user_id'];
        
        // Check if product exists
        if (!$this->productModel->getById($productId)) {
            $this->jsonResponse(['success' => false, 'message' => 'Product not found'], 404);
            return;
        }
        
        // Check if already in wishlist
        $alreadyInWishlist = $this->wishlistModel->isInWishlist($userId, $productId);
        
        if ($alreadyInWishlist) {
            // Remove from wishlist if already there (toggle behavior)
            $success = $this->wishlistModel->remove($userId, $productId);
            $message = 'Product removed from wishlist';
            $isAdded = false;
        } else {
            // Add to wishlist
            $success = $this->wishlistModel->add($userId, $productId);
            $message = 'Product added to wishlist';
            $isAdded = true;
        }
        
        // Get updated wishlist count
        $count = $this->wishlistModel->countWishlistItems($userId);
        
        // Return JSON response
        $this->jsonResponse([
            'success' => $success,
            'message' => $message,
            'is_added' => $isAdded,
            'count' => $count
        ]);
    }
    
    /**
     * Remove item from wishlist
     */
    public function remove()
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Please log in'], 401);
                return;
            }
            header("Location: /login?redirect=/wishlist");
            exit;
        }
        
        // Validate product ID
        if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid product ID'], 400);
                return;
            }
            header("Location: /wishlist");
            exit;
        }
        
        $productId = (int)$_POST['product_id'];
        $userId = $_SESSION['user_id'];
        
        // Remove from wishlist
        $success = $this->wishlistModel->remove($userId, $productId);
        
        // If AJAX request, return JSON response
        if ($this->isAjaxRequest()) {
            $count = $this->wishlistModel->countWishlistItems($userId);
            $this->jsonResponse([
                'success' => $success,
                'message' => $success ? 'Product removed from wishlist' : 'Failed to remove product',
                'count' => $count
            ]);
            return;
        }
        
        // If regular form submission, redirect back to wishlist
        if ($success) {
            $_SESSION['flash_message'] = 'Item removed from wishlist';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Failed to remove item from wishlist';
            $_SESSION['flash_type'] = 'error';
        }
        
        header("Location: /wishlist");
        exit;
    }
    
    /**
     * Check if product is in wishlist via AJAX
     */
    public function check()
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            $this->jsonResponse(['in_wishlist' => false], 200);
            return;
        }
        
        // Check if request is AJAX
        if (!$this->isAjaxRequest()) {
            header("Location: /");
            exit;
        }
        
        // Validate product ID
        if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid product ID'], 400);
            return;
        }
        
        $productId = (int)$_GET['product_id'];
        $userId = $_SESSION['user_id'];
        
        // Check if in wishlist
        $inWishlist = $this->wishlistModel->isInWishlist($userId, $productId);
        
        // Return JSON response
        $this->jsonResponse(['in_wishlist' => $inWishlist]);
    }
    
    /**
     * Helper method to check if request is AJAX
     */
    private function isAjaxRequest()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }
    
    /**
     * Helper method to send JSON response
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Helper method to check if user is logged in
     */
    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
