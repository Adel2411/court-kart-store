/**
 * Admin Products JS - Handles admin product page functionality
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
  
  // Existing code for product form modal...
});
