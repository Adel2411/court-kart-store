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
        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $minPrice = $_GET['min_price'] ?? null;
        $maxPrice = $_GET['max_price'] ?? null;
        $sort = $_GET['sort'] ?? 'name_asc';

        // Fetch filtered products from the database
        $products = Product::getFiltered([
            'search' => $search,
            'category' => $category,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'sort' => $sort,
        ]);

        echo View::renderWithLayout('shop/index', 'main', [
            'title' => 'Shop - Court Kart',
            'products' => $products,
            'page_css' => 'shop',
            'page_js' => 'shop',
        ]);
    }

    /**
     * Display a product details page
     *
     * @param  int  $id  The product ID
     */
    public function show($id)
    {
        // Fetch product from database
        $product = Product::getById($id);

        if (! $product) {
            // Product not found, redirect to 404
            header('HTTP/1.0 404 Not Found');
            require_once BASE_PATH.'/views/errors/404.php';

            return;
        }

        echo View::renderWithLayout('shop/product', 'main', [
            'title' => $product['name'].' - Court Kart',
            'id' => $product['id'],
            'product_name' => $product['name'],
            'description' => $product['description'],
            'price' => $product['price'],
            'stock' => $product['stock'],
            'image_url' => $product['image_url'],
            'category' => $product['category'],
            'page_css' => 'product',
            'page_js' => 'product',
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

        // Fetch products from the selected category
        $products = Product::getByCategory($category_name, 4);

        echo View::renderWithLayout('shop/category', 'main', [
            'title' => "$category_name - Court Kart",
            'id' => $id,
            'category_name' => $category_name,
            'products' => $products,
        ]);
    }
}
