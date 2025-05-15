<div class="page-header">
    <h1>My Orders</h1>
    <p class="breadcrumbs">
        <a href="/">Home</a> > 
        <span>My Orders</span>
    </p>
</div>

<?php if (empty($orders)) { ?>
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <h2>No orders yet</h2>
        <p>You haven't placed any orders yet. Start shopping to see your orders here.</p>
        <a href="/shop" class="btn btn-primary">Start Shopping</a>
    </div>
<?php } else { ?>
    <div class="order-filters">
        <div class="filter-title">Filter by status:</div>
        <div class="filter-options">
            <button class="status-filter active" data-status="all">All</button>
            <button class="status-filter" data-status="pending">Pending</button>
            <button class="status-filter" data-status="confirmed">Confirmed</button>
            <button class="status-filter" data-status="shipped">Shipped</button>
            <button class="status-filter" data-status="delivered">Delivered</button>
            <button class="status-filter" data-status="cancelled">Cancelled</button>
        </div>
    </div>

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
                <?php foreach ($orders as $order) { ?>
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
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <!-- Pagination if needed -->
    </div>
<?php } ?>

<div class="actions">
    <a href="/shop" class="btn btn-primary">
        <i class="fas fa-shopping-basket"></i> Continue Shopping
    </a>
</div>
