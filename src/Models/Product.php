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
     * Get all products with optional limit
     *
     * @param  int  $limit  Maximum number of products to return
     * @return array
     */
    public static function getAll($limit = null)
    {
        $db = Database::getInstance();
        $sql = 'SELECT * FROM products';

        if ($limit) {
            $sql .= ' LIMIT ?';

            return $db->fetchRows($sql, [$limit]);
        }

        return $db->fetchRows($sql);
    }

    /**
     * Get filtered products with pagination
     *
     * @param  array  $filters  Array of filter parameters
     * @param  int  $page  Current page number
     * @param  int  $perPage  Number of items per page
     * @return array Contains 'products' array and 'pagination' metadata
     */
    public static function getFiltered($filters, $page = 1, $perPage = 9)
    {
        $db = Database::getInstance();
        $params = [];

        $sql = 'SELECT * FROM products WHERE 1=1';
        $countSql = 'SELECT COUNT(*) as count FROM products WHERE 1=1';

        // Search filter
        if (! empty($filters['search'])) {
            $searchCondition = ' AND (name LIKE ? OR description LIKE ?)';
            $sql .= $searchCondition;
            $countSql .= $searchCondition;
            $searchTerm = '%'.$filters['search'].'%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Category filter - Updated to handle multiple categories
        if (! empty($filters['category'])) {
            if (is_array($filters['category']) && count($filters['category']) > 0) {
                $placeholders = implode(',', array_fill(0, count($filters['category']), '?'));
                $categoryCondition = " AND category IN ($placeholders)";
                $sql .= $categoryCondition;
                $countSql .= $categoryCondition;
                $params = array_merge($params, $filters['category']);
                $countParams = array_merge($params, $filters['category']);
            } elseif (! is_array($filters['category'])) {
                $categoryCondition = ' AND category = ?';
                $sql .= $categoryCondition;
                $countSql .= $categoryCondition;
                $params[] = $filters['category'];
                $countParams[] = $filters['category'];
            }
        }

        // Price range filter
        if (! empty($filters['min_price'])) {
            $minPriceCondition = ' AND price >= ?';
            $sql .= $minPriceCondition;
            $countSql .= $minPriceCondition;
            $params[] = $filters['min_price'];
            $countParams[] = $filters['min_price'];
        }

        if (! empty($filters['max_price'])) {
            $maxPriceCondition = ' AND price <= ?';
            $sql .= $maxPriceCondition;
            $countSql .= $maxPriceCondition;
            $params[] = $filters['max_price'];
            $countParams[] = $filters['max_price'];
        }

        // Get total count for pagination
        $countParams = $params; // Copy params for count query
        $totalCount = (int) $db->fetchRow($countSql, $countParams)['count'];

        // Apply sorting
        if (! empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'newest':
                    $sql .= ' ORDER BY created_at DESC';
                    break;
                case 'price_asc':
                    $sql .= ' ORDER BY price ASC';
                    break;
                case 'price_desc':
                    $sql .= ' ORDER BY price DESC';
                    break;
                case 'popularity':
                    $sql .= ' ORDER BY stock ASC'; // Using stock as a proxy for popularity
                    break;
                default:
                    $sql .= ' ORDER BY created_at DESC';
            }
        } else {
            $sql .= ' ORDER BY created_at DESC';
        }

        // Calculate pagination values
        $totalPages = ceil($totalCount / $perPage);
        $page = max(1, min($page, max(1, $totalPages))); // Ensure page is within valid range
        $offset = ($page - 1) * $perPage;

        // Add pagination to the query
        $sql .= ' LIMIT ? OFFSET ?';
        $params[] = $perPage;
        $params[] = $offset;

        // Get products for current page
        $products = $db->fetchRows($sql, $params);

        // Return both products and pagination metadata
        return [
            'products' => $products,
            'pagination' => [
                'total_items' => $totalCount,
                'total_pages' => $totalPages,
                'current_page' => $page,
                'per_page' => $perPage,
            ],
        ];
    }

    /**
     * Get product by ID
     *
     * @param  int  $id  Product ID
     * @return array|bool Product data or false if not found
     */
    public static function getById($id)
    {
        $db = Database::getInstance();

        return $db->fetchRow('SELECT * FROM products WHERE id = ?', [$id]);
    }

    /**
     * Get products by category
     *
     * @param  string  $category  Category name
     * @param  int  $limit  Maximum number of products to return
     * @return array
     */
    public static function getByCategory($category, $limit = null)
    {
        $db = Database::getInstance();
        $sql = 'SELECT * FROM products WHERE category = ?';

        if ($limit) {
            $sql .= ' LIMIT ?';

            return $db->fetchRows($sql, [$category, $limit]);
        }

        return $db->fetchRows($sql, [$category]);
    }

    /**
     * Get total count of products
     *
     * @return int
     */
    public static function getCount()
    {
        $db = Database::getInstance();
        $result = $db->fetchRow('SELECT COUNT(*) as count FROM products');

        return $result ? (int) $result['count'] : 0;
    }

    /**
     * Add a new product
     *
     * @param  array  $data  Product data
     * @return int|bool New product ID or false on failure
     */
    public static function add($data)
    {
        $db = Database::getInstance();

        return $db->execute('INSERT INTO products (name, description, price, stock, image_url, category) VALUES (?, ?, ?, ?, ?, ?)', [
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['image_url'],
            $data['category'],
        ]);
    }

    /**
     * Update a product
     *
     * @param  int  $id  Product ID
     * @param  array  $data  Product data
     * @return bool Success flag
     */
    public static function update($id, $data)
    {
        $db = Database::getInstance();

        return $db->execute('UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image_url = ?, category = ? WHERE id = ?', [
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['image_url'],
            $data['category'],
            $id,
        ]);
    }

    /**
     * Delete a product
     *
     * @param  int  $id  Product ID
     * @return bool Success flag
     */
    public static function delete($id)
    {
        $db = Database::getInstance();

        return $db->execute('DELETE FROM products WHERE id = ?', [$id]);
    }

    /**
     * Get newest products based on creation date
     *
     * @param  int  $limit  Maximum number of products to return
     * @return array
     */
    public static function getNewest($limit = 4)
    {
        $db = Database::getInstance();
        $sql = 'SELECT * FROM products ORDER BY created_at DESC';

        if ($limit) {
            $sql .= ' LIMIT ?';

            return $db->fetchRows($sql, [$limit]);
        }

        return $db->fetchRows($sql);
    }

    /**
     * Get popular products (currently based on lowest stock as an indicator of sales)
     *
     * @param  int  $limit  Maximum number of products to return
     * @return array
     */
    public static function getPopular($limit = 4)
    {
        $db = Database::getInstance();

        // In a real-world scenario, this would be based on sales data or view counts
        // For now, we're assuming popular products have lower stock due to more sales
        // Alternatively, this could be implemented with a product_views or order_items table join
        $sql = 'SELECT * FROM products WHERE stock > 0 ORDER BY stock ASC';

        if ($limit) {
            $sql .= ' LIMIT ?';

            return $db->fetchRows($sql, [$limit]);
        }

        return $db->fetchRows($sql);
    }
}
