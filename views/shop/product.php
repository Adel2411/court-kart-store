<h1>Product Details: <?= htmlspecialchars($product_name ?? 'Basketball Product') ?></h1>

<div class="product-details">
    <div class="product-image">
        <img src="<?= $image_url ?? 'https://via.placeholder.com/400x300' ?>" alt="<?= htmlspecialchars($product_name) ?> Image">
    </div>
    <div class="product-info">
        <p>This is a detailed view of product ID: <?= $id ?></p>
        <p><strong>Price: $<?= number_format($price ?? 0, 2) ?></strong></p>
        <p>Category: <?= htmlspecialchars($category ?? 'Basketball Equipment') ?></p>
        <p>Stock: <?= $stock ?? 0 ?> units available</p>
        
        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= $stock ?? 10 ?>">
            <button type="submit" class="btn">Add to Cart</button>
        </form>
    </div>
</div>

<div class="product-description">
    <h2>Description</h2>
    <p><?= htmlspecialchars($description ?? 'This is a sample product description for router testing purposes.') ?></p>
</div>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Product details loaded from database.</p>
</div>
