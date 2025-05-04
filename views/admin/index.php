<h1>Admin Dashboard</h1>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Total Orders</h3>
        <p class="stat-number">254</p>
    </div>
    <div class="stat-card">
        <h3>Total Sales</h3>
        <p class="stat-number">$15,687.45</p>
    </div>
    <div class="stat-card">
        <h3>Total Users</h3>
        <p class="stat-number">532</p>
    </div>
    <div class="stat-card">
        <h3>Products</h3>
        <p class="stat-number">87</p>
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
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <tr>
                <td>#<?= 1000 + $i ?></td>
                <td>Customer <?= $i ?></td>
                <td><?= date('Y-m-d', strtotime("-$i days")) ?></td>
                <td>$<?= rand(50, 500) ?>.00</td>
                <td><?= ['Confirmed', 'Processing', 'Shipped', 'Delivered'][array_rand(['Confirmed', 'Processing', 'Shipped', 'Delivered'])] ?></td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
