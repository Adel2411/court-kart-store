<h1>My Account</h1>

<div class="account-container">
    <div class="account-sidebar">
        <div class="account-avatar">
            <?php if (!empty($user['profile_image'])) { ?>
                <div class="avatar-container">
                    <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="avatar-image">
                </div>
            <?php } else { ?>
                <div class="avatar-image">
                    <?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?>
                </div>
            <?php } ?>
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p><?= htmlspecialchars($user['email']) ?></p>
        </div>
        
        <div class="account-menu">
            <a href="/account" class="account-menu-item active">Account Overview</a>
            <a href="/orders" class="account-menu-item">My Orders</a>
            <a href="/account/edit" class="account-menu-item">Edit Profile</a>
            <a href="/logout" class="account-menu-item">Logout</a>
        </div>
    </div>
    
    <div class="account-content">
        <div class="account-card">
            <div class="account-card-header">
                <h3>Account Information</h3>
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
                <?php if (isset($orders) && !empty($orders)) { ?>
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
                                <div class="order-total">$<?= number_format($order['total'], 2) ?></div>
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

<style>
    .account-container {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
    }
    
    .account-sidebar {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .account-avatar {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .avatar-container {
        width: 100px;
        height: 100px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .account-menu {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .account-menu-item {
        text-decoration: none;
        color: #333;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        transition: background 0.3s;
    }
    
    .account-menu-item:hover, .account-menu-item.active {
        background: #e0e0e0;
    }
    
    .account-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .account-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .account-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .account-card-body {
        padding: 1.5rem;
    }
    
    .info-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
    }
    
    .info-label {
        font-weight: bold;
    }
    
    .info-value {
        color: #555;
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }
    
    .role-badge {
        background: #e0e0e0;
        color: #333;
    }
    
    .role-admin {
        background: #ff6b6b;
        color: #fff;
    }
    
    .role-user {
        background: #4caf50;
        color: #fff;
    }
    
    .recent-orders {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-preview {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }
    
    .order-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .order-id {
        font-weight: bold;
    }
    
    .order-date {
        color: #555;
    }
    
    .order-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }
    
    .status-pending {
        background: #ffeb3b;
        color: #333;
    }
    
    .status-completed {
        background: #4caf50;
        color: #fff;
    }
    
    .status-cancelled {
        background: #f44336;
        color: #fff;
    }
    
    .order-total {
        font-weight: bold;
    }
    
    .view-all {
        text-align: center;
        margin-top: 1rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
    }
    
    .empty-state-icon {
        font-size: 3rem;
        color: #e0e0e0;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .account-container {
            grid-template-columns: 1fr;
        }
    }
</style>
