<div class="wishlist-page">
    <div class="container">
        <header class="wishlist-header">
            <h1>My Wishlist</h1>
            <p>Items you've saved for later</p>
        </header>
        
        <?php if (empty($items)): ?>
            <div class="empty-wishlist">
                <div class="empty-wishlist-icon">
                    <i class="far fa-heart"></i>
                </div>
                <h2>Your wishlist is empty</h2>
                <p>Add items you like to your wishlist and they'll appear here.</p>
                <a href="/shop" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach ($items as $item): ?>
                    <div class="wishlist-item">
                        <div class="wishlist-item-image">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        
                        <div class="wishlist-item-info">
                            <h3 class="wishlist-item-title">
                                <a href="/shop/product/<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                            </h3>
                            
                            <div class="wishlist-item-meta">
                                <span class="wishlist-item-category"><?= htmlspecialchars($item['category']) ?></span>
                                <span class="wishlist-item-date">Added on <?= date('M j, Y', strtotime($item['added_at'])) ?></span>
                            </div>
                            
                            <div class="wishlist-item-price">
                                <?php if (isset($item['discount']) && $item['discount'] > 0): ?>
                                    <span class="current-price">$<?= number_format($item['price'], 2) ?></span>
                                    <span class="original-price">$<?= number_format($item['original_price'], 2) ?></span>
                                <?php else: ?>
                                    <span class="current-price">$<?= number_format($item['price'], 2) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="wishlist-item-actions">
                                <form action="/cart/add" method="post" class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="return_url" value="/wishlist">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                                
                                <form action="/wishlist/remove" method="post" class="remove-from-wishlist-form">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-link remove-wishlist-btn">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="/assets/js/wishlist.js"></script>
