<!-- Hero Section - Full-Width Modern Design -->
<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <div class="hero-content">
            <div class="hero-badge-container">
                <span class="hero-badge">Premium Basketball Gear</span>
            </div>
            <h1 class="hero-title">FIND GEAR THAT MATCHES<br>YOUR STYLE & PERFORMANCE</h1>
            <p class="hero-description">Discover our expertly curated collection of premium basketball products designed for athletes who demand excellence on and off the court.</p>
            <div class="hero-buttons">
                <a href="/shop" class="btn btn-primary btn-lg">Shop Collection</a>
                <a href="#categories" class="btn btn-outline btn-lg">Explore Categories</a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="stat-number">200+</span>
                    <p class="stat-label">Products</p>
                </div>
                <div class="hero-stat">
                    <span class="stat-number">2,000+</span>
                    <p class="stat-label">Happy Players</p>
                </div>
                <div class="hero-stat">
                    <span class="stat-number">Free</span>
                    <p class="stat-label">Premium Shipping</p>
                </div>
            </div>
        </div>
        <div class="hero-image-container">
            <img src="/assets/images/hero-image.jpg" alt="Basketball players in premium CourtKart gear" class="hero-main-image">
            <div class="hero-floating-card">
                <div class="floating-card-badge">Most Popular</div>
                <div class="floating-card-product">
                    <img src="/assets/images/products/featured-shoe.png" alt="Featured basketball shoe">
                    <div class="floating-card-info">
                        <h4>Pro Court Elite</h4>
                        <span class="floating-card-price">$129.99</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Premium Brands Section -->
<div class="brands-section">
    <div class="container">
        <div class="brands-header">
            <h3 class="section-subtitle">PARTNERED WITH THE BEST</h3>
            <p class="section-description">Trusted by professional athletes and teams worldwide</p>
        </div>
        <div class="brands-grid">
            <div class="brand-item"><img src="/assets/images/brands/brand-1.svg" alt="Brand logo"></div>
            <div class="brand-item"><img src="/assets/images/brands/brand-2.svg" alt="Brand logo"></div>
            <div class="brand-item"><img src="/assets/images/brands/brand-3.svg" alt="Brand logo"></div>
            <div class="brand-item"><img src="/assets/images/brands/brand-4.svg" alt="Brand logo"></div>
            <div class="brand-item"><img src="/assets/images/brands/brand-5.svg" alt="Brand logo"></div>
        </div>
    </div>
</div>

<!-- New Arrivals Section - Modern Card Design -->
<div class="section new-arrivals">
    <div class="container">
        <div class="section-header animated-header">
            <div class="header-main">
                <h2 class="section-title">NEW ARRIVALS</h2>
                <div class="title-underline"></div>
                <p class="section-subtitle">Fresh drops to elevate your game</p>
            </div>
            <a href="/shop?filter=new" class="section-link">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="product-grid modern-grid">
            <?php if (isset($newProducts) && !empty($newProducts)) { ?>
                <?php foreach (array_slice($newProducts, 0, 4) as $product) { ?>
                    <div class="product-card modern-card">
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
                            <div class="product-card-overlay">
                                <form class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
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
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <p>New products coming soon! Check back later for fresh releases.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Featured Categories - Enhanced Visual Design -->
<div class="featured-categories" id="categories">
    <div class="container">
        <div class="section-header animated-header">
            <div class="header-main">
                <h2 class="section-title">SHOP BY CATEGORY</h2>
                <div class="title-underline"></div>
                <p class="section-description">Premium basketball equipment for every aspect of your game</p>
            </div>
        </div>
        
        <div class="category-feature-grid modern-category-grid">
            <a href="/shop?category=Footwear" class="category-feature-card">
                <div class="category-feature-image">
                    <img src="/assets/images/categories/footwear.jpg" alt="Basketball Shoes">
                    <div class="category-overlay"></div>
                </div>
                <div class="category-feature-content">
                    <h3>Footwear</h3>
                    <p>Performance shoes designed for the court</p>
                    <span class="category-link">Shop Collection <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            
            <a href="/shop?category=Apparel" class="category-feature-card">
                <div class="category-feature-image">
                    <img src="/assets/images/categories/apparel.jpg" alt="Basketball Apparel">
                    <div class="category-overlay"></div>
                </div>
                <div class="category-feature-content">
                    <h3>Apparel</h3>
                    <p>Jerseys, shorts, and training gear</p>
                    <span class="category-link">Shop Collection <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            
            <a href="/shop?category=Equipment" class="category-feature-card large">
                <div class="category-feature-image">
                    <img src="/assets/images/categories/equipment.jpg" alt="Basketball Equipment">
                    <div class="category-overlay"></div>
                </div>
                <div class="category-feature-content">
                    <h3>Equipment</h3>
                    <p>Balls, hoops, and training accessories</p>
                    <span class="category-link">Shop Collection <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Featured Banner - Premium Design -->
<div class="featured-banner premium-banner">
    <div class="container">
        <div class="banner-content">
            <div class="banner-text">
                <span class="banner-badge">SIGNATURE COLLECTION</span>
                <h2>PERFORMANCE ENGINEERED FOR CHAMPIONS</h2>
                <p>Our signature collection features premium materials and innovative technology designed to enhance your performance on the court. Worn by professionals, designed for everyone.</p>
                <a href="/shop?collection=signature" class="btn btn-light btn-lg">Shop The Collection</a>
            </div>
            <div class="banner-image">
                <img src="/assets/images/featured-banner.jpg" alt="Basketball player with Court Kart products">
            </div>
        </div>
    </div>
</div>

<!-- Top Selling Products Section -->
<div class="section top-products">
    <div class="container">
        <div class="section-header animated-header">
            <div class="header-main">
                <h2 class="section-title">TOP SELLING</h2>
                <div class="title-underline"></div>
                <p class="section-subtitle">Fan favorites with proven performance</p>
            </div>
            <a href="/shop?filter=popular" class="section-link">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="product-grid modern-grid">
            <?php if (isset($topProducts) && !empty($topProducts)) { ?>
                <?php foreach (array_slice($topProducts, 0, 4) as $product) { ?>
                    <div class="product-card modern-card">
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
                            <div class="product-card-overlay">
                                <form class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
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
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <p>Stay tuned! Our best sellers will be displayed here.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Browse By Style - Visual Enhancement -->
<div class="browse-categories-section">
    <div class="container">
        <div class="section-header animated-header">
            <div class="header-main">
                <h2 class="section-title">BROWSE BY STYLE</h2>
                <div class="title-underline"></div>
                <p class="section-description">Find the perfect look for every occasion</p>
            </div>
        </div>
        <div class="category-cards modern-style-grid">
            <a href="/shop?category=Casual" class="category-style-card">
                <div class="category-image">
                    <img src="/assets/images/categories/casual.jpg" alt="Casual">
                    <div class="style-overlay"></div>
                </div>
                <span class="category-title">Casual</span>
            </a>
            <a href="/shop?category=Formal" class="category-style-card">
                <div class="category-image">
                    <img src="/assets/images/categories/formal.jpg" alt="Formal">
                    <div class="style-overlay"></div>
                </div>
                <span class="category-title">Formal</span>
            </a>
            <a href="/shop?category=Party" class="category-style-card">
                <div class="category-image">
                    <img src="/assets/images/categories/party.jpg" alt="Party">
                    <div class="style-overlay"></div>
                </div>
                <span class="category-title">Party</span>
            </a>
            <a href="/shop?category=Gym" class="category-style-card">
                <div class="category-image">
                    <img src="/assets/images/categories/gym.jpg" alt="Gym">
                    <div class="style-overlay"></div>
                </div>
                <span class="category-title">Gym</span>
            </a>
        </div>
    </div>
</div>

<!-- Enhanced Testimonials Section -->
<div class="testimonials-section modern-testimonials">
    <div class="container">
        <div class="section-header animated-header">
            <div class="header-main">
                <h2 class="section-title">OUR HAPPY CUSTOMERS</h2>
                <div class="title-underline"></div>
                <p class="section-description">See what others have to say about their CourtKart experience</p>
            </div>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"The quality of these basketball shoes is incredible. I've noticed significant improvement in my game since switching to Court Kart products."</p>
                <div class="testimonial-author">
                    <img src="/assets/images/testimonials/user1.jpg" alt="Customer photo">
                    <div>
                        <h4>Michael R.</h4>
                        <span>Verified Buyer</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Fast shipping and exceptional customer service. The jersey I ordered fits perfectly and the material feels premium. Will definitely shop again!"</p>
                <div class="testimonial-author">
                    <img src="/assets/images/testimonials/user2.jpg" alt="Customer photo">
                    <div>
                        <h4>Jessica K.</h4>
                        <span>Verified Buyer</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="testimonial-text">"My team ordered custom jerseys and they arrived even better than we expected. The quality and attention to detail is outstanding!"</p>
                <div class="testimonial-author">
                    <img src="/assets/images/testimonials/user3.jpg" alt="Customer photo">
                    <div>
                        <h4>Coach Thomas</h4>
                        <span>Verified Buyer</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced CTA Section -->
<div class="cta-section modern-cta">
    <div class="container">
        <div class="cta-content">
            <h2>READY TO ELEVATE YOUR GAME?</h2>
            <p>Join thousands of satisfied athletes who trust CourtKart for their premium basketball gear.</p>
            <a href="/shop" class="btn btn-primary btn-lg">Shop All Products</a>
        </div>
    </div>
</div>

<!-- Enhanced Newsletter Section -->
<div class="newsletter-section modern-newsletter">
    <div class="container">
        <div class="newsletter-content">
            <div class="newsletter-text">
                <h2>STAY UPDATED</h2>
                <p>Subscribe to get exclusive offers, new product alerts, and player tips delivered straight to your inbox.</p>
            </div>
            <form class="newsletter-form modern-form">
                <div class="newsletter-input">
                    <input type="email" placeholder="Your email address" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </div>
                <label class="newsletter-checkbox">
                    <input type="checkbox" required>
                    <span>I agree to receive promotional emails from Court Kart</span>
                </label>
            </form>
        </div>
    </div>
</div>
