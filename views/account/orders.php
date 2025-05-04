<h1>My Orders</h1>

<?php if (empty($orders)): ?>
    <div class="empty-orders">
        <p>You haven't placed any orders yet.</p>
        <a href="/shop" class="btn">Start Shopping</a>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= date('Y-m-d', strtotime($order['created_at'])) ?></td>
                    <td><?= $order['items_count'] ?></td>
                    <td>$<?= number_format($order['total_price'], 2) ?></td>
                    <td>
                        <span class="status-badge status-<?= $order['status'] ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="/orders/<?= $order['id'] ?>" class="btn">View Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="actions">
    <a href="/account" class="btn">Back to Account</a>
    <a href="/shop" class="btn">Continue Shopping</a>
</div>

<style>
    .empty-orders {
        text-align: center;
        padding: 3rem;
        background: #f9f9f9;
        border-radius: 8px;
        margin: 2rem 0;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        color: white;
    }
    
    .status-pending {
        background-color: #f39c12;
    }
    
    .status-confirmed {
        background-color: #2ecc71;
    }
    
    .status-cancelled {
        background-color: #e74c3c;
    }
    
    .actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
    }
</style>
