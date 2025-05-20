<h1>Admin Dashboard</h1>

<!-- Dashboard Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orders">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $totalOrders ?></div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon sales">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">$<?= number_format($totalSales, 2) ?></div>
            <div class="stat-label">Total Sales</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon users">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label">Registered Users</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon products">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $totalProducts ?></div>
            <div class="stat-label">Products</div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Recent Orders</h2>
        <div class="admin-card-actions">
            <a href="/admin/orders" class="btn btn-primary btn-sm">View All Orders</a>
        </div>
    </div>
    
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)) { ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">
                            <div style="color: var(--gray);">
                                <i class="fas fa-shopping-bag" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                <p>No orders found</p>
                            </div>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($recentOrders as $order) { ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td>
                                <div style="font-weight: 500;"><?= htmlspecialchars($order['customer_name']) ?></div>
                            </td>
                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td><span style="font-weight: 500; color: var(--primary);">$<?= number_format($order['total_price'], 2) ?></span></td>
                            <td>
                                <?php
                                $statusClass = '';
                        switch ($order['status']) {
                            case 'pending':
                                $statusClass = 'bg-warning';
                                $icon = 'clock';
                                break;
                            case 'confirmed':
                                $statusClass = 'bg-primary';
                                $icon = 'check-circle';
                                break;
                            case 'shipped':
                                $statusClass = 'bg-info';
                                $icon = 'truck';
                                break;
                            case 'delivered':
                                $statusClass = 'bg-success';
                                $icon = 'box-open';
                                break;
                            case 'cancelled':
                                $statusClass = 'bg-danger';
                                $icon = 'times-circle';
                                break;
                            default:
                                $statusClass = 'bg-secondary';
                                $icon = 'circle';
                        }
                        ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <i class="fas fa-<?= $icon ?>"></i>
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline">View</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .bg-warning {
        background-color: var(--warning);
        color: white;
    }
    
    .bg-primary {
        background-color: var(--primary);
        color: white;
    }
    
    .bg-info {
        background-color: var(--info);
        color: white;
    }
    
    .bg-success {
        background-color: var(--success);
        color: white;
    }
    
    .bg-danger {
        background-color: var(--danger);
        color: white;
    }
    
    .bg-secondary {
        background-color: var(--secondary);
        color: white;
    }
</style>
