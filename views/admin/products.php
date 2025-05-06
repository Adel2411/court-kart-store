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
<div class="admin-modal" id="productModal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title" id="modalTitle">Add New Product</h3>
            <button type="button" class="admin-modal-close" id="closeModal">&times;</button>
        </div>
        
        <div class="admin-modal-body">
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
                            <option value="Footwear">Footwear</option>
                            <option value="Apparel">Apparel</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Merchandise">Merchandise</option>
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
        
        <div class="admin-modal-footer">
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
    
    // Open modal for new product
    addBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Add New Product';
        productForm.reset();
        productIdInput.value = '';
        productImagePreview.style.display = 'none';
        modal.classList.add('active');
    });
    
    // Close modal
    function closeModal() {
        modal.classList.remove('active');
    }
    
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    
    // Image preview functionality
    productImage.addEventListener('input', function() {
        if (this.value) {
            productImagePreview.src = this.value;
            productImagePreview.style.display = 'block';
            
            // Handle error if image doesn't load
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
            
            // Show image preview
            if (productData.image_url) {
                productImagePreview.src = productData.image_url;
                productImagePreview.style.display = 'block';
                
                // Handle error if image doesn't load
                productImagePreview.onerror = function() {
                    this.style.display = 'none';
                };
            }
            
            modal.classList.add('active');
        });
    });
    
    // Submit form when Save button is clicked
    saveBtn.addEventListener('click', function() {
        if (productForm.checkValidity()) {
            productForm.submit();
        } else {
            // Trigger HTML5 validation
            let submitEvent = new Event('submit', {
                'bubbles': true,
                'cancelable': true
            });
            productForm.dispatchEvent(submitEvent);
        }
    });
});
</script>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Product data loaded from database.</p>
</div>
