<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Order;

class ApiController
{
    /**
     * Get product details for quick view
     *
     * @param int $id Product ID
     */
    public function getProduct($id)
    {
        // Get product details
        $product = Product::getById($id);
        
        if (!$product) {
            // Return 404 if product not found
            header('HTTP/1.1 404 Not Found');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Product not found']);
            return;
        }
        
        // Add additional data for display purposes
        if (!isset($product['rating'])) {
            $product['rating'] = rand(3, 5); // Example: random rating between 3-5 stars
        }
        if (!isset($product['reviews_count'])) {
            $product['reviews_count'] = rand(5, 50); // Example: random review count
        }
        // Mark new products (for example, if created in the last 7 days)
        $product['is_new'] = (strtotime($product['created_at'] ?? 'now') > strtotime('-7 days'));
        
        // Add discount information if original price exists
        if (isset($product['original_price']) && $product['original_price'] > $product['price']) {
            $product['discount'] = round(($product['original_price'] - $product['price']) / $product['original_price'] * 100);
        } else {
            $product['discount'] = 0;
        }
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($product);
    }

    /**
     * Get order details for API consumption
     *
     * @param int $id Order ID
     */
    public function getOrder($id)
    {
        // Get order details
        $orderDetails = Order::getOrderDetails($id);
        
        if (empty($orderDetails)) {
            // Return 404 if order not found
            header('HTTP/1.1 404 Not Found');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Order not found']);
            return;
        }
        
        // Process order details
        $order = $orderDetails[0];
        $items = [];
        $subtotal = 0;
        
        foreach ($orderDetails as $item) {
            if (isset($item['product_id'])) {
                $itemSubtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                $subtotal += $itemSubtotal;
                
                $items[] = [
                    'product_id' => $item['product_id'],
                    'name' => $item['product_name'] ?? 'Unknown Product',
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 0,
                    'image_url' => $item['image_url'] ?? '',
                    'subtotal' => $itemSubtotal
                ];
            }
        }
        
        $shippingCost = 0; // Free shipping
        $total = $subtotal + $shippingCost;
        
        $result = [
            'id' => $order['id'],
            'status' => $order['status'],
            'created_at' => $order['created_at'],
            'customer' => [
                'name' => $order['customer_name'] ?? 'N/A',
                'email' => $order['customer_email'] ?? 'N/A'
            ],
            'shipping_address' => $order['address'] ?? 'No address provided',
            'payment_method' => $order['payment_method'] ?? 'Credit Card',
            'items' => $items,
            'summary' => [
                'subtotal' => $subtotal,
                'shipping' => $shippingCost,
                'total' => $total
            ]
        ];
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
