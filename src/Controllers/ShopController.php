<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Product;

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
            'page_css' => 'product'
        ]);
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
