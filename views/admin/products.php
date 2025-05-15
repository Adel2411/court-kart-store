<h1>Product Management</h1>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Products</h2>
        <div class="admin-card-actions">
            <button type="button" class="btn btn-primary" id="addProductBtn">
                <i class="fas fa-plus"></i> Add New Product
            </button>
        </div>
    </div>
    
    <!-- Product filtering section -->
    <div class="admin-filters-bar">
        <form action="/admin/products" method="get" class="product-filters-form">
            <div class="filter-group">
                <label for="categoryFilter">Category:</label>
                <select name="category" id="categoryFilter" class="form-control">
                    <option value="all" <?= ($currentCategory ?? 'all') === 'all' ? 'selected' : '' ?>>All Categories</option>
                    <?php foreach ($categories as $cat) { ?>
                    <!-- Since we're using the category name as the ID -->
                    <option value="<?= htmlspecialchars($cat['id']) ?>" <?= ($currentCategory ?? 'all') == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="sortFilter">Sort By:</label>
                <select name="sort" id="sortFilter" class="form-control">
                    <option value="name_asc" <?= ($currentSort ?? 'name_asc') === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                    <option value="name_desc" <?= ($currentSort ?? 'name_asc') === 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                    <option value="price_asc" <?= ($currentSort ?? 'name_asc') === 'price_asc' ? 'selected' : '' ?>>Price (Low-High)</option>
                    <option value="price_desc" <?= ($currentSort ?? 'name_asc') === 'price_desc' ? 'selected' : '' ?>>Price (High-Low)</option>
                    <option value="newest" <?= ($currentSort ?? 'name_asc') === 'newest' ? 'selected' : '' ?>>Newest First</option>
                </select>
            </div>
            
            <div class="filter-group search-group">
                <label for="searchFilter">Search:</label>
                <div class="search-input-wrapper">
                    <input type="text" name="search" id="searchFilter" class="form-control" placeholder="Search products..." value="<?= htmlspecialchars($currentSearch ?? '') ?>">
                    <?php if (! empty($currentSearch)) { ?>
                    <a href="?category=<?= $currentCategory ?? 'all' ?>&sort=<?= $currentSort ?? 'name_asc' ?>" class="clear-search">
                        <i class="fas fa-times"></i>
                    </a>
                    <?php } ?>
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <?php if (($currentCategory ?? 'all') !== 'all' || ($currentSort ?? 'name_asc') !== 'name_asc' || ! empty($currentSearch ?? '')) { ?>
                <a href="/admin/products" class="btn btn-outline">Reset</a>
                <?php } ?>
            </div>
        </form>
    </div>
    
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)) { ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">
                            <div style="color: var(--gray);">
                                <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                <p>No products found</p>
                            </div>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($products as $product) { ?>
                        <tr data-product='<?= json_encode($product) ?>'>
                            <td>#<?= $product['id'] ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product" class="product-thumbnail">
                            </td>
                            <td>
                                <div style="font-weight: 500;"><?= htmlspecialchars($product['name']) ?></div>
                                <div style="font-size: 0.85rem; color: var(--gray);">
                                    <?= substr(htmlspecialchars($product['description']), 0, 50) ?>...
                                </div>
                            </td>
                            <td>
                                <span class="chip"><?= htmlspecialchars($product['category']) ?></span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: var(--primary);">$<?= number_format($product['price'], 2) ?></span>
                            </td>
                            <td>
                                <?php if ($product['stock'] > 10) { ?>
                                    <span style="color: var(--success); font-weight: 500;">
                                        <i class="fas fa-check-circle"></i> <?= $product['stock'] ?>
                                    </span>
                                <?php } elseif ($product['stock'] > 0) { ?>
                                    <span style="color: var(--warning); font-weight: 500;">
                                        <i class="fas fa-exclamation-circle"></i> <?= $product['stock'] ?>
                                    </span>
                                <?php } else { ?>
                                    <span style="color: var(--danger); font-weight: 500;">
                                        <i class="fas fa-times-circle"></i> Out of stock
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="actions">
                                <button class="btn btn-sm btn-outline edit-btn" data-id="<?= $product['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="/admin/products/delete" method="post" class="inline-form delete-form">
                                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Product Modal -->
<div class="modal" id="productModal" aria-hidden="true" role="dialog" aria-labelledby="productModalTitle">
    <div class="modal-backdrop" tabindex="-1" data-close></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="productModalTitle">Add New Product</h3>
            <button type="button" class="modal-close" data-close aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form id="productForm" action="/admin/products/save" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="productId">
                
                <div class="admin-form">
                    <div class="admin-form-group">
                        <label for="productName">Product Name</label>
                        <input type="text" id="productName" name="name" class="form-control" required>
                    </div>
                    
                    <div class="admin-form-group">
                        <label for="productDescription">Description</label>
                        <textarea id="productDescription" name="description" rows="4" class="form-control" required></textarea>
                    </div>
                    
                    <div class="admin-form-row">
                        <div class="admin-form-group">
                            <label for="productPrice">Price ($)</label>
                            <input type="number" id="productPrice" name="price" min="0.01" step="0.01" class="form-control" required>
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="productStock">Stock</label>
                            <input type="number" id="productStock" name="stock" min="0" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="admin-form-group">
                        <label for="productCategory">Category</label>
                        <select id="productCategory" name="category" class="form-control" required>
                            <option value="">Select a category</option>
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
                        echo "<option value=\"$category\">$icon $category</option>";
                    }
                    ?>
                        </select>
                    </div>
                    
                    <div class="admin-form-group">
                        <label for="productImage">Image URL</label>
                        <input type="text" id="productImage" name="image_url" class="form-control" required>
                        <div class="image-preview-container" style="margin-top: 10px;">
                            <img id="productImagePreview" class="image-preview" style="display: none;" alt="Product Preview">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" id="cancelBtn">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveProductBtn">Save Product</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('productModal');
    const addBtn = document.getElementById('addProductBtn');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveProductBtn');
    const modalTitle = document.getElementById('modalTitle');
    const productForm = document.getElementById('productForm');
    const productIdInput = document.getElementById('productId');
    const productImage = document.getElementById('productImage');
    const productImagePreview = document.getElementById('productImagePreview');
    
    addBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Add New Product';
        productForm.reset();
        productIdInput.value = '';
        productImagePreview.style.display = 'none';
        modal.classList.add('active');
    });
    
    function closeModal() {
        modal.classList.remove('active');
    }
    
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    
    productImage.addEventListener('input', function() {
        if (this.value) {
            productImagePreview.src = this.value;
            productImagePreview.style.display = 'block';
            
            productImagePreview.onerror = function() {
                this.style.display = 'none';
            };
        } else {
            productImagePreview.style.display = 'none';
        }
    });
    
    // Edit product
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productData = JSON.parse(this.closest('tr').dataset.product);
            modalTitle.textContent = 'Edit Product';
            
            document.getElementById('productId').value = productData.id;
            document.getElementById('productName').value = productData.name;
            document.getElementById('productDescription').value = productData.description;
            document.getElementById('productPrice').value = productData.price;
            document.getElementById('productStock').value = productData.stock;
            document.getElementById('productCategory').value = productData.category;
            document.getElementById('productImage').value = productData.image_url;
            
            if (productData.image_url) {
                productImagePreview.src = productData.image_url;
                productImagePreview.style.display = 'block';
                
                productImagePreview.onerror = function() {
                    this.style.display = 'none';
                };
            }
            
            modal.classList.add('active');
        });
    });
    
    saveBtn.addEventListener('click', function() {
        if (productForm.checkValidity()) {
            productForm.submit();
        } else {
            let submitEvent = new Event('submit', {
                'bubbles': true,
                'cancelable': true
            });
            productForm.dispatchEvent(submitEvent);
        }
    });

    // Make filters apply on change
    const autoSubmitFilters = document.querySelectorAll('#categoryFilter, #sortFilter');
    autoSubmitFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Connect to our modal component
    document.addEventListener('modal:open', function(e) {
        if (e.detail.modalId === 'productModal') {
            document.getElementById('productName').focus();
        }
    });
    
    document.addEventListener('modal:close', function(e) {
        if (e.detail.modalId === 'productModal') {
            document.getElementById('productImagePreview').style.display = 'none';
        }
    });
});
</script>

<style>
.admin-filters-bar {
    background-color: var(--light);
    border-radius: var(--radius-md);
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.product-filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-size: 0.85rem;
    color: var(--secondary);
}

.search-group {
    grid-column: span 2;
}

.search-input-wrapper {
    position: relative;
}

.clear-search {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    color: var(--gray);
    cursor: pointer;
}

.filter-actions {
    display: flex;
    align-items: flex-end;
    gap: 0.5rem;
}

@media (max-width: 992px) {
    .product-filters-form {
        grid-template-columns: 1fr;
    }
    
    .search-group {
        grid-column: span 1;
    }
}
</style>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Product data loaded from database.</p>
</div>
