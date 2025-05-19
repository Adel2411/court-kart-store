<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Product;
use App\Models\Review;

class ShopController
{
    /**
     * Display the shop index page with search and filtering
     */
    public function index()
    {
        $filters = [
            'search' => $_GET['search'] ?? null,
            'category' => $_GET['category'] ?? null,
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'sort' => $_GET['sort'] ?? 'newest',
        ];

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $result = Product::getFiltered($filters, $page, 9);
        $products = $result['products'];
        $pagination = $result['pagination'];

        $pagination['query_string'] = $this->buildQueryString($filters);

        echo View::renderWithLayout('shop/index', 'main', [
            'title' => 'Shop - Court Kart',
            'products' => $products,
            'pagination' => $pagination,
            'page_css' => ['shop'],
            'page_js' => ['shop'],
        ]);
    }

    /**
     * Build query string for pagination links that preserves filters
     */
    private function buildQueryString($filters)
    {
        $query = [];

        if (! empty($filters['search'])) {
            $query['search'] = $filters['search'];
        }

        if (! empty($filters['category'])) {
            if (is_array($filters['category'])) {
                foreach ($filters['category'] as $category) {
                    $query['category'][] = $category;
                }
            } else {
                $query['category'] = $filters['category'];
            }
        }

        if (! empty($filters['min_price'])) {
            $query['min_price'] = $filters['min_price'];
        }

        if (! empty($filters['max_price'])) {
            $query['max_price'] = $filters['max_price'];
        }

        if (! empty($filters['sort'])) {
            $query['sort'] = $filters['sort'];
        }

        return http_build_query($query);
    }

    /**
     * Display a product details page
     *
     * @param  int  $id  The product ID
     */
    public function show($id)
    {
        $product = Product::getById($id);
        
        if (! $product) {
            header('HTTP/1.1 404 Not Found');
            // Handle product not found
            return;
        }
        
        // Store the original price 
        $originalPrice = $product['price'];
        
        // Calculate the discounted price if discount exists
        $discountedPrice = $originalPrice;
        if (isset($product['discount']) && $product['discount'] > 0) {
            $discountedPrice = round($originalPrice * (1 - $product['discount']), 2);
        }
        
        // Get reviews for this product
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perPage = 5;
        
        $reviewsData = Review::getByProductId($id, $page, $perPage);
        $reviews = $reviewsData['reviews'] ?? [];
        $reviewsCount = $reviewsData['total'] ?? 0;
        
        // Calculate average rating
        $averageRating = Review::getAverageRating($id);
        
        // Get rating distribution (how many 5-star, 4-star, etc.)
        $ratingDistribution = Review::getRatingDistribution($id);
        
        // Pagination for reviews
        $totalPages = ceil($reviewsCount / $perPage);
        $pagination = [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $reviewsCount
        ];
        
        echo View::renderWithLayout('shop/product', 'main', [
            'title' => $product['name'] . ' - Court Kart',
            'id' => $product['id'],
            'product_name' => $product['name'],
            'description' => $product['description'],
            'price' => $discountedPrice,
            'original_price' => $originalPrice,
            'stock' => $product['stock'],
            'category' => $product['category'],
            'image_url' => $product['image_url'],
            'discount' => $product['discount'],
            'average_rating' => $averageRating,
            'reviews_count' => $reviewsCount,
            'reviews' => $reviews,
            'rating_distribution' => $ratingDistribution,
            'pagination' => $pagination,
            'page_css' => 'product'
        ]);
    }

    /**
     * Submit a new product review
     *
     * @param  int  $id  The product ID
     */
    public function submitReview($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login page
            header('Location: /login?redirect=/shop/product/' . $id);
            exit;
        }
        
        // Validate form data
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = isset($_POST['rating']) ? (float) $_POST['rating'] : 0;
            $reviewText = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
            
            // Validate rating
            if ($rating < 0.5 || $rating > 5) {
                $_SESSION['error'] = 'Please provide a valid rating (0.5 to 5 stars).';
                header('Location: /shop/product/' . $id);
                exit;
            }
            
            // Validate review text
            if (empty($reviewText)) {
                $_SESSION['error'] = 'Please provide a review text.';
                header('Location: /shop/product/' . $id);
                exit;
            }
            
            // Check if user already reviewed this product
            $existingReview = Review::getByUserAndProduct($_SESSION['user_id'], $id);
            
            if ($existingReview) {
                // Update existing review
                $result = Review::update([
                    'id' => $existingReview['id'],
                    'rating' => $rating,
                    'review_text' => $reviewText
                ]);
                
                $message = 'Your review has been updated.';
            } else {
                // Create new review
                $result = Review::create([
                    'user_id' => $_SESSION['user_id'],
                    'product_id' => $id,
                    'rating' => $rating,
                    'review_text' => $reviewText
                ]);
                
                $message = 'Thank you for your review!';
            }
            
            if ($result) {
                $_SESSION['success'] = $message;
            } else {
                $_SESSION['error'] = 'There was an error saving your review. Please try again.';
            }
            
            // Redirect back to product page
            header('Location: /shop/product/' . $id);
            exit;
        }
        
        // If not POST, redirect to product page
        header('Location: /shop/product/' . $id);
    }

    /**
     * Display products in a category
     *
     * @param  int  $id  The category ID
     */
    public function category($id)
    {
        $categories = [
            1 => 'Footwear',
            2 => 'Apparel',
            3 => 'Equipment',
            4 => 'Accessories',
            5 => 'Merchandise',
        ];

        $category_name = $categories[$id] ?? 'Unknown Category';

        $products = Product::getByCategory($category_name, 4);

        echo View::renderWithLayout('shop/category', 'main', [
            'title' => "$category_name - Court Kart",
            'id' => $id,
            'category_name' => $category_name,
            'products' => $products,
        ]);
    }
}
