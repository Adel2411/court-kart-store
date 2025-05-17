<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Product;
use App\Models\User;

class HomeController
{
    /**
     * Display the home page
     */
    public function index()
    {
        $newProducts = Product::getNewest(4);
        $topProducts = Product::getPopular(4);

        foreach ($newProducts as &$product) {
            $product['is_new'] = true;
            
            if (isset($product['discount']) && $product['discount'] > 0) {
                // Store the original price (before discount)
                $product['original_price'] = $product['price'];
                // Calculate the discounted price correctly
                $product['price'] = round($product['price'] * (1 - $product['discount']), 2);
            } else {
                $product['original_price'] = $product['price'];
                $product['discount'] = 0;
            }
        }

        foreach ($topProducts as &$product) {
            if (isset($product['discount']) && $product['discount'] > 0) {
                // Store the original price (before discount)
                $product['original_price'] = $product['price'];
                // Calculate the discounted price correctly
                $product['price'] = round($product['price'] * (1 - $product['discount']), 2);
            } else {
                // For products without discount, don't randomly assign discounts
                $product['original_price'] = $product['price'];
                $product['discount'] = 0;
            }
        }

        $totalProducts = Product::getCount();
        $totalUsers = User::getCount();

        echo View::renderWithLayout('home', 'main', [
            'title' => 'Court Kart - Premium Basketball Products',
            'newProducts' => $newProducts,
            'topProducts' => $topProducts,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'page_css' => 'home',
            'page_js' => 'home',
        ]);
    }
}
