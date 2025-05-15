<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Court Kart</title>
    <link rel="icon" type="image/x-icon" href="public/assets/images/court-kart-logo-dark.ico">
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
                    <!-- Add close button for mobile -->
                    <button type="button" class="filters-close" aria-label="Close filters">
                        <i class="fas fa-times"></i>
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
                                    'Gear' => '<i class="fas fa-basketball-ball"></i>',
                                    'Equipment' => '<i class="fas fa-dumbbell"></i>',
                                    'Accessories' => '<i class="fas fa-glasses"></i>',
                                    'Merchandise' => '<i class="fas fa-store"></i>',
                                ];

                                    foreach ($categories as $category => $icon) {
                                        $isChecked = isset($_GET['category']) && in_array($category, (array) $_GET['category']);
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
                                <?php } ?>
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
                                            'popularity' => ['label' => 'Popularity', 'icon' => 'fa-fire'],
                                        ];

                                    $currentSort = $_GET['sort'] ?? 'newest';

                                    foreach ($sortOptions as $value => $option) {
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
                                <?php } ?>
                            </div>
                        </div>
                    </details>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary" >
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
                                        <input type="hidden" name="return_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
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
                        $queryConnector = ! empty($queryString) ? '&' : '';
                        ?>
                            
                            <div class="pagination-links">
                                <?php if ($pagination['current_page'] > 1) { ?>
                                    <a href="?<?= $queryString.$queryConnector ?>page=<?= $pagination['current_page'] - 1 ?>" class="page-link" aria-label="Previous page">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                <?php } ?>
                                
                                <?php
                            $range = 2;
                        $startPage = max(1, $pagination['current_page'] - $range);
                        $endPage = min($pagination['total_pages'], $pagination['current_page'] + $range);

                        if ($startPage > 1) { ?>
                                    <a href="?<?= $queryString.$queryConnector ?>page=1" class="page-link">1</a>
                                    <?php if ($startPage > 2) { ?>
                                        <span class="page-ellipsis">...</span>
                                    <?php }
                                    }

                        for ($i = $startPage; $i <= $endPage; $i++) { ?>
                                    <a href="?<?= $queryString.$queryConnector ?>page=<?= $i ?>" 
                                      class="page-link <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php }

                        if ($endPage < $pagination['total_pages']) {
                            if ($endPage < $pagination['total_pages'] - 1) { ?>
                                        <span class="page-ellipsis">...</span>
                                    <?php } ?>
                                    <a href="?<?= $queryString.$queryConnector ?>page=<?= $pagination['total_pages'] ?>" class="page-link">
                                        <?= $pagination['total_pages'] ?>
                                    </a>
                                <?php } ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']) { ?>
                                    <a href="?<?= $queryString.$queryConnector ?>page=<?= $pagination['current_page'] + 1 ?>" class="page-link" aria-label="Next page">
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

<!-- Add filter toggle for mobile -->
<button type="button" class="mobile-filters-toggle" aria-label="Show filters">
    <i class="fas fa-filter"></i>
    <span>Filters</span>
</button>

<!-- Add backdrop for mobile filter overlay -->
<div class="filters-backdrop"></div>

<!-- Quick View Modal -->
<div class="modal" id="quickViewModal" aria-hidden="true" role="dialog" aria-labelledby="quickViewTitle">
    <div class="modal-backdrop" tabindex="-1" data-close></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="quickViewTitle">Product Quick View</h2>
            <button type="button" class="modal-close" data-close aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="quickViewContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>

<!-- Cart notification toast -->
<div class="toast-notification" id="cartNotification">
    <div class="toast-icon success">
        <i class="fas fa-check"></i>
    </div>
    <div class="toast-content">
        <p>Product added to your cart!</p>
    </div>
    <button type="button" class="toast-close">
        <i class="fas fa-times"></i>
    </button>
</div>

<script src="/assets/js/pages/shop.js"></script>
</body>
</html>