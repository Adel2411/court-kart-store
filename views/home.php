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
                <span class="stat-number counter-animation" data-counter="<?= $totalProducts ?? 200 ?>">0</span><span class="stat-plus">+</span>
                <p class="stat-label">Products</p>
            </div>
            <div class="stat">
                <span class="stat-number counter-animation" data-counter="<?= $totalUsers ?? 2000 ?>">0</span><span class="stat-plus">+</span>
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
                    <img src="https://i.pinimg.com/736x/f1/6f/a1/f16fa1eb21d483327a8294881aefa719.jpg" alt="Casual">
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
                    <img src="https://m.media-amazon.com/images/I/71E9R7iFKbS.jpg" alt="Party">
                    <div class="style-overlay"></div>
                </div>
                <span class="category-title">Party</span>
            </a>
            <a href="/shop?category=Gym" class="category-style-card">
                <div class="category-image">
                    <img src="https://st.hzcdn.com/simgs/pictures/home-gyms/2019-fall-parade-of-homes-dream-home-zawadski-homes-inc-img~2d71f2520d8ceb3c_14-1578-1-03c3984.jpg" alt="Gym">
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
                    <img src="https://cdn.britannica.com/20/264820-050-3FA4E891/devin-booker-team-united-states-olympics-paris-2024-august-3-2024.jpg" alt="Customer photo">
                    <div>
                        <h4>Devin B.</h4>
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
                    <img src="https://www.proballers.com/media/cache/torso_player/https---www.proballers.com/ul/player/sabrina-ionescu-1ef53709-8e3b-6a82-b4e5-5fe5e1a8d6a8.png" alt="Customer photo">
                    <div>
                        <h4>Sabrina L.</h4>
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
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTjtps7JFKZMQ4qs4VodkJyonumin8mtb-sWA&s" alt="Customer photo">
                    <div>
                        <h4>Coach Popovich</h4>
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
