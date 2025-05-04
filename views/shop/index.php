<h1>Welcome to Court Kart Store</h1>
<p>Browse our extensive collection of basketball products.</p>

<div class="product-grid">
    <?php for ($i = 1; $i <= 6; $i++): ?>
        <div class="product-card">
            <h3>Basketball Product <?= $i ?></h3>
            <p>This is a sample product to test the router functionality.</p>
            <p><strong>Price: $<?= rand(50, 200) ?>.99</strong></p>
            <a href="/shop/product/<?= $i ?>" class="btn">View Details</a>
        </div>
    <?php endfor; ?>
</div>
