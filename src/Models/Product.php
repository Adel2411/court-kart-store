<?php

namespace App\Models;

use App\Core\Database;

class Product
{
    private $id;

    private $name;

    private $description;

    private $price;

    private $stock;

    private $image_url;

    private $category;

    private $created_at;

    /**
     * Get all products from the database
     *
     * @param  int  $limit  Optional limit of products to fetch
     * @return array Array of products
     */
    public static function getAll($limit = 10): array
    {
        $db = Database::getInstance();

        // Fix: Use the limit as an integer in the SQL query directly
        $limit = (int) $limit; // Cast to integer for security
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $limit";

        return $db->fetchRows($sql, []);
    }

    /**
     * Get a product by its ID
     *
     * @param  int  $id  Product ID
     * @return array|null Product data or null if not found
     */
    public static function getById($id): ?array
    {
        $db = Database::getInstance();

        $sql = 'SELECT * FROM products WHERE id = ?';

        return $db->fetchRow($sql, [$id]);
    }

    /**
     * Get products by category
     *
     * @param  string  $category  Category name
     * @param  int  $limit  Optional limit of products to fetch
     * @return array Array of products
     */
    public static function getByCategory($category, $limit = 10): array
    {
        $db = Database::getInstance();

        // Fix: Use the limit as an integer in the SQL query directly
        $limit = (int) $limit; // Cast to integer for security
        $sql = "SELECT * FROM products WHERE category = ? ORDER BY id DESC LIMIT $limit";

        return $db->fetchRows($sql, [$category]);
    }

    /**
     * Get count of all products
     *
     * @return int Count of products
     */
    public static function getCount(): int
    {
        $db = Database::getInstance();

        $sql = 'SELECT COUNT(*) as count FROM products';
        $result = $db->fetchRow($sql);

        return (int) ($result['count'] ?? 0);
    }
}
