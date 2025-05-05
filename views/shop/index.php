<h1>Welcome to Court Kart</h1>
<p>Browse our extensive collection of basketball products.</p>

<div class="search-filter-container">
    <form action="/shop" method="GET" class="search-form">
        <div class="search-row">
            <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="btn">Search</button>
        </div>
        
        <div class="filter-options">
            <div class="filter-group">
                <label>Category:</label>
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="Footwear" <?= isset($_GET['category']) && $_GET['category'] == 'Footwear' ? 'selected' : '' ?>>Footwear</option>
                    <option value="Apparel" <?= isset($_GET['category']) && $_GET['category'] == 'Apparel' ? 'selected' : '' ?>>Apparel</option>
                    <option value="Equipment" <?= isset($_GET['category']) && $_GET['category'] == 'Equipment' ? 'selected' : '' ?>>Equipment</option>
                    <option value="Accessories" <?= isset($_GET['category']) && $_GET['category'] == 'Accessories' ? 'selected' : '' ?>>Accessories</option>
                    <option value="Merchandise" <?= isset($_GET['category']) && $_GET['category'] == 'Merchandise' ? 'selected' : '' ?>>Merchandise</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Price Range:</label>
                <div class="price-inputs">
                    <input type="number" name="min_price" placeholder="Min" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                    <span>to</span>
                    <input type="number" name="max_price" placeholder="Max" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                </div>
            </div>
            
            <div class="filter-group">
                <label>Sort By:</label>
                <select name="sort">
                    <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : '' ?>>Name (A-Z)</option>
                    <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : '' ?>>Name (Z-A)</option>
                    <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Price (Low to High)</option>
                    <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Price (High to Low)</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-outline">Apply Filters</button>
            <a href="/shop" class="btn btn-link">Clear Filters</a>
        </div>
    </form>
</div>

<div class="product-grid">
    <?php if (empty($products)) { ?>
        <p>No products available at this time.</p>
    <?php } else { ?>
        <?php foreach ($products as $product) { ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><strong>Price: $<?= number_format($product['price'], 2) ?></strong></p>
                <p>In Stock: <?= $product['stock'] ?> units</p>
                <div class="product-actions">
                    <a href="/shop/product/<?= $product['id'] ?>" class="btn">View Details</a>
                    <form action="/cart/add" method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Products loaded from database.</p>
    <p>Total products loaded: <?= count($products) ?></p>
</div>
