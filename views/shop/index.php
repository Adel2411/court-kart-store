<h1>Welcome to Court Kart Store</h1>
<p>Browse our extensive collection of basketball products.</p>

<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>No products available at this time.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><strong>Price: $<?= number_format($product['price'], 2) ?></strong></p>
                <p>In Stock: <?= $product['stock'] ?> units</p>
                <a href="/shop/product/<?= $product['id'] ?>" class="btn">View Details</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Products loaded from database.</p>
    <p>Total products loaded: <?= count($products) ?></p>
</div>
