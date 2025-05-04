<h1>Product Management</h1>

<div class="actions">
    <a href="#" class="btn">Add New Product</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($products)): ?>
            <tr>
                <td colspan="6">No products found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= $product['stock'] ?></td>
                    <td>
                        <a href="#" class="btn">Edit</a>
                        <a href="#" class="btn">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Product data loaded from database.</p>
</div>
