/**
 * Admin Products Page Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Fix thumbnail sizes in product table
  const thumbnails = document.querySelectorAll('table .thumbnail');
  thumbnails.forEach(img => {
    // Force size constraints
    img.style.width = '60px';
    img.style.height = '60px';
    img.style.maxWidth = '60px';
    img.style.maxHeight = '60px';
    
    // Handle image loading issues
    img.onerror = function() {
      this.src = '/assets/images/placeholder-product.png';
    };
    
    // Force reload with proper dimensions
    if(img.complete) {
      img.style.width = '60px';
      img.style.height = '60px';
    }
  });
  
  // Product filter functionality
  initProductFilters();
  
  // Initialize product modals
  initProductModal();
});

/**
 * Initialize product filters
 */
function initProductFilters() {
  // Auto-submit on category and sort change
  const autoSubmitFilters = document.querySelectorAll('#categoryFilter, #sortFilter');
  autoSubmitFilters.forEach(filter => {
    filter.addEventListener('change', function() {
      this.closest('form').submit();
    });
  });
  
  // Clear search button functionality
  const clearSearchBtn = document.querySelector('.clear-search');
  if (clearSearchBtn) {
    clearSearchBtn.addEventListener('click', function(e) {
      e.preventDefault();
      const searchInput = document.getElementById('searchFilter');
      if (searchInput) {
        searchInput.value = '';
        this.closest('form').submit();
      }
    });
  }
  
  // Handle filter reset
  const resetBtn = document.querySelector('.filter-actions .btn-outline');
  if (resetBtn) {
    resetBtn.addEventListener('click', function(e) {
      e.preventDefault();
      window.location.href = '/admin/products';
    });
  }
}

/**
 * Initialize product modal functionality
 */
function initProductModal() {
  const addBtn = document.getElementById('addProductBtn');
  const saveBtn = document.getElementById('saveProductBtn');
  const cancelBtn = document.getElementById('cancelBtn');
  const productForm = document.getElementById('productForm');
  const productIdInput = document.getElementById('productId');
  const productImage = document.getElementById('productImage');
  const productImagePreview = document.getElementById('productImagePreview');
  const modalTitle = document.getElementById('productModalTitle');
  
  // Open modal for new product
  if (addBtn) {
    addBtn.addEventListener('click', function() {
      modalTitle.textContent = 'Add New Product';
      productForm.reset();
      productIdInput.value = '';
      if (productImagePreview) productImagePreview.style.display = 'none';
      
      // Open modal using the modal component
      if (window.CourtKartModals && window.CourtKartModals.productModal) {
        window.CourtKartModals.productModal.open();
      } else {
        // Fallback if modal component isn't loaded yet
        document.getElementById('productModal').classList.add('active');
      }
    });
  }
  
  // Image preview functionality
  if (productImage) {
    productImage.addEventListener('input', function() {
      if (this.value && productImagePreview) {
        productImagePreview.src = this.value;
        productImagePreview.style.display = 'block';
        
        // Handle error if image doesn't load
        productImagePreview.onerror = function() {
          this.style.display = 'none';
        };
      } else if (productImagePreview) {
        productImagePreview.style.display = 'none';
      }
    });
  }
  
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
      if (productData.image_url && productImagePreview) {
        productImagePreview.src = productData.image_url;
        productImagePreview.style.display = 'block';
        
        // Handle error if image doesn't load
        productImagePreview.onerror = function() {
          this.style.display = 'none';
        };
      }
      
      // Open modal using the modal component
      if (window.CourtKartModals && window.CourtKartModals.productModal) {
        window.CourtKartModals.productModal.open();
      } else {
        // Fallback if modal component isn't loaded yet
        document.getElementById('productModal').classList.add('active');
      }
    });
  });
  
  // Close modal
  function closeModal() {
    if (window.CourtKartModals && window.CourtKartModals.productModal) {
      window.CourtKartModals.productModal.close();
    } else {
      document.getElementById('productModal').classList.remove('active');
    }
  }
  
  // Add close functionality to cancel button
  if (cancelBtn) {
    cancelBtn.addEventListener('click', closeModal);
  }
  
  // Modal closes
  document.querySelectorAll('#productModal [data-close]').forEach(element => {
    element.addEventListener('click', closeModal);
  });
  
  // Submit form when Save button is clicked
  if (saveBtn && productForm) {
    saveBtn.addEventListener('click', function() {
      if (productForm.checkValidity()) {
        productForm.submit();
      } else {
        // Trigger HTML5 validation
        productForm.reportValidity();
      }
    });
  }
}
