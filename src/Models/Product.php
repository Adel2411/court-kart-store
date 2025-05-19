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
     */
    public static function getFiltered($filters, $page = 1, $perPage = 9)
    {
        $db = Database::getInstance();
        $params = [];

        $sql = 'SELECT * FROM products WHERE 1=1';
        $countSql = 'SELECT COUNT(*) as count FROM products WHERE 1=1';

        if (! empty($filters['search'])) {
            $searchCondition = ' AND (name LIKE ? OR description LIKE ?)';
            $sql .= $searchCondition;
            $countSql .= $searchCondition;
            $searchTerm = '%'.$filters['search'].'%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

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

        $countParams = $params;
        $totalCount = (int) $db->fetchRow($countSql, $countParams)['count'];

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
                    $sql .= ' ORDER BY stock ASC';
                    break;
                default:
                    $sql .= ' ORDER BY created_at DESC';
            }
        } else {
            $sql .= ' ORDER BY created_at DESC';
        }

        $totalPages = ceil($totalCount / $perPage);
        $page = max(1, min($page, max(1, $totalPages)));
        $offset = ($page - 1) * $perPage;

        $sql .= ' LIMIT ? OFFSET ?';
        $params[] = $perPage;
        $params[] = $offset;

        $products = $db->fetchRows($sql, $params);

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
     */
    public static function getById($id)
    {
        $db = Database::getInstance();

        return $db->fetchRow('SELECT * FROM products WHERE id = ?', [$id]);
    }

    /**
     * Get products by category
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
     */
    public static function getCount()
    {
        $db = Database::getInstance();
        $result = $db->fetchRow('SELECT COUNT(*) as count FROM products');

        return $result ? (int) $result['count'] : 0;
    }

    /**
     * Add a new product
     */
    public static function add($data)
    {
        $db = Database::getInstance();

        return $db->execute('INSERT INTO products (name, description, price, stock, image_url, category, discount) VALUES (?, ?, ?, ?, ?, ?, ?)', [
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['image_url'],
            $data['category'],
            $data['discount'],
        ]);
    }

    /**
     * Update a product
     */
    public static function update($id, $data)
    {
        $db = Database::getInstance();

        return $db->execute('UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image_url = ?, category = ?, discount = ? WHERE id = ?', [
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['image_url'],
            $data['category'],
            $data['discount'],
            $id,
        ]);
    }

    /**
     * Delete a product
     */
    public static function delete($id)
    {
        $db = Database::getInstance();

        return $db->execute('DELETE FROM products WHERE id = ?', [$id]);
    }

    /**
     * Get newest products based on creation date
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
     */
    public static function getPopular($limit = 4)
    {
        $db = Database::getInstance();

        $sql = 'SELECT * FROM products WHERE stock > 0 ORDER BY stock ASC';

        if ($limit) {
            $sql .= ' LIMIT ?';

            return $db->fetchRows($sql, [$limit]);
        }

        return $db->fetchRows($sql);
    }

    /**
     * Get all products with filters
     */
    public static function getAllFiltered(string $category = 'all', string $sort = 'name_asc', string $search = ''): array
    {
        $db = Database::getInstance();
        $params = [];

        $sql = 'SELECT * FROM products WHERE 1=1';

        if ($category !== 'all') {
            $sql .= ' AND category = ?';
            $params[] = $category;
        }

        if (! empty($search)) {
            $sql .= ' AND (name LIKE ? OR description LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        switch ($sort) {
            case 'name_desc':
                $sql .= ' ORDER BY name DESC';
                break;
            case 'price_asc':
                $sql .= ' ORDER BY price ASC';
                break;
            case 'price_desc':
                $sql .= ' ORDER BY price DESC';
                break;
            case 'newest':
                $sql .= ' ORDER BY created_at DESC';
                break;
            case 'name_asc':
            default:
                $sql .= ' ORDER BY name ASC';
                break;
        }

        return $db->fetchRows($sql, $params);
    }

    /**
     * Get products with filters
     * 
     * @param array $filters
     * @param string $sort
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getProductsWithFilters($filters, $sort = 'newest', $page = 1, $perPage = 12)
    {
        try {
            $db = Database::getInstance();
            $sql = "SELECT * FROM products WHERE 1=1";
            $params = [];
            
            // Apply category filter
            if (isset($filters['category']) && !empty($filters['category'])) {
                if (is_array($filters['category'])) {
                    $placeholders = implode(',', array_fill(0, count($filters['category']), '?'));
                    $sql .= " AND category IN ($placeholders)";
                    $params = array_merge($params, $filters['category']);
                } else {
                    $sql .= " AND category = ?";
                    $params[] = $filters['category'];
                }
            }
            
            // Apply price range filter
            if (isset($filters['min_price'])) {
                $sql .= " AND price >= ?";
                $params[] = $filters['min_price'];
            }
            
            if (isset($filters['max_price'])) {
                $sql .= " AND price <= ?";
                $params[] = $filters['max_price'];
            }
            
            // Apply search filter
            if (isset($filters['search']) && !empty($filters['search'])) {
                $sql .= " AND (name LIKE ? OR description LIKE ?)";
                $searchTerm = '%' . $filters['search'] . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            // Apply product IDs filter (for wishlist filtering)
            if (isset($filters['product_ids']) && !empty($filters['product_ids'])) {
                $placeholders = implode(',', array_fill(0, count($filters['product_ids']), '?'));
                $sql .= " AND id IN ($placeholders)";
                $params = array_merge($params, $filters['product_ids']);
            }
            
            // Apply sorting
            switch ($sort) {
                case 'price_asc':
                    $sql .= " ORDER BY price ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY price DESC";
                    break;
                case 'popularity':
                    $sql .= " ORDER BY views DESC, created_at DESC";
                    break;
                case 'newest':
                default:
                    $sql .= " ORDER BY created_at DESC";
                    break;
            }
            
            // Apply pagination
            $offset = ($page - 1) * $perPage;
            $sql .= " LIMIT $perPage OFFSET $offset";
            
            return $db->fetchRows($sql, $params);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

        /**
         * Count products with filters
         * 
         * @param array $filters
         * @return int
         */
        public static function countProductsWithFilters($filters)
        {
            try {
                $db = Database::getInstance();
                $sql = "SELECT COUNT(*) as count FROM products WHERE 1=1";
                $params = [];
                
                // Apply category filter
                if (isset($filters['category']) && !empty($filters['category'])) {
                    if (is_array($filters['category'])) {
                        $placeholders = implode(',', array_fill(0, count($filters['category']), '?'));
                        $sql .= " AND category IN ($placeholders)";
                        $params = array_merge($params, $filters['category']);
                    } else {
                        $sql .= " AND category = ?";
                        $params[] = $filters['category'];
                    }
                }
                
                // Apply price range filter
                if (isset($filters['min_price'])) {
                    $sql .= " AND price >= ?";
                    $params[] = $filters['min_price'];
                }
                
                if (isset($filters['max_price'])) {
                    $sql .= " AND price <= ?";
                    $params[] = $filters['max_price'];
                }
                
                // Apply search filter
                if (isset($filters['search']) && !empty($filters['search'])) {
                    $sql .= " AND (name LIKE ? OR description LIKE ?)";
                    $searchTerm = '%' . $filters['search'] . '%';
                    $params[] = $searchTerm;
                    $params[] = $searchTerm;
                }
                
                // Apply product IDs filter (for wishlist filtering)
                if (isset($filters['product_ids']) && !empty($filters['product_ids'])) {
                    $placeholders = implode(',', array_fill(0, count($filters['product_ids']), '?'));
                    $sql .= " AND id IN ($placeholders)";
                    $params = array_merge($params, $filters['product_ids']);
                }
                
                $result = $db->fetchRow($sql, $params);
                return (int)$result['count'];
            } catch (\PDOException $e) {
                error_log($e->getMessage());
                return 0;
        }
    }
}
