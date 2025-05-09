<?php
/**
 * Order details view
 */

// Check if $order is set
$order = $order ?? null;
$orderItems = $orderItems ?? [];
?>

<div class="container order-details">
    <h1>Order Details</h1>
    
    <?php if ($order): ?>
        <div class="order-summary card">
            <div class="card-header">
                <h2>Order #<?= htmlspecialchars($order->id ?? 'N/A') ?></h2>
                <span class="order-date">Placed on: <?= htmlspecialchars($order->created_at ?? 'N/A') ?></span>
            </div>
            
            <div class="card-body">
                <div class="order-status">
                    <strong>Status:</strong> 
                    <span class="badge <?= ($order->status ?? '') === 'completed' ? 'badge-success' : 'badge-pending' ?>">
                        <?= htmlspecialchars(ucfirst($order->status ?? 'N/A')) ?>
                    </span>
                </div>
                
                <div class="order-address">
                    <h3>Shipping Address</h3>
                    <address>
                        <?= htmlspecialchars($order->address ?? 'No address provided') ?>
                    </address>
                </div>
                
                <div class="order-payment">
                    <h3>Payment Information</h3>
                    <p>Payment Method: <?= htmlspecialchars($order->payment_method ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
        
        <div class="order-items">
            <h3>Items in Your Order</h3>
            
            <?php if (!empty($orderItems)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item->product_name ?? 'Unknown Product') ?></td>
                                <td>$<?= number_format(($item->price ?? 0), 2) ?></td>
                                <td><?= htmlspecialchars($item->quantity ?? 0) ?></td>
                                <td>$<?= number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Subtotal</th>
                            <td>$<?= number_format(($order->subtotal ?? 0), 2) ?></td>
                        </tr>
                        <tr>
                            <th colspan="3">Shipping</th>
                            <td>$<?= number_format(($order->shipping_cost ?? 0), 2) ?></td>
                        </tr>
                        <tr>
                            <th colspan="3">Total</th>
                            <td>$<?= number_format(($order->total ?? 0), 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php else: ?>
                <p>No items found in this order.</p>
            <?php endif; ?>
        </div>
        
        <?php if (($order->status ?? '') !== 'cancelled' && ($order->status ?? '') !== 'completed'): ?>
            <div class="order-actions">
                <form action="/orders/<?= htmlspecialchars($order->id ?? '') ?>/cancel" method="POST">
                    <button type="submit" class="btn btn-danger">Cancel Order</button>
                </form>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-warning">
            <p>Order not found or you don't have permission to view this order.</p>
        </div>
        <a href="/orders" class="btn btn-primary">Back to Orders</a>
    <?php endif; ?>
</div>
