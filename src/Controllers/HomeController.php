<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Product;

class HomeController
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Get featured products for the homepage
        $newProducts = Product::getNewest(4);
        $topProducts = Product::getPopular(4);
        
        // Add some metadata for visual display
        foreach ($newProducts as &$product) {
            $product['is_new'] = true;
            if (!isset($product['original_price'])) {
                $product['original_price'] = 0;
            }
        }
        
        foreach ($topProducts as &$product) {
            // Add discount flag to some products for visual display
            if (rand(0, 1) && !isset($product['original_price'])) {
                $product['original_price'] = round($product['price'] * (1 + (rand(10, 30) / 100)), 2);
                $product['discount'] = round(($product['original_price'] - $product['price']) / $product['original_price'] * 100);
            } else {
                $product['discount'] = 0;
                if (!isset($product['original_price'])) {
                    $product['original_price'] = 0;
                }
            }
        }

        echo View::renderWithLayout('home', 'main', [
            'title' => 'Court Kart - Premium Basketball Products',
            'newProducts' => $newProducts,
            'topProducts' => $topProducts,
            'page_css' => 'home',
            'page_js' => 'home',
        ]);
    }
}
