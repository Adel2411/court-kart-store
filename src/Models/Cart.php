<?php

namespace App\Models;

use App\Core\Database;

class Cart
{
    /**
     * Get items in a user's cart
     *
     * @param  int  $userId  User ID
     * @return array Cart items
     */
    public static function getItems($userId)
    {
        $db = Database::getInstance();

        return $db->fetchRows('SELECT * FROM cart_items WHERE user_id = ?', [$userId]);
    }

    /**
     * Get total number of items in a user's cart
     *
     * @param  int  $userId  User ID
     * @return int Number of distinct items (not total quantity)
     */
    public static function getItemCount($userId)
    {
        $db = Database::getInstance();
        $result = $db->fetchRow('SELECT COUNT(*) as count FROM cart_items WHERE user_id = ?', [$userId]);

        return (int) ($result['count'] ?? 0);
    }

    /**
     * Add item to cart
     *
     * @param  int  $userId  User ID
     * @param  int  $productId  Product ID
     * @param  int  $quantity  Quantity
     * @return bool Success flag
     */
    public static function addItem($userId, $productId, $quantity)
    {
        $db = Database::getInstance();

        $existingItem = $db->fetchRow(
            'SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?',
            [$userId, $productId]
        );

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;

            return $db->execute(
                'UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?',
                [$newQuantity, $userId, $productId]
            );
        } else {
            return $db->execute(
                'INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)',
                [$userId, $productId, $quantity]
            );
        }
    }

    /**
     * Update cart item quantity
     *
     * @param  int  $userId  User ID
     * @param  int  $productId  Product ID
     * @param  int  $quantity  New quantity
     * @return bool Success flag
     */
    public static function updateItem($userId, $productId, $quantity)
    {
        $db = Database::getInstance();

        return $db->execute(
            'UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?',
            [$quantity, $userId, $productId]
        );
    }

    /**
     * Remove item from cart
     *
     * @param  int  $userId  User ID
     * @param  int  $productId  Product ID
     * @return bool Success flag
     */
    public static function removeItem($userId, $productId)
    {
        $db = Database::getInstance();

        return $db->execute(
            'DELETE FROM cart_items WHERE user_id = ? AND product_id = ?',
            [$userId, $productId]
        );
    }

    /**
     * Clear all items from a user's cart
     *
     * @param  int  $userId  User ID
     * @return bool Success flag
     */
    public static function clearCart($userId)
    {
        $db = Database::getInstance();

        return $db->execute(
            'DELETE FROM cart_items WHERE user_id = ?',
            [$userId]
        );
    }
}
