<h1>Products in Category: <?= $category_name ?? 'Basketball Category' ?></h1>
<p>Browse products in category ID: <?= $id ?></p>

<div class="product-grid">
    <?php for ($i = 1; $i <= 4; $i++) { ?>
        <div class="product-card">
            <h3>Category <?= $id ?> - Product <?= $i ?></h3>
            <p>Sample product in this category.</p>
            <p><strong>Price: $<?= rand(50, 200) ?>.99</strong></p>
            <a href="/shop/product/<?= $i ?>" class="btn">View Details</a>
        </div>
    <?php } ?>
</div>
