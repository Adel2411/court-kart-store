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
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <tr>
                <td>#<?= 1000 + $i ?></td>
                <td>Customer <?= $i ?></td>
                <td><?= date('Y-m-d', strtotime("-$i days")) ?></td>
                <td><?= rand(1, 5) ?></td>
                <td>$<?= rand(50, 500) ?>.00</td>
                <td><?= ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'][array_rand(['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'])] ?></td>
                <td>
                    <a href="#" class="btn">View</a>
                    <a href="#" class="btn">Update</a>
                </td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
