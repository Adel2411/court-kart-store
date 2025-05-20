<div class="wishlist-container">
    <header class="wishlist-header">
        <div class="header-content">
            <h1>My Wishlist</h1>
            <p class="header-subtitle">Items you've saved for later</p>
            
            <?php if (!empty($items)): ?>
                <span class="wishlist-badge">
                    <i class="fas fa-heart"></i> <?= count($items) ?> <?= count($items) === 1 ? 'item' : 'items' ?>
                </span>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($items)): ?>
            <div class="header-actions">
                <a href="/shop" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </header>
    
    <?php if (empty($items)): ?>
        <div class="wishlist-empty">
            <div class="empty-illustration">
                <div class="heart-animation">
                    <i class="far fa-heart"></i>
                </div>
            </div>
            <h2>Your wishlist is empty</h2>
            <p>Discover products and add them to your wishlist to save for later.</p>
            <div class="empty-actions">
                <a href="/shop" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i>
                    Browse Products
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="wishlist-items">
            <?php foreach ($items as $item): ?>
                <div class="wishlist-card" data-product-id="<?= $item['id'] ?>">
                    <div class="card-media">
                        <a href="/shop/product/<?= $item['id'] ?>" class="item-image-link">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </a>
                        <form action="/wishlist/remove" method="post" class="remove-form">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="remove-btn" title="Remove from wishlist">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        <?php if (isset($item['discount']) && $item['discount'] > 0): ?>
                            <span class="discount-badge">
                                <?= $item['discount'] ?>% OFF
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-content">
                        <div class="item-category">
                            <span><?= htmlspecialchars($item['category']) ?></span>
                        </div>
                        
                        <h3 class="item-title">
                            <a href="/shop/product/<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                        </h3>
                        
                        <div class="item-price">
                            <?php if (isset($item['discount']) && $item['discount'] > 0): ?>
                                <span class="current-price">$<?= number_format($item['price'], 2) ?></span>
                                <span class="original-price">$<?= number_format($item['original_price'], 2) ?></span>
                            <?php else: ?>
                                <span class="current-price">$<?= number_format($item['price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="item-meta">
                            <span class="added-date">
                                <i class="far fa-calendar-alt"></i>
                                Added <?= date('M j, Y', strtotime($item['added_at'])) ?>
                            </span>
                        </div>
                        
                        <form action="/cart/add" method="post" class="add-to-cart-form">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="return_url" value="/wishlist">
                            <button type="submit" class="btn btn-primary btn-add-to-cart">
                                <i class="fas fa-shopping-bag"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="wishlist-footer">
            <a href="/shop" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Continue Shopping
            </a>
        </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="/assets/css/pages/wishlist.css">
<script src="/assets/js/wishlist.js"></script>