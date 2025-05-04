<h1>Admin Dashboard</h1>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Total Orders</h3>
        <p class="stat-number"><?= $totalOrders ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Sales</h3>
        <p class="stat-number">$<?= number_format($totalSales, 2) ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Users</h3>
        <p class="stat-number"><?= $totalUsers ?></p>
    </div>
    <div class="stat-card">
        <h3>Products</h3>
        <p class="stat-number"><?= $totalProducts ?></p>
    </div>
</div>

<h2>Recent Orders</h2>
<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($recentOrders)) { ?>
            <tr>
                <td colspan="5">No recent orders found.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($recentOrders as $order) { ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= date('Y-m-d', strtotime($order['created_at'])) ?></td>
                    <td>$<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= ucfirst($order['status']) ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Admin dashboard data loaded from database.</p>
</div>
