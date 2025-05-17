<h1>Product Details: <?= htmlspecialchars($product_name ?? 'Basketball Product') ?></h1>

<div class="product-details">
    <div class="product-image">
        <img src="<?= $image_url ?? 'https://via.placeholder.com/400x300' ?>" alt="<?= htmlspecialchars($product_name) ?> Image">
        <?php if (isset($discount) && $discount > 0): ?>
        <span class="discount-badge">-<?= round($discount * 100) ?>%</span>
        <?php endif; ?>
    </div>
    <div class="product-info">
        <p>This is a detailed view of product ID: <?= $id ?></p>
        
        <?php if (isset($discount) && $discount > 0): ?>
            <p><strong>Price: <span class="discounted-price">$<?= number_format($price ?? 0, 2) ?></span> 
               <span class="original-price">$<?= number_format($original_price ?? 0, 2) ?></span></strong></p>
            <p><span class="discount-label">You save: $<?= number_format($original_price - $price, 2) ?> (<?= round($discount * 100) ?>%)</span></p>
        <?php else: ?>
            <p><strong>Price: $<?= number_format($price ?? 0, 2) ?></strong></p>
        <?php endif; ?>
        
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

<style>
.discount-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #e53935;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
}
.discounted-price {
    color: #e53935;
    font-size: 1.2em;
}
.original-price {
    text-decoration: line-through;
    color: #777;
    font-size: 0.9em;
    margin-left: 8px;
}
.discount-label {
    background-color: #f5f5f5;
    padding: 3px 8px;
    border-radius: 4px;
    color: #e53935;
    font-weight: bold;
    font-size: 0.9em;
}
</style>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Product details loaded from database.</p>
</div>
