<h1>Product Details: <?= $product_name ?? 'Basketball Product' ?></h1>

<div class="product-details">
    <div class="product-image">
        <img src="https://via.placeholder.com/400x300" alt="Product Image">
    </div>
    <div class="product-info">
        <p>This is a detailed view of product ID: <?= $id ?></p>
        <p><strong>Price: $<?= rand(50, 200) ?>.99</strong></p>
        <p>Category: Basketball <?= $category ?? 'Equipment' ?></p>
        <p>Stock: <?= rand(5, 50) ?> units available</p>
        
        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1">
            <button type="submit" class="btn">Add to Cart</button>
        </form>
    </div>
</div>

<div class="product-description">
    <h2>Description</h2>
    <p>This is a sample product description for router testing purposes. In a real application, this would contain detailed information about the product's features, materials, and specifications.</p>
</div>
