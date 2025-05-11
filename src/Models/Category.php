<?php

namespace App\Models;

use App\Core\Database;
use Exception;

class Category
{
    /**
     * Get all distinct categories from the products table
     *
     * @return array Array of categories
     */
    public static function getAll(): array
    {
        $db = Database::getInstance();

        try {
            // Get distinct categories from the products table
            $categories = $db->fetchRows('SELECT DISTINCT category FROM products');

            // Format the result to be more usable in views
            $result = [];
            foreach ($categories as $category) {
                $result[] = [
                    'id' => $category['category'],
                    'name' => $category['category'],
                ];
            }

            return $result;
        } catch (Exception $e) {
            error_log('Error fetching categories: '.$e->getMessage());

            return [];
        }
    }
}
