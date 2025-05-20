<h1>My Account</h1>

<div class="account-container">
    <?php 
    // Set the active page for the sidebar
    $activePage = 'overview';
    include __DIR__ . '/../partials/account-sidebar.php'; 
    ?>
    
    <div class="account-content">
        <div class="account-card">
            <div class="account-card-header">
                <h3><i class="fas fa-info-circle"></i> Account Information</h3>
            </div>
            <div class="account-card-body">
                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value"><?= htmlspecialchars($user['name']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?= htmlspecialchars($user['email']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Account Type</div>
                        <div class="info-value">
                            <span class="badge role-badge role-<?= strtolower($user['role']) ?>">
                                <?= ucfirst(htmlspecialchars($user['role'])) ?>
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Member Since</div>
                        <div class="info-value"><?= date('F j, Y', strtotime($user['created_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="account-card">
            <div class="account-card-header">
                <h3>Recent Orders</h3>
            </div>
            <div class="account-card-body">
                <?php if (isset($orders) && ! empty($orders)) { ?>
                    <div class="recent-orders">
                        <?php foreach (array_slice($orders, 0, 3) as $order) { ?>
                            <div class="order-preview">
                                <div class="order-info">
                                    <div class="order-id">Order #<?= $order['id'] ?></div>
                                    <div class="order-date"><?= date('M j, Y', strtotime($order['created_at'])) ?></div>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </div>
                                <div class="order-total">$<?= number_format($order['total_price'], 2) ?></div>
                                <a href="/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline">View</a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="view-all">
                        <a href="/orders" class="btn btn-outline">View All Orders</a>
                    </div>
                <?php } else { ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="fas fa-shopping-bag"></i></div>
                        <h4>No orders yet</h4>
                        <p>Once you make a purchase, your orders will appear here.</p>
                        <a href="/shop" class="btn btn-primary">Start Shopping</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Link to the external account stylesheet -->
<link rel="stylesheet" href="/assets/css/pages/account.css">
