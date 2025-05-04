<?php

namespace App\Models;

use App\Core\Database;

class Order
{
    private $id;

    private $user_id;

    private $total_price;

    private $status;

    private $created_at;

    /**
     * Get all orders from the database
     *
     * @param  int  $limit  Optional limit of orders to fetch
     * @return array Array of orders
     */
    public static function getAll($limit = 20): array
    {
        $db = Database::getInstance();

        $limit = (int) $limit;
        $sql = "SELECT o.*, u.name as customer_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                ORDER BY o.id DESC 
                LIMIT $limit";

        return $db->fetchRows($sql, []);
    }

    /**
     * Get recent orders with user information
     *
     * @param  int  $limit  Optional limit of orders to fetch
     * @return array Array of recent orders
     */
    public static function getRecent($limit = 5): array
    {
        $db = Database::getInstance();

        $limit = (int) $limit;
        $sql = "SELECT o.*, u.name as customer_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC 
                LIMIT $limit";

        return $db->fetchRows($sql, []);
    }

    /**
     * Get an order by its ID
     *
     * @param  int  $id  Order ID
     * @return array|null Order data or null if not found
     */
    public static function getById($id): ?array
    {
        $db = Database::getInstance();

        $sql = 'SELECT * FROM orders WHERE id = ?';

        return $db->fetchRow($sql, [$id]);
    }

    /**
     * Get order items for an order
     *
     * @param  int  $orderId  Order ID
     * @return array Order items
     */
    public static function getOrderItems($orderId): array
    {
        $db = Database::getInstance();

        $sql = 'SELECT oi.*, p.name as product_name 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?';

        return $db->fetchRows($sql, [$orderId]);
    }

    /**
     * Get total sales amount
     *
     * @return float Total sales
     */
    public static function getTotalSales(): float
    {
        $db = Database::getInstance();

        $sql = "SELECT SUM(total_price) as total FROM orders WHERE status = 'confirmed'";
        $result = $db->fetchRow($sql);

        return (float) ($result['total'] ?? 0);
    }

    /**
     * Get count of all orders
     *
     * @return int Count of orders
     */
    public static function getCount(): int
    {
        $db = Database::getInstance();

        $sql = 'SELECT COUNT(*) as count FROM orders';
        $result = $db->fetchRow($sql);

        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get items count for an order
     *
     * @param  int  $orderId  Order ID
     * @return int Count of items
     */
    public static function getItemsCount($orderId): int
    {
        $db = Database::getInstance();

        $sql = 'SELECT COUNT(*) as count FROM order_items WHERE order_id = ?';
        $result = $db->fetchRow($sql, [$orderId]);

        return (int) ($result['count'] ?? 0);
    }
}
