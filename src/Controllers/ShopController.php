<?php

namespace App\Controllers;

use App\Core\View;

class ShopController
{
    /**
     * Display the shop index page
     */
    public function index()
    {
        echo View::renderWithLayout('shop/index', 'main', [
            'title' => 'Shop - Court Kart Store'
        ]);
    }
    
    /**
     * Display a product details page
     * 
     * @param int $id The product ID
     */
    public function show($id)
    {
        echo View::renderWithLayout('shop/product', 'main', [
            'title' => 'Product Details - Court Kart Store',
            'id' => $id,
            'product_name' => "Basketball Product $id"
        ]);
    }
    
    /**
     * Display products in a category
     * 
     * @param int $id The category ID
     */
    public function category($id)
    {
        $categories = [
            1 => 'Footwear',
            2 => 'Apparel',
            3 => 'Equipment',
            4 => 'Accessories',
            5 => 'Merchandise'
        ];
        
        $category_name = $categories[$id] ?? 'Unknown Category';
        
        echo View::renderWithLayout('shop/category', 'main', [
            'title' => "$category_name - Court Kart Store",
            'id' => $id,
            'category_name' => $category_name
        ]);
    }
}
