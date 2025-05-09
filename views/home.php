<!-- Enhanced Hero Section with Image -->
<section class="hero-section">
    <div class="hero-content" data-animation="fade-in">
        <div class="hero-badge-container">
            <span class="hero-badge">Premium Basketball Gear</span>
        </div>
        
        <h1 class="hero-title" data-animation="slide-up">
            TIES THE BASKETBALL <span class="text-highlight">COURT</span><br>
            TO YOUR SHOPPING <span class="text-highlight">JOURNEY</span>
        </h1>
        
        <p class="hero-description" data-animation="slide-up">
            Discover our expertly curated collection of premium basketball products 
            designed for athletes who demand excellence on and off the court.
        </p>
        
        <div class="hero-buttons">
            <a href="/shop" class="btn btn-primary btn-lg pulse">
                <span>Shop Collection</span>
                <i class="fas fa-arrow-right"></i>
            </a>
            <a href="#categories" class="btn btn-outline btn-lg">
                <span>Explore Categories</span>
            </a>
        </div>
        
        <div class="hero-stats">
            <div class="stat">
                <span class="stat-number" data-counter="200">0</span><span class="stat-plus">+</span>
                <p class="stat-label">Products</p>
            </div>
            <div class="stat">
                <span class="stat-number" data-counter="2000">0</span><span class="stat-plus">+</span>
                <p class="stat-label">Happy Players</p>
            </div>
            <div class="stat">
                <span class="stat-number">Free</span>
                <p class="stat-label">Premium Shipping</p>
            </div>
        </div>
    </div>
    
    <div class="scroll-indicator">
        <a href="#brands-section">
            <span>Scroll to explore</span>
            <i class="fas fa-arrow-down"></i>
        </a>
    </div>
</section>

<!-- Premium Brands Section -->
<div id="brands-section" class="brands-section">
    <div class="container">
        <div class="brands-header">
            <h3 class="section-subtitle">PARTNERED WITH THE BEST</h3>
            <p class="section-description">Trusted by professional athletes and teams worldwide</p>
        </div>
        <div class="brands-grid">
            <div class="brand-item"><img src="/assets/images/court-kart-logo.svg" alt="Brand logo"></div>
            <div class="brand-item"><img src="/assets/images/court-kart-logo.svg" alt="Brand logo"></div>
            <div class="brand-item"><img src="/assets/images/court-kart-logo.svg" alt="Brand logo"></div>
            <div class="brand-item"><img src="/assets/images/court-kart-logo.svg" alt="Brand logo"></div>
            <div class="brand-item"><img src="/assets/images/court-kart-logo.svg" alt="Brand logo"></div>
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
            <?php if (isset($newProducts) && ! empty($newProducts)) { ?>
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
                    <img src="https://www.highsnobiety.com/static-assets/thumbor/Htee-ieF33Ao4CLQlz-UiK86BSI=/1200x800/https://whatdropsnow.s3.amazonaws.com/cache/5e163ff21331b6d1695edbea94fe4dbb.jpg" alt="Basketball Shoes">
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
                    <img src="https://images.footballfanatics.com/minnesota-timberwolves/minnesota-timberwolves-jordan-statement-edition-swingman-jersey-gray-anthony-edwards-unisex_ss5_p-13363753+u-jeskdxcx68sxsjm33z8l+v-b2l97zx4nmvyf396bjgh.jpg" alt="Basketball Apparel">
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
                    <img src="https://www.sportdirect.ca/cdn/shop/products/S027_1.jpg" alt="Basketball Equipment">
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
                <img src="https://yi-files.yellowimages.com/products/1905000/1905756/3063945-cover.jpg" alt="Basketball player with Court Kart products">
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
            <?php if (isset($topProducts) && ! empty($topProducts)) { ?>
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
                    <img src="data:image/jpeg;base64,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" alt="Casual">
                    <div class="style-overlay"></div>
                </div>
                <span class="category-title">Casual</span>
            </a>
            <a href="/shop?category=Formal" class="category-style-card">
                <div class="category-image">
                    <img src="https://www.spalding.com/on/demandware.static/-/Sites-masterCatalog_SPALDING/default/dwbb5284e5/images/hi-res/77015E__FRONT.jpg" alt="Formal">
                    <div class="style-overlay"></div>
                </div>
                <span class="category-title">Formal</span>
            </a>
            <a href="/shop?category=Party" class="category-style-card">
                <div class="category-image">
                    <img src="https://img.kwcdn.com/product/fancy/d8888d4d-ee6a-44c5-8f3b-7289b549bab9.jpg" alt="Party">
                    <div class="style-overlay"></div>
                </div>
                <span class="category-title">Party</span>
            </a>
            <a href="/shop?category=Gym" class="category-style-card">
                <div class="category-image">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRUK6FlvdOpihU5NvEM69KHjiXug5FN1-ygvg&s" alt="Gym">
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
