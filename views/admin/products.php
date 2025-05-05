<h1>Product Management</h1>

<div class="actions">
    <a href="#" class="btn btn-primary" id="addProductBtn">Add New Product</a>
</div>

<!-- Add/Edit Product Form Modal -->
<div id="productFormModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modalTitle">Add New Product</h2>
        
        <form id="productForm" action="/admin/products/save" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="productId">
            
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" id="productName" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="productDescription">Description</label>
                <textarea id="productDescription" name="description" rows="4" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="productPrice">Price ($)</label>
                    <input type="number" id="productPrice" name="price" min="0.01" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="productStock">Stock</label>
                    <input type="number" id="productStock" name="stock" min="0" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="productCategory">Category</label>
                <select id="productCategory" name="category" required>
                    <option value="">Select a category</option>
                    <option value="Footwear">Footwear</option>
                    <option value="Apparel">Apparel</option>
                    <option value="Equipment">Equipment</option>
                    <option value="Accessories">Accessories</option>
                    <option value="Merchandise">Merchandise</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="productImage">Image URL</label>
                <input type="text" id="productImage" name="image_url" required>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline" id="cancelBtn">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<table>
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
                <td colspan="7">No products found.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($products as $product) { ?>
                <tr data-product='<?= json_encode($product) ?>'>
                    <td><?= $product['id'] ?></td>
                    <td><img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product" class="thumbnail"></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= $product['stock'] ?></td>
                    <td>
                        <button class="btn btn-sm edit-btn" data-id="<?= $product['id'] ?>">Edit</button>
                        <form action="/admin/products/delete" method="post" class="inline-form delete-form">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('productFormModal');
    const addBtn = document.getElementById('addProductBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeBtn = modal.querySelector('.close');
    const modalTitle = document.getElementById('modalTitle');
    const productForm = document.getElementById('productForm');
    const productIdInput = document.getElementById('productId');
    
    // Open modal for new product
    addBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Add New Product';
        productForm.reset();
        productIdInput.value = '';
        modal.style.display = 'block';
    });
    
    // Close modal
    function closeModal() {
        modal.style.display = 'none';
    }
    
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
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
            
            modal.style.display = 'block';
        });
    });
});
</script>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! Product data loaded from database.</p>
</div>
