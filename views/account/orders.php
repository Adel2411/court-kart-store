<div class="page-header">
    <h1>My Orders</h1>
    <p class="breadcrumbs">
        <a href="/">Home</a> > 
        <a href="/account">My Account</a> > 
        <span>Orders</span>
    </p>
</div>

<?php if (empty($orders)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <h2>No orders yet</h2>
        <p>You haven't placed any orders yet.</p>
        <a href="/shop" class="btn btn-primary">Start Shopping</a>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="data-table orders-table">
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
                        <td><strong>#<?= $order['id'] ?></strong></td>
                        <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                        <td><?= $order['items_count'] ?></td>
                        <td class="price-column">$<?= number_format($order['total_price'], 2) ?></td>
                        <td>
                            <span class="status-badge status-<?= $order['status'] ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="/orders/<?= $order['id'] ?>" class="btn btn-sm">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <!-- Placeholder for pagination if needed -->
    </div>
<?php endif; ?>

<div class="actions">
    <a href="/account" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Account
    </a>
    <a href="/shop" class="btn btn-primary">
        <i class="fas fa-shopping-basket"></i> Continue Shopping
    </a>
</div>
