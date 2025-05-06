<h1>Welcome to Court Kart</h1>
<p>Browse our extensive collection of basketball products.</p>

<div class="shop-container">
    <div class="shop-sidebar">
        <div class="filter-section">
            <h3>Filter Products</h3>
            <form action="/shop" method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <div class="search-input-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="search" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label>Category</label>
                    <div class="checkbox-list">
                        <label class="checkbox-container">
                            <input type="checkbox" name="category[]" value="Footwear" <?= isset($_GET['category']) && in_array('Footwear', (array)$_GET['category']) ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            Footwear
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" name="category[]" value="Apparel" <?= isset($_GET['category']) && in_array('Apparel', (array)$_GET['category']) ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            Apparel
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" name="category[]" value="Equipment" <?= isset($_GET['category']) && in_array('Equipment', (array)$_GET['category']) ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            Equipment
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" name="category[]" value="Accessories" <?= isset($_GET['category']) && in_array('Accessories', (array)$_GET['category']) ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            Accessories
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" name="category[]" value="Merchandise" <?= isset($_GET['category']) && in_array('Merchandise', (array)$_GET['category']) ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            Merchandise
                        </label>
                    </div>
                </div>
                
                <div class="filter-group">
                    <label>Price Range</label>
                    <div class="price-slider">
                        <div class="range-slider">
                            <input type="range" id="price-range" min="0" max="1000" step="10" value="<?= $_GET['max_price'] ?? 500 ?>">
                        </div>
                        <div class="range-values">
                            <span>$0</span>
                            <span>$<span id="price-value"><?= $_GET['max_price'] ?? 500 ?></span></span>
                            <span>$1000</span>
                        </div>
                        <input type="hidden" name="min_price" id="min-price" value="<?= $_GET['min_price'] ?? 0 ?>">
                        <input type="hidden" name="max_price" id="max-price" value="<?= $_GET['max_price'] ?? 500 ?>">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label for="sort">Sort By</label>
                    <select id="sort" name="sort" class="form-control">
                        <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Newest</option>
                        <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="popularity" <?= (isset($_GET['sort']) && $_GET['sort'] == 'popularity') ? 'selected' : '' ?>>Popularity</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="/shop" class="btn btn-outline">Clear All</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="shop-main">
        <div class="shop-header">
            <h1>Shop Basketball Products</h1>
            <div class="shop-header-right">
                <div class="view-options">
                    <button class="view-option active" data-view="grid"><i class="fas fa-th"></i></button>
                    <button class="view-option" data-view="list"><i class="fas fa-list"></i></button>
                </div>
                <div class="results-count">
                    <?= count($products) ?> Products Found
                </div>
            </div>
        </div>
        
        <div class="product-grid">
            <?php if (empty($products)) { ?>
                <div class="no-products">
                    <i class="fas fa-search"></i>
                    <h3>No products found</h3>
                    <p>Try adjusting your search or filter criteria</p>
                    <a href="/shop" class="btn btn-outline">Clear Filters</a>
                </div>
            <?php } else { ?>
                <?php foreach ($products as $product) { ?>
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
                            <div class="product-meta">
                                <span class="product-category"><?= htmlspecialchars($product['category']) ?></span>
                                <?php if ($product['rating'] > 0) { ?>
                                    <div class="product-rating">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <i class="<?= $i <= $product['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                        <?php } ?>
                                        <span>(<?= $product['reviews_count'] ?? 0 ?>)</span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="product-price">
                                $<?= number_format($product['price'], 2) ?>
                                <?php if (isset($product['original_price']) && $product['original_price'] > $product['price']) { ?>
                                    <span class="original-price">$<?= number_format($product['original_price'], 2) ?></span>
                                <?php } ?>
                            </div>
                            <div class="product-actions">
                                <form class="add-to-cart-form" action="/cart/add" method="post">
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
            <?php } ?>
        </div>
        
        <?php if (!empty($products) && isset($pagination) && $pagination['total_pages'] > 1) { ?>
            <div class="pagination">
                <?php if ($pagination['current_page'] > 1) { ?>
                    <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="pagination-link">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php } ?>
                
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++) { ?>
                    <a href="?page=<?= $i ?>" class="pagination-link <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php } ?>
                
                <?php if ($pagination['current_page'] < $pagination['total_pages']) { ?>
                    <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="pagination-link">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Quick View Modal -->
<div class="product-modal" id="quickViewModal">
    <div class="product-modal-content">
        <button class="close-modal" id="closeQuickView">&times;</button>
        <div class="product-modal-body" id="quickViewContent">
            <!-- Quick view content will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Price range slider
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    const minPrice = document.getElementById('min-price');
    const maxPrice = document.getElementById('max-price');
    
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            priceValue.textContent = this.value;
            maxPrice.value = this.value;
        });
    }
    
    // View switcher
    const viewOptions = document.querySelectorAll('.view-option');
    const productGrid = document.querySelector('.product-grid');
    
    viewOptions.forEach(option => {
        option.addEventListener('click', function() {
            viewOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            if (this.dataset.view === 'list') {
                productGrid.classList.add('list-view');
            } else {
                productGrid.classList.remove('list-view');
            }
        });
    });
    
    // Quick view functionality
    const quickViewButtons = document.querySelectorAll('.quick-view');
    const quickViewModal = document.getElementById('quickViewModal');
    const closeQuickView = document.getElementById('closeQuickView');
    const quickViewContent = document.getElementById('quickViewContent');
    
    quickViewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            
            // Show loading state
            quickViewContent.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i></div>';
            quickViewModal.classList.add('active');
            
            // Fetch product data
            fetch(`/api/products/${productId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate quick view
                    quickViewContent.innerHTML = `
                        <div class="quick-view-grid">
                            <div class="quick-view-image">
                                <img src="${data.image_url}" alt="${data.name}">
                            </div>
                            <div class="quick-view-details">
                                <h2>${data.name}</h2>
                                <div class="quick-view-price">$${data.price.toFixed(2)}</div>
                                <p>${data.description}</p>
                                <form class="add-to-cart-form" action="/cart/add" method="post">
                                    <input type="hidden" name="product_id" value="${data.id}">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <div class="quantity-wrapper">
                                            <button type="button" class="quantity-btn minus">-</button>
                                            <input type="number" name="quantity" value="1" min="1" max="${data.stock}">
                                            <button type="button" class="quantity-btn plus">+</button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                                <a href="/shop/product/${data.id}" class="btn btn-outline">View Full Details</a>
                            </div>
                        </div>
                    `;
                    
                    // Initialize quantity buttons
                    initQuantityButtons();
                })
                .catch(error => {
                    console.error('Error fetching product:', error);
                    quickViewContent.innerHTML = '<div class="error">Failed to load product details</div>';
                });
        });
    });
    
    if (closeQuickView) {
        closeQuickView.addEventListener('click', function() {
            quickViewModal.classList.remove('active');
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === quickViewModal) {
            quickViewModal.classList.remove('active');
        }
    });
    
    // Function to initialize quantity buttons
    function initQuantityButtons() {
        const minusButtons = document.querySelectorAll('.quantity-btn.minus');
        const plusButtons = document.querySelectorAll('.quantity-btn.plus');
        
        minusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input');
                const value = parseInt(input.value);
                if (value > parseInt(input.min)) {
                    input.value = value - 1;
                }
            });
        });
        
        plusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input');
                const value = parseInt(input.value);
                if (value < parseInt(input.max)) {
                    input.value = value + 1;
                }
            });
        });
    }
    
    // Initialize quantity buttons on page load
    initQuantityButtons();
});
</script>
