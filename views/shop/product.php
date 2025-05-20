<head>
    <title><?= htmlspecialchars($product_name) ?> - Court Kart</title>
    <link rel="icon" type="image/x-icon" href="public/assets/images/court-kart-logo-dark.ico">
    <link rel="stylesheet" href="/assets/css/pages/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<div class="product-detail">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Home</a>
            <span class="separator">/</span>
            <a href="/shop">Shop</a>
            <span class="separator">/</span>
            <a href="/shop/category/<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></a>
            <span class="separator">/</span>
            <span class="current"><?= htmlspecialchars($product_name) ?></span>
        </div>
        
        <div class="product-main">
            <div class="product-gallery">
                <div class="product-image-main">
                    <img src="<?= htmlspecialchars($image_url) ?>" alt="<?= htmlspecialchars($product_name) ?>" id="mainProductImage">
                </div>
                <?php if(isset($discount) && $discount > 0): ?>
                    <div class="product-badge sale">-<?= round($discount * 100) ?>%</div>
                <?php endif; ?>
            </div>
            
            <div class="product-info">
                <h1 class="product-title"><?= htmlspecialchars($product_name) ?></h1>
                
                <div class="product-meta">
                    <div class="product-category">
                        <i class="fas fa-tag"></i> <?= htmlspecialchars($category) ?>
                    </div>
                    
                    <div class="product-rating-summary">
                        <div class="stars-display">
                            <?php
                            // Get average rating from reviews
                            $avgRating = isset($average_rating) ? $average_rating : 0;
                            $fullStars = floor($avgRating);
                            $halfStar = $avgRating - $fullStars >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            
                            // Display full stars
                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="fas fa-star"></i>';
                            }
                            
                            // Display half star if needed
                            if ($halfStar) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            }
                            
                            // Display empty stars
                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<i class="far fa-star"></i>';
                            }
                            ?>
                            <span class="rating-number"><?= number_format($avgRating, 1) ?></span>
                            <span class="reviews-count">(<?= isset($reviews_count) ? $reviews_count : 0 ?> reviews)</span>
                        </div>
                    </div>
                </div>
                
                <div class="product-price">
                    <?php if(isset($discount) && $discount > 0): ?>
                        <span class="current-price">$<?= number_format($price, 2) ?></span>
                        <span class="original-price">$<?= number_format($original_price, 2) ?></span>
                    <?php else: ?>
                        <span class="current-price">$<?= number_format($price, 2) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <p><?= nl2br(htmlspecialchars($description)) ?></p>
                </div>
                
                <div class="product-actions">
                    <form action="/cart/add" method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?= $id ?>">
                        
                        <div class="quantity-control">
                            <label for="quantity">Quantity</label>
                            <div class="quantity-wrapper">
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $stock ?>" <?= $stock < 1 ? 'disabled' : '' ?>>
                            </div>
                        </div>
                        
                        <div class="stock-status">
                            <?php if ($stock > 10): ?>
                                <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock (<?= $stock ?> available)</span>
                            <?php elseif ($stock > 0): ?>
                                <span class="low-stock"><i class="fas fa-exclamation-circle"></i> Low Stock (only <?= $stock ?> left)</span>
                            <?php else: ?>
                                <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-buttons">
                            <button type="submit" class="btn btn-primary" <?= $stock < 1 ? 'disabled' : '' ?>>
                                <i class="fas fa-shopping-bag"></i> Add to Cart
                            </button>
                            <button type="button" class="btn btn-outline wishlist-btn" data-action="wishlist" data-id="<?= $id ?>">
                                <i class="far fa-heart"></i> <span>Add to Wishlist</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tabs">
                <div class="tab-item active" data-tab="reviews">Reviews (<?= isset($reviews_count) ? $reviews_count : 0 ?>)</div>
                <div class="tab-item" data-tab="details">Details</div>
                <div class="tab-item" data-tab="shipping">Shipping & Returns</div>
            </div>
            
            <div class="tab-content active" id="reviews">
                <!-- Reviews Section -->
                <div class="reviews-section">
                    <div class="reviews-summary">
                        <div class="rating-overview">
                            <div class="average-rating">
                                <div class="big-rating"><?= number_format($avgRating, 1) ?></div>
                                <div class="rating-stars">
                                    <?php
                                    // Reuse the same star rating code
                                    // Display full stars
                                    for ($i = 0; $i < $fullStars; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    
                                    // Display half star if needed
                                    if ($halfStar) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    
                                    // Display empty stars
                                    for ($i = 0; $i < $emptyStars; $i++) {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                                <div class="rating-count"><?= isset($reviews_count) ? $reviews_count : 0 ?> reviews</div>
                            </div>
                            
                            <div class="rating-bars">
                                <?php 
                                // Show distribution of ratings
                                $ratingDistribution = $rating_distribution ?? [
                                    5 => 0,
                                    4 => 0,
                                    3 => 0,
                                    2 => 0,
                                    1 => 0
                                ];
                                
                                $totalReviews = isset($reviews_count) ? $reviews_count : 0;
                                
                                for ($i = 5; $i >= 1; $i--) {
                                    $count = $ratingDistribution[$i] ?? 0;
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                ?>
                                <div class="rating-bar-row">
                                    <div class="stars-count"><?= $i ?> <i class="fas fa-star"></i></div>
                                    <div class="rating-bar-container">
                                        <div class="rating-bar" style="width: <?= $percentage ?>%"></div>
                                    </div>
                                    <div class="rating-count"><?= $count ?></div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="write-review-button">
                            <button id="writeReviewBtn" class="btn btn-primary">
                                <i class="fas fa-pen"></i> Write a Review
                            </button>
                        </div>
                        <?php else: ?>
                        <div class="login-to-review">
                            <p>Please <a href="/login?redirect=/shop/product/<?= $id ?>">login</a> to write a review.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Review Form (hidden by default) -->
                    <div id="reviewFormContainer" class="review-form-container" style="display: none;">
                        <div class="review-form">
                            <h3>Write Your Review</h3>
                            <form id="productReviewForm" action="/shop/product/<?= $id ?>/review" method="post">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <input type="hidden" name="rating" id="ratingInput" value="0">
                                
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <div class="star-rating">
                                        <span class="star" data-rating="1"><i class="far fa-star"></i></span>
                                        <span class="star" data-rating="2"><i class="far fa-star"></i></span>
                                        <span class="star" data-rating="3"><i class="far fa-star"></i></span>
                                        <span class="star" data-rating="4"><i class="far fa-star"></i></span>
                                        <span class="star" data-rating="5"><i class="far fa-star"></i></span>
                                        <span class="rating-text">Select a rating</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reviewText">Your Review</label>
                                    <textarea id="reviewText" name="review_text" rows="5" placeholder="Share your experience with this product..." required></textarea>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="button" id="cancelReviewBtn" class="btn btn-outline">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Reviews List -->
                    <div class="reviews-list">
                        <h3>Customer Reviews</h3>
                        
                        <?php if (empty($reviews)): ?>
                        <div class="no-reviews">
                            <p>This product has no reviews yet. Be the first to share your thoughts!</p>
                        </div>
                        <?php else: ?>
                            <?php foreach ($reviews as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <?php if (!empty($review['profile_image'])): ?>
                                            <div class="reviewer-avatar">
                                                <img src="<?= htmlspecialchars($review['profile_image']) ?>" alt="Reviewer">
                                            </div>
                                        <?php else: ?>
                                            <div class="reviewer-avatar">
                                                <?= strtoupper(substr($review['name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="reviewer-details">
                                            <div class="reviewer-name"><?= htmlspecialchars($review['name'] ?? 'Anonymous') ?></div>
                                            <div class="review-date"><?= date('M j, Y', strtotime($review['created_at'])) ?></div>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <?php
                                        $rating = $review['rating'];
                                        $fullStars = floor($rating);
                                        $halfStar = $rating - $fullStars >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                        
                                        // Display full stars
                                        for ($i = 0; $i < $fullStars; $i++) {
                                            echo '<i class="fas fa-star"></i>';
                                        }
                                        
                                        // Display half star if needed
                                        if ($halfStar) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        }
                                        
                                        // Display empty stars
                                        for ($i = 0; $i < $emptyStars; $i++) {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="review-content">
                                    <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                            <div class="pagination">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <a href="/shop/product/<?= $id ?>?page=<?= $pagination['current_page'] - 1 ?>" class="pagination-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                    <a href="/shop/product/<?= $id ?>?page=<?= $i ?>" class="pagination-link <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <a href="/shop/product/<?= $id ?>?page=<?= $pagination['current_page'] + 1 ?>" class="pagination-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="tab-content" id="details">
                <div class="product-details">
                    <h3>Product Specifications</h3>
                    <div class="specifications">
                        <div class="spec-item">
                            <div class="spec-label">Category</div>
                            <div class="spec-value"><?= htmlspecialchars($category) ?></div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-label">SKU</div>
                            <div class="spec-value">CK-<?= str_pad($id, 5, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <!-- Add more specs as needed -->
                    </div>
                </div>
            </div>
            
            <div class="tab-content" id="shipping">
                <div class="shipping-info">
                    <h3>Shipping Information</h3>
                    <p>Free standard shipping on all orders over $75. Delivery time is typically 3-5 business days.</p>
                    
                    <h3>Return Policy</h3>
                    <p>If you're not fully satisfied with your purchase, you can return it within 30 days for a full refund.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add wishlist functionality -->
<script src="/assets/js/wishlist.js"></script>
<script src="/assets/js/pages/product.js"></script>
