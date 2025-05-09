<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Court Kart</title>
    <link rel="stylesheet" href="/assets/css/pages/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<main class="shop">
    <header class="shop-header">
        <div class="container">
            <h1>Basketball Collection</h1>
            <p>Discover premium gear designed for champions on and off the court</p>
        </div>
    </header>

    <div class="container">
        <div class="shop-layout">
            <!-- Filters sidebar -->
            <aside class="filters">
                <div class="filters-header">
                    <h2>Filters</h2>
                    <button type="button" class="filters-toggle" aria-label="Toggle filters">
                        <i class="fas fa-sliders-h"></i>
                    </button>
                </div>
                
                <div class="active-filters" id="active-filters"></div>
                
                <form action="/shop" method="GET" id="filters-form">
                    <!-- Search filter -->
                    <details class="filter-group" open>
                        <summary class="filter-title">
                            <i class="fas fa-search"></i> 
                            <span>Search</span>
                        </summary>
                        <div class="filter-content">
                            <div class="search-input">
                                <input type="text" name="search" id="search" placeholder="Search products..." 
                                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                        </div>
                    </details>
                    
                    <!-- Category filter -->
                    <details class="filter-group" open>
                        <summary class="filter-title">
                            <i class="fas fa-tags"></i> 
                            <span>Category</span>
                        </summary>
                        <div class="filter-content">
                            <div class="checkbox-group">
                                <?php 
                                $categories = [
                                    'Footwear' => '<i class="fas fa-shoe-prints"></i>',
                                    'Apparel' => '<i class="fas fa-tshirt"></i>',
                                    'Equipment' => '<i class="fas fa-basketball-ball"></i>',
                                    'Accessories' => '<i class="fas fa-glasses"></i>',
                                    'Merchandise' => '<i class="fas fa-store"></i>'
                                ];
                                
                                foreach($categories as $category => $icon): 
                                    $isChecked = isset($_GET['category']) && in_array($category, (array)$_GET['category']);
                                ?>
                                    <label class="checkbox">
                                        <input type="checkbox" name="category[]" value="<?= $category ?>" 
                                              <?= $isChecked ? 'checked' : '' ?>>
                                        <span class="checkbox-mark"></span>
                                        <div class="checkbox-label">
                                            <?= $icon ?> <?= $category ?>
                                            <span class="count" id="count-<?= strtolower($category) ?>"></span>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </details>
                    
                    <!-- Price range filter -->
                    <details class="filter-group" open>
                        <summary class="filter-title">
                            <i class="fas fa-dollar-sign"></i> 
                            <span>Price Range</span>
                        </summary>
                        <div class="filter-content">
                            <div class="price-range">
                                <div class="price-inputs">
                                    <div class="input-group">
                                        <span class="currency">$</span>
                                        <input type="number" name="min_price" id="min-price" 
                                              value="<?= $_GET['min_price'] ?? 0 ?>" min="0" max="1000" step="10" placeholder="Min">
                                    </div>
                                    <span class="separator">to</span>
                                    <div class="input-group">
                                        <span class="currency">$</span>
                                        <input type="number" name="max_price" id="max-price" 
                                              value="<?= $_GET['max_price'] ?? 500 ?>" min="0" max="1000" step="10" placeholder="Max">
                                    </div>
                                </div>
                                
                                <div class="price-slider">
                                    <div class="slider-track"></div>
                                    <div class="slider-labels">
                                        <span>$0</span>
                                        <span>$500</span>
                                        <span>$1000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>
                    
                    <!-- Sort options -->
                    <details class="filter-group" open>
                        <summary class="filter-title">
                            <i class="fas fa-sort"></i> 
                            <span>Sort By</span>
                        </summary>
                        <div class="filter-content">
                            <div class="radio-group">
                                <?php 
                                $sortOptions = [
                                    'newest' => ['label' => 'Newest First', 'icon' => 'fa-clock'],
                                    'price_asc' => ['label' => 'Price: Low to High', 'icon' => 'fa-sort-amount-down'],
                                    'price_desc' => ['label' => 'Price: High to Low', 'icon' => 'fa-sort-amount-up'],
                                    'popularity' => ['label' => 'Popularity', 'icon' => 'fa-fire']
                                ];
                                
                                $currentSort = $_GET['sort'] ?? 'newest';
                                
                                foreach($sortOptions as $value => $option): 
                                ?>
                                    <label class="radio">
                                        <input type="radio" name="sort" value="<?= $value ?>" 
                                             <?= ($currentSort == $value) ? 'checked' : '' ?>>
                                        <span class="radio-mark"></span>
                                        <div class="radio-label">
                                            <i class="fas <?= $option['icon'] ?>"></i>
                                            <?= $option['label'] ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </details>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn primary">
                            <i class="fas fa-check"></i> Apply Filters
                        </button>
                        <button type="button" id="clear-filters" class="btn outline">
                            <i class="fas fa-times"></i> Clear All
                        </button>
                    </div>
                </form>
            </aside>
            
            <!-- Products main content -->
            <section class="products">
                <div class="products-header">
                    <h2>Our Collection</h2>
                    <div class="products-toolbar">
                        <div class="view-selector">
                            <button class="view-btn active" data-view="grid" aria-label="Grid view">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" data-view="list" aria-label="List view">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                        <div class="product-count">
                            <?= count($products) ?> Products
                        </div>
                    </div>
                </div>
                
                <?php if (empty($products)) { ?>
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No products found</h3>
                        <p>Try adjusting your filters or search criteria</p>
                        <a href="/shop" class="btn outline">Clear Filters</a>
                    </div>
                <?php } else { ?>
                    <div class="product-grid" id="products-grid">
                        <?php foreach ($products as $product) { ?>
                            <article class="product-card">
                                <div class="product-media">
                                    <?php if ($product['is_new']) { ?>
                                        <span class="badge new">New</span>
                                    <?php } ?>
                                    <?php if (isset($product['discount']) && $product['discount'] > 0) { ?>
                                        <span class="badge sale">Sale</span>
                                    <?php } ?>
                                    
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    
                                    <div class="quick-actions">
                                        <button type="button" class="quick-action-btn" data-action="wishlist" data-id="<?= $product['id'] ?>">
                                            <i class="far fa-heart"></i>
                                        </button>
                                        <button type="button" class="quick-action-btn" data-action="quickview" data-id="<?= $product['id'] ?>">
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
                                        
                                        <?php if (isset($product['rating']) && $product['rating'] > 0) { ?>
                                            <div class="product-rating">
                                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                    <i class="<?= $i <= $product['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                                <?php } ?>
                                                <span>(<?= $product['reviews_count'] ?? 0 ?>)</span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="product-price">
                                        <span class="current-price">$<?= number_format($product['price'], 2) ?></span>
                                        <?php if (isset($product['original_price']) && $product['original_price'] > $product['price']) { ?>
                                            <span class="original-price">$<?= number_format($product['original_price'], 2) ?></span>
                                        <?php } ?>
                                    </div>
                                    
                                    <form class="product-actions" action="/cart/add" method="post">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn primary">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </article>
                        <?php } ?>
                    </div>
                    
                    <?php if (isset($pagination) && $pagination['total_pages'] > 1) { ?>
                        <nav class="pagination" aria-label="Products pagination">
                            <?php 
                            $queryString = $pagination['query_string'] ?? '';
                            $queryConnector = !empty($queryString) ? '&' : '';
                            ?>
                            
                            <div class="pagination-links">
                                <?php if ($pagination['current_page'] > 1) { ?>
                                    <a href="?<?= $queryString . $queryConnector ?>page=<?= $pagination['current_page'] - 1 ?>" class="page-link" aria-label="Previous page">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                <?php } ?>
                                
                                <?php
                                $range = 2;
                                $startPage = max(1, $pagination['current_page'] - $range);
                                $endPage = min($pagination['total_pages'], $pagination['current_page'] + $range);
                                
                                if ($startPage > 1) { ?>
                                    <a href="?<?= $queryString . $queryConnector ?>page=1" class="page-link">1</a>
                                    <?php if ($startPage > 2) { ?>
                                        <span class="page-ellipsis">...</span>
                                    <?php } 
                                }
                                
                                for ($i = $startPage; $i <= $endPage; $i++) { ?>
                                    <a href="?<?= $queryString . $queryConnector ?>page=<?= $i ?>" 
                                      class="page-link <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php }
                                
                                if ($endPage < $pagination['total_pages']) { 
                                    if ($endPage < $pagination['total_pages'] - 1) { ?>
                                        <span class="page-ellipsis">...</span>
                                    <?php } ?>
                                    <a href="?<?= $queryString . $queryConnector ?>page=<?= $pagination['total_pages'] ?>" class="page-link">
                                        <?= $pagination['total_pages'] ?>
                                    </a>
                                <?php } ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']) { ?>
                                    <a href="?<?= $queryString . $queryConnector ?>page=<?= $pagination['current_page'] + 1 ?>" class="page-link" aria-label="Next page">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php } ?>
                            </div>
                            
                            <p class="pagination-info">
                                Showing <?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?>-<?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']) ?> 
                                of <?= $pagination['total_items'] ?> products
                            </p>
                        </nav>
                    <?php } ?>
                <?php } ?>
            </section>
        </div>
    </div>
</main>

<!-- Quick View Modal -->
<div class="modal" id="quickViewModal" aria-hidden="true">
    <div class="modal-backdrop" tabindex="-1" data-close></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Product Quick View</h2>
            <button type="button" class="modal-close" data-close aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="quickViewContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter accordion functionality
    document.querySelectorAll('.filter-title').forEach(title => {
        title.addEventListener('click', function() {
            // The details element handles open/close state automatically
            const details = this.parentNode;
            const isOpen = details.hasAttribute('open');
            
            // Toggle icon if needed
            const icon = this.querySelector('.toggle-icon');
            if (icon) {
                icon.className = isOpen 
                    ? 'fas fa-chevron-down toggle-icon' 
                    : 'fas fa-chevron-up toggle-icon';
            }
        });
    });
    
    // Mobile filter toggle
    const filterToggle = document.querySelector('.filters-toggle');
    const filtersElement = document.querySelector('.filters');
    
    if (filterToggle) {
        filterToggle.addEventListener('click', function() {
            filtersElement.classList.toggle('active');
            this.setAttribute('aria-expanded', filtersElement.classList.contains('active'));
        });
    }
    
    // Active filters display
    function updateActiveFilters() {
        const activeFilters = document.getElementById('active-filters');
        activeFilters.innerHTML = '';
        let hasFilters = false;
        
        // Search filter tag
        const searchValue = document.getElementById('search').value.trim();
        if (searchValue) {
            const tag = createFilterTag('search', searchValue, 'fa-search');
            activeFilters.appendChild(tag);
            hasFilters = true;
        }
        
        // Category filter tags
        document.querySelectorAll('input[name="category[]"]:checked').forEach(input => {
            const tag = createFilterTag('category', input.value, 'fa-tag', input.value);
            activeFilters.appendChild(tag);
            hasFilters = true;
        });
        
        // Price range filter tag
        const minPrice = parseInt(document.getElementById('min-price').value);
        const maxPrice = parseInt(document.getElementById('max-price').value);
        if (minPrice > 0 || maxPrice < 1000) {
            const tag = createFilterTag('price', `$${minPrice} - $${maxPrice}`, 'fa-dollar-sign');
            activeFilters.appendChild(tag);
            hasFilters = true;
        }
        
        // Show/hide the active filters container
        activeFilters.style.display = hasFilters ? 'flex' : 'none';
    }
    
    function createFilterTag(type, text, icon, value = null) {
        const tag = document.createElement('div');
        tag.className = 'filter-tag';
        
        tag.innerHTML = `
            <i class="fas ${icon}"></i> 
            <span>${text}</span>
            <button type="button" class="remove-tag" data-filter="${type}" ${value ? `data-value="${value}"` : ''} aria-label="Remove filter">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        return tag;
    }
    
    // Initialize active filters
    updateActiveFilters();
    
    // Filter form controls change events
    document.querySelectorAll('#filters-form input, #filters-form select').forEach(input => {
        input.addEventListener('change', updateActiveFilters);
    });
    
    // Remove filter tag handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-tag')) {
            const button = e.target.closest('.remove-tag');
            const filterType = button.getAttribute('data-filter');
            
            if (filterType === 'search') {
                document.getElementById('search').value = '';
            } else if (filterType === 'category') {
                const value = button.getAttribute('data-value');
                const checkbox = document.querySelector(`input[name="category[]"][value="${value}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                }
                document.getElementById('filters-form').submit();
            } else if (filterType === 'price') {
                document.getElementById('min-price').value = 0;
                document.getElementById('max-price').value = 1000;
            }
            
            updateActiveFilters();
        }
    });
    
    // Price range inputs linked
    const minPriceInput = document.getElementById('min-price');
    const maxPriceInput = document.getElementById('max-price');
    
    if (minPriceInput && maxPriceInput) {
        minPriceInput.addEventListener('change', function() {
            if (parseInt(this.value) > parseInt(maxPriceInput.value)) {
                maxPriceInput.value = this.value;
            }
            updateActiveFilters();
        });
        
        maxPriceInput.addEventListener('change', function() {
            if (parseInt(this.value) < parseInt(minPriceInput.value)) {
                minPriceInput.value = this.value;
            }
            updateActiveFilters();
        });
    }
    
    // Clear all filters
    document.getElementById('clear-filters')?.addEventListener('click', function() {
        document.getElementById('search').value = '';
        
        document.querySelectorAll('input[name="category[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        minPriceInput.value = 0;
        maxPriceInput.value = 500;
        
        document.querySelectorAll('input[name="sort"]')[0].checked = true;
        
        updateActiveFilters();
    });
    
    // View switcher
    const viewButtons = document.querySelectorAll('.view-btn');
    const productsGrid = document.getElementById('products-grid');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.getAttribute('data-view');
            productsGrid.className = view === 'list' ? 'product-list' : 'product-grid';
        });
    });
    
    // Quick View functionality
    const quickViewModal = document.getElementById('quickViewModal');
    const quickViewContent = document.getElementById('quickViewContent');
    
    document.querySelectorAll('[data-action="quickview"]').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            
            // Show loading state
            quickViewContent.innerHTML = '<div class="loader"><i class="fas fa-spinner fa-spin"></i></div>';
            quickViewModal.setAttribute('aria-hidden', 'false');
            
            // Fetch product data
            fetch(`/api/products/${productId}`)
                .then(response => response.json())
                .then(data => {
                    quickViewContent.innerHTML = `
                        <div class="quick-view">
                            <div class="quick-view-image">
                                <img src="${data.image_url}" alt="${data.name}">
                            </div>
                            
                            <div class="quick-view-details">
                                <h3>${data.name}</h3>
                                <div class="quick-view-price">$${data.price.toFixed(2)}</div>
                                <p>${data.description}</p>
                                
                                <form action="/cart/add" method="post" class="quick-view-form">
                                    <input type="hidden" name="product_id" value="${data.id}">
                                    <div class="quantity-control">
                                        <label for="quantity">Quantity</label>
                                        <div class="quantity-wrapper">
                                            <button type="button" class="quantity-btn" data-action="decrease">−</button>
                                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="${data.stock}">
                                            <button type="button" class="quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                    
                                    <div class="quick-view-actions">
                                        <button type="submit" class="btn primary">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                        <a href="/shop/product/${data.id}" class="btn outline">View Details</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    `;
                    
                    // Initialize quantity controls
                    initQuantityControls();
                })
                .catch(error => {
                    console.error('Error fetching product:', error);
                    quickViewContent.innerHTML = '<div class="error">Failed to load product details</div>';
                });
        });
    });
    
    // Modal close handlers
    document.querySelectorAll('[data-close]').forEach(element => {
        element.addEventListener('click', function() {
            quickViewModal.setAttribute('aria-hidden', 'true');
        });
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && quickViewModal.getAttribute('aria-hidden') === 'false') {
            quickViewModal.setAttribute('aria-hidden', 'true');
        }
    });
    
    // Quantity control functionality
    function initQuantityControls() {
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input');
                const currentValue = parseInt(input.value);
                const action = this.getAttribute('data-action');
                
                if (action === 'decrease' && currentValue > parseInt(input.min)) {
                    input.value = currentValue - 1;
                } else if (action === 'increase' && currentValue < parseInt(input.max)) {
                    input.value = currentValue + 1;
                }
            });
        });
    }
});
</script>

</body>
</html>
