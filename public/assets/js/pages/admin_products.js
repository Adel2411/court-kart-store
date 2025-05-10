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
  
  // Existing code for product form modal...
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
