<?php

namespace App\Models;

use App\Core\Database;

class Review
{
    /**
     * Get reviews for a specific product with pagination
     *
     * @param int $productId
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getByProductId($productId, $page = 1, $perPage = 5)
    {
        $db = Database::getInstance();
        $offset = ($page - 1) * $perPage;
        
        // Count total reviews
        $total = $db->fetchOne('
            SELECT COUNT(*) as total FROM product_reviews 
            WHERE product_id = ? AND status = "approved"
        ', [$productId]) ?? 0;
        
        // Get paginated reviews
        $reviews = $db->fetchRows('
            SELECT r.*, u.name, u.profile_image 
            FROM product_reviews r
            LEFT JOIN users u ON r.user_id = u.id
            WHERE r.product_id = ? AND r.status = "approved"
            ORDER BY r.created_at DESC
            LIMIT ? OFFSET ?
        ', [$productId, $perPage, $offset]);
        
        return [
            'reviews' => $reviews,
            'total' => $total
        ];
    }
    
    /**
     * Get the average rating for a product
     *
     * @param int $productId
     * @return float
     */
    public static function getAverageRating($productId)
    {
        $db = Database::getInstance();
        $avgRating = $db->fetchOne('
            SELECT AVG(rating) as average_rating 
            FROM product_reviews 
            WHERE product_id = ? AND status = "approved"
        ', [$productId]);
        
        return $avgRating ? round($avgRating * 2) / 2 : 0; // Round to nearest 0.5
    }
    
    /**
     * Get rating distribution for a product
     *
     * @param int $productId
     * @return array
     */
    public static function getRatingDistribution($productId)
    {
        $db = Database::getInstance();
        $results = $db->fetchRows('
            SELECT FLOOR(rating) as rating, COUNT(*) as count
            FROM product_reviews
            WHERE product_id = ? AND status = "approved"
            GROUP BY FLOOR(rating)
        ', [$productId]);
        
        // Initialize distribution with zeros
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];
        
        // Fill in the actual counts
        foreach ($results as $row) {
            $rating = (int) $row['rating'];
            if ($rating >= 1 && $rating <= 5) {
                $distribution[$rating] = (int) $row['count'];
            }
        }
        
        return $distribution;
    }
    
    /**
     * Get a review by user and product
     *
     * @param int $userId
     * @param int $productId
     * @return array|false
     */
    public static function getByUserAndProduct($userId, $productId)
    {
        $db = Database::getInstance();
        return $db->fetchRow('
            SELECT * FROM product_reviews 
            WHERE user_id = ? AND product_id = ?
        ', [$userId, $productId]);
    }
    
    /**
     * Create a new review
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $db = Database::getInstance();
        return $db->execute('
            INSERT INTO product_reviews (user_id, product_id, rating, review_text)
            VALUES (?, ?, ?, ?)
        ', [
            $data['user_id'],
            $data['product_id'],
            $data['rating'],
            $data['review_text']
        ]) > 0;
    }
    
    /**
     * Update an existing review
     *
     * @param array $data
     * @return bool
     */
    public static function update($data)
    {
        $db = Database::getInstance();
        return $db->execute('
            UPDATE product_reviews
            SET rating = ?, review_text = ?, updated_at = NOW()
            WHERE id = ?
        ', [
            $data['rating'],
            $data['review_text'],
            $data['id']
        ]) > 0;
    }
    
    /**
     * Get count of reviews for a specific product
     *
     * @param int $productId
     * @return int
     */
    public static function getCountByProductId($productId)
    {
        $db = Database::getInstance();
        $totalReviews = $db->fetchOne('
            SELECT COUNT(*) as total FROM product_reviews 
            WHERE product_id = ? AND status = "approved"
        ', [$productId]);
        
        return $totalReviews ? (int)$totalReviews : 0;
    }
}
