/**
 * Product Page JavaScript
 * Handles product functionality like quantity control and add-to-cart actions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const quantityInput = document.getElementById('quantity');
    const addToCartForm = document.querySelector('form[action="/cart/add"]');
    
    // Handle form submission
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get product ID and quantity
            const productId = this.querySelector('input[name="product_id"]').value;
            const quantity = quantityInput.value;
            
            // Validate quantity
            if (quantity < 1) {
                alert('Please select at least 1 item');
                return;
            }
            
            // Submit form for processing
            console.log(`Adding product ${productId} with quantity ${quantity} to cart`);
            this.submit();
        });
    }
    
    // Quantity controls (optional enhancement)
    if (quantityInput) {
        // Ensure quantity stays within valid range
        quantityInput.addEventListener('change', function() {
            const max = parseInt(this.getAttribute('max'));
            const val = parseInt(this.value);
            
            if (val > max) {
                this.value = max;
            }
            if (val < 1) {
                this.value = 1;
            }
        });
    }
    
    console.log('Product page JavaScript initialized');
});