<div class="hero-section">
    <div class="hero-content">
        <h1>FIND CLOTHES THAT MATCHES YOUR STYLE</h1>
        <p>Discover our curated collection of premium basketball products designed for performance and style.</p>
        <div class="hero-buttons">
            <a href="/shop" class="btn btn-primary btn-lg">Shop Now</a>
            <a href="/categories" class="btn btn-outline btn-lg">Browse Categories</a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <span>200+</span>
                <p>Products</p>
            </div>
            <div class="hero-stat">
                <span>2,000+</span>
                <p>Happy Customers</p>
            </div>
            <div class="hero-stat">
                <span>30,000+</span>
                <p>Reviews</p>
            </div>
        </div>
    </div>
    <div class="hero-image">
        <img src="/assets/images/hero-image.jpg" alt="Basketball players in stylish gear">
    </div>
</div>

<div class="brands-section">
    <div class="container">
        <div class="brands-grid">
            <img src="/assets/images/brands/brand-1.svg" alt="Brand logo">
            <img src="/assets/images/brands/brand-2.svg" alt="Brand logo">
            <img src="/assets/images/brands/brand-3.svg" alt="Brand logo">
            <img src="/assets/images/brands/brand-4.svg" alt="Brand logo">
            <img src="/assets/images/brands/brand-5.svg" alt="Brand logo">
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">NEW ARRIVALS</h2>
            <a href="/shop?filter=new" class="section-link">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="product-grid">
            <?php if (isset($newProducts) && !empty($newProducts)) { ?>
                <?php foreach (array_slice($newProducts, 0, 4) as $product) { ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($product['is_new']) { ?>
                                <div class="product-badge new">New</div>
                            <?php } ?>
                            <?php if ($product['discount'] > 0) { ?>
                                <div class="product-badge sale">Sale</div>
                            <?php } ?>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="product-quick-actions">
                                <button class="quick-action-btn add-to-wishlist" data-id="<?= $product['id'] ?>" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="quick-action-btn quick-view" data-id="<?= $product['id'] ?>" title="Quick View">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="/shop/product/<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a>
                            </h3>
                            <div class="product-price">
                                $<?= number_format($product['price'], 2) ?>
                                <?php if ($product['original_price'] > $product['price']) { ?>
                                    <span class="original-price">$<?= number_format($product['original_price'], 2) ?></span>
                                <?php } ?>
                            </div>
                            <div class="product-actions">
                                <form class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-cart">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <p>No new products available at this time.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">TOP SELLING</h2>
            <a href="/shop?filter=popular" class="section-link">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="product-grid">
            <?php if (isset($topProducts) && !empty($topProducts)) { ?>
                <?php foreach (array_slice($topProducts, 0, 4) as $product) { ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($product['is_new']) { ?>
                                <div class="product-badge new">New</div>
                            <?php } ?>
                            <?php if ($product['discount'] > 0) { ?>
                                <div class="product-badge sale">Sale</div>
                            <?php } ?>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="product-quick-actions">
                                <button class="quick-action-btn add-to-wishlist" data-id="<?= $product['id'] ?>" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="quick-action-btn quick-view" data-id="<?= $product['id'] ?>" title="Quick View">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="/shop/product/<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a>
                            </h3>
                            <div class="product-price">
                                $<?= number_format($product['price'], 2) ?>
                                <?php if ($product['original_price'] > $product['price']) { ?>
                                    <span class="original-price">$<?= number_format($product['original_price'], 2) ?></span>
                                <?php } ?>
                            </div>
                            <div class="product-actions">
                                <form class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-cart">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <p>No top selling products available at this time.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="categories-section section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">BROWSE BY DRESS STYLE</h2>
        </div>
        <div class="category-cards">
            <a href="/shop?category=Casual" class="category-card">
                <div class="category-image">
                    <img src="/assets/images/categories/casual.jpg" alt="Casual">
                </div>
                <span class="category-title">Casual</span>
            </a>
            <a href="/shop?category=Formal" class="category-card">
                <div class="category-image">
                    <img src="/assets/images/categories/formal.jpg" alt="Formal">
                </div>
                <span class="category-title">Formal</span>
            </a>
            <a href="/shop?category=Party" class="category-card">
                <div class="category-image">
                    <img src="/assets/images/categories/party.jpg" alt="Party">
                </div>
                <span class="category-title">Party</span>
            </a>
            <a href="/shop?category=Gym" class="category-card">
                <div class="category-image">
                    <img src="/assets/images/categories/gym.jpg" alt="Gym">
                </div>
                <span class="category-title">Gym</span>
            </a>
        </div>
    </div>
</div>

<div class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <h2>STAY UP TO DATE ABOUT OUR LATEST OFFERS</h2>
            <form action="/newsletter/subscribe" method="post" class="newsletter-form">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Enter your email address" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </div>
            </form>
        </div>
    </div>
</div>
