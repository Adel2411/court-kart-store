<?php

namespace App\Models;

use App\Core\Database;
use Exception;

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

    /**
     * Get detailed information about a specific order
     *
     * @param  int  $orderId  Order ID
     * @return array|null Order details or null if not found
     */
    public static function getOrderDetails(int $orderId): ?array
    {
        $db = Database::getInstance();

        try {
            $orderSql = 'SELECT o.*, u.name as customer_name, u.email as customer_email 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        WHERE o.id = ?';

            $orderDetails = $db->fetchRow($orderSql, [$orderId]);

            if (! $orderDetails) {
                return null;
            }

            $items = $db->fetchRows('CALL GetOrderDetails(?)', [$orderId]);

            $result = [];
            foreach ($items as $item) {
                $orderWithItems = array_merge([
                    'id' => $orderDetails['id'],
                    'user_id' => $orderDetails['user_id'],
                    'total_price' => $orderDetails['total_price'],
                    'status' => $orderDetails['status'],
                    'created_at' => $orderDetails['created_at'],
                    'updated_at' => $orderDetails['updated_at'],
                    'customer_name' => $orderDetails['customer_name'],
                    'customer_email' => $orderDetails['customer_email'],
                ], [
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'] ?? null,
                    'quantity' => $item['quantity'] ?? null,
                    'price' => $item['unit_price'] ?? null,
                    'image_url' => $item['image_url'] ?? null,
                    'subtotal' => $item['subtotal'] ?? null,
                ]);
                $result[] = $orderWithItems;
            }

            if (empty($result)) {
                $result[] = $orderDetails;
            }

            return $result;
        } catch (Exception $e) {
            error_log('Error getting order details: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Finalize an order by confirming it and emptying the cart
     * Using stored procedure instead of direct SQL queries
     *
     * @param  int  $orderId  Order ID
     * @param  int  $userId  User ID
     * @return bool Success status
     */
    public static function finalizeOrder(int $orderId, int $userId): bool
    {
        try {
            $db = Database::getInstance();

            $db->execute('CALL FinalizeOrder(?, ?)', [$orderId, $userId]);

            return true;
        } catch (Exception $e) {
            error_log('Order finalization failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get a customer's order history with item counts and product names
     * Uses the GetCustomerOrderHistory stored procedure
     *
     * @param  int  $userId  User ID
     * @return array Customer's order history
     */
    public static function getCustomerOrderHistory(int $userId): array
    {
        $db = Database::getInstance();

        try {
            $orders = $db->fetchRows('CALL GetCustomerOrderHistory(?)', [$userId]);

            if (empty($orders)) {
                $sql = 'SELECT o.id, o.total_price, o.status, o.created_at, 
                       COUNT(oi.id) as items_count
                       FROM orders o 
                       LEFT JOIN order_items oi ON o.id = oi.order_id 
                       WHERE o.user_id = ? 
                       GROUP BY o.id 
                       ORDER BY o.created_at DESC';

                $orders = $db->fetchRows($sql, [$userId]);
            }

            foreach ($orders as &$order) {
                if (isset($order['order_id']) && ! isset($order['id'])) {
                    $order['id'] = $order['order_id'];
                }

                if (isset($order['total']) && ! isset($order['total_price'])) {
                    $order['total_price'] = $order['total'];
                }
            }

            return $orders;
        } catch (Exception $e) {
            error_log('Error getting customer order history: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Create a new order from cart items
     * Trigger: BeforeOrderItemInsert will prevent orders with insufficient stock
     *
     * @param  int  $userId  User ID
     * @param  array  $items  Order items
     * @param  float  $totalPrice  Total price
     * @return int|bool Order ID on success, false on failure
     */
    public static function createOrder(int $userId, array $items, float $totalPrice): int|bool
    {
        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $sql = "INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')";
            $db->execute($sql, [$userId, $totalPrice]);
            $orderId = (int) $db->getLastInsertId();

            foreach ($items as $item) {
                $sql = 'INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)';
                $db->execute($sql, [
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price'],
                ]);
            }

            $db->commit();

            return $orderId;
        } catch (Exception $e) {
            $db->rollBack();
            error_log('Order creation failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Cancel an order
     * Triggers: AfterOrderCancelled will restore product stock
     * Triggers: LogCanceledOrder will log the cancellation
     *
     * @param  int  $orderId  Order ID
     * @param  int  $userId  User ID
     * @param  string  $reason  Cancellation reason
     * @return bool Success status
     */
    public static function cancelOrder(int $orderId, int $userId, string $reason = 'User requested cancellation'): bool
    {
        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $sql = "SELECT id FROM orders WHERE id = ? AND user_id = ? AND status != 'cancelled'";
            $order = $db->fetchRow($sql, [$orderId, $userId]);

            if (! $order) {
                return false;
            }

            $sql = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
            $db->execute($sql, [$orderId]);

            $sql = 'INSERT INTO canceled_orders (order_id, reason, canceled_at) 
                   VALUES (?, ?, NOW())
                   ON DUPLICATE KEY UPDATE reason = ?, canceled_at = NOW()';
            $db->execute($sql, [$orderId, $reason, $reason]);

            $db->commit();

            return true;

        } catch (Exception $e) {
            $db->rollBack();
            error_log('Error cancelling order: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Update order status
     * Triggers will handle stock updates for status changes to 'confirmed' or 'cancelled'
     *
     * @param  int  $orderId  Order ID
     * @param  string  $status  New status
     * @return bool Success status
     */
    public static function updateStatus(int $orderId, string $status): bool
    {
        $db = Database::getInstance();
        $validStatuses = self::getValidStatuses();

        if (! in_array($status, $validStatuses)) {
            return false;
        }

        try {
            $sql = 'UPDATE orders SET status = ? WHERE id = ?';
            $db->execute($sql, [$status, $orderId]);

            return true;
        } catch (Exception $e) {
            error_log('Error updating order status: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get all orders from the database filtered by status
     *
     * @param  string  $status  Status to filter by
     * @param  int  $limit  Optional limit of orders to fetch
     * @return array Array of filtered orders
     */
    public static function getAllByStatus(string $status, int $limit = 20): array
    {
        $db = Database::getInstance();

        $validStatus = self::validateStatus($status);
        $limit = (int) $limit;

        $sql = "SELECT o.*, u.name as customer_name, u.email as customer_email  
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.status = ? 
                ORDER BY o.id DESC 
                LIMIT $limit";

        return $db->fetchRows($sql, [$validStatus]);
    }

    /**
     * Validate order status to prevent SQL injection
     *
     * @param  string  $status  Status to validate
     * @return string Validated status
     */
    private static function validateStatus(string $status): string
    {
        $validStatuses = self::getValidStatuses();

        return in_array($status, $validStatuses) ? $status : 'pending';
    }

    /**
     * Get valid order statuses from database schema
     *
     * @return array Array of valid status values
     */
    private static function getValidStatuses(): array
    {
        static $statuses = null;

        if ($statuses === null) {
            try {
                $db = Database::getInstance();

                $sql = "SELECT SUBSTRING(COLUMN_TYPE, 6, LENGTH(COLUMN_TYPE) - 6) as enum_list
                        FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE TABLE_SCHEMA = DATABASE()
                        AND TABLE_NAME = 'orders'
                        AND COLUMN_NAME = 'status'";

                $result = $db->fetchRow($sql);

                if ($result && isset($result['enum_list'])) {
                    $enumList = $result['enum_list'];

                    $enumList = substr($enumList, 1, -1);
                    $statuses = explode("','", $enumList);
                } else {
                    $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
                }
            } catch (Exception $e) {
                error_log('Error fetching valid order statuses: '.$e->getMessage());
                $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
            }
        }

        return $statuses;
    }
}
