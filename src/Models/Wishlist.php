<?php

namespace App\Models;

use PDO;
use App\Core\Database;

class Wishlist
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Add a product to a user's wishlist
     * 
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public function add($userId, $productId)
    {
        try {
            $sql = "INSERT INTO wishlists (user_id, product_id) 
                    VALUES (:user_id, :product_id)
                    ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP";
            
            $params = [
                ':user_id' => $userId,
                ':product_id' => $productId
            ];
            
            $result = $this->db->execute($sql, $params);
            return $result >= 0;
        } catch (\PDOException $e) {
            // Handle duplicate entry error gracefully - item already in wishlist
            if ($e->getCode() == '23000') {
                return true;
            }
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Remove a product from a user's wishlist
     * 
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public function remove($userId, $productId)
    {
        try {
            $sql = "DELETE FROM wishlists 
                    WHERE user_id = :user_id AND product_id = :product_id";
            
            $params = [
                ':user_id' => $userId,
                ':product_id' => $productId
            ];
            
            $result = $this->db->execute($sql, $params);
            return $result > 0;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if a product is in a user's wishlist
     * 
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public function isInWishlist($userId, $productId)
    {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM wishlists 
                    WHERE user_id = :user_id AND product_id = :product_id";
            
            $params = [
                ':user_id' => $userId,
                ':product_id' => $productId
            ];
            
            $count = $this->db->fetchOne($sql, $params);
            return (int)$count > 0;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all items in a user's wishlist
     * 
     * @param int $userId
     * @return array
     */
    public function getUserWishlist($userId)
    {
        try {
            $sql = "SELECT p.*, w.created_at as added_at
                    FROM wishlists w
                    JOIN products p ON w.product_id = p.id
                    WHERE w.user_id = :user_id
                    ORDER BY w.created_at DESC";
            
            $params = [':user_id' => $userId];
            
            return $this->db->fetchRows($sql, $params);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    
    /**
     * Count items in a user's wishlist
     * 
     * @param int $userId
     * @return int
     */
    public function countWishlistItems($userId)
    {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM wishlists 
                    WHERE user_id = :user_id";
            
            $params = [':user_id' => $userId];
            
            $count = $this->db->fetchOne($sql, $params);
            return (int)$count;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get all product IDs in a user's wishlist
     * 
     * @param int $userId
     * @return array
     */
    public function getUserWishlistProductIds($userId)
    {
        try {
            $sql = "SELECT product_id 
                    FROM wishlists 
                    WHERE user_id = :user_id";
            
            $params = [':user_id' => $userId];
            
            $result = $this->db->fetchRows($sql, $params);
            
            // Extract just the product_id values
            return array_map(function($item) {
                return $item['product_id'];
            }, $result);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
