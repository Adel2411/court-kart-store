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
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <tr>
                <td><?= $i ?></td>
                <td>Basketball Product <?= $i ?></td>
                <td><?= ['Footwear', 'Apparel', 'Equipment', 'Accessories'][array_rand(['Footwear', 'Apparel', 'Equipment', 'Accessories'])] ?></td>
                <td>$<?= rand(50, 300) ?>.99</td>
                <td><?= rand(0, 100) ?></td>
                <td>
                    <a href="#" class="btn">Edit</a>
                    <a href="#" class="btn">Delete</a>
                </td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
