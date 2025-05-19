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
    <div class="order-filters-container">
        <h3 class="filter-heading">
            <i class="fas fa-filter"></i> Filter Orders
        </h3>
        <div class="order-filters-wrapper">
            <div class="filter-pills">
                <button class="filter-pill active" data-status="all">
                    <span class="pill-dot all"></span>All
                </button>
                <button class="filter-pill" data-status="pending">
                    <span class="pill-dot pending"></span>Pending
                </button>
                <button class="filter-pill" data-status="confirmed">
                    <span class="pill-dot confirmed"></span>Confirmed
                </button>
                <button class="filter-pill" data-status="shipped">
                    <span class="pill-dot shipped"></span>Shipped
                </button>
                <button class="filter-pill" data-status="delivered">
                    <span class="pill-dot delivered"></span>Delivered
                </button>
                <button class="filter-pill" data-status="cancelled">
                    <span class="pill-dot cancelled"></span>Cancelled
                </button>
            </div>
            <div class="order-count">
                <span id="filtered-count">All</span> orders
            </div>
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
