<h1>Order Management</h1>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($orders)) { ?>
            <tr>
                <td colspan="7">No orders found.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($orders as $order) { ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= date('Y-m-d', strtotime($order['created_at'])) ?></td>
                    <td><?= $order['items_count'] ?></td>
                    <td>$<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= ucfirst($order['status']) ?></td>
                    <td>
                        <a href="#" class="btn">View</a>
                        <a href="#" class="btn">Update</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Order data loaded from database.</p>
</div>
