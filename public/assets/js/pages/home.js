/**
 * Home Page JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize add to cart functionality
    initAddToCart();
    
    // Initialize quick actions like wishlist
    initQuickActions();
});

/**
 * Initialize add to cart functionality
 */
function initAddToCart() {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = this.querySelector('[name="product_id"]').value;
            const quantity = this.querySelector('[name="quantity"]')?.value || 1;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;
            
            // Send AJAX request to add item to cart
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                // Show success state
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Added';
                submitBtn.classList.add('btn-success');
                
                // Update cart count
                updateCartCount();
                
                // Reset button after delay
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-success');
                }, 2000);
            })
            .catch(error => {
                // Show error state
                submitBtn.innerHTML = '<i class="fas fa-times"></i> Failed';
                submitBtn.classList.add('btn-danger');
                
                // Reset button after delay
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-danger');
                }, 2000);
            });
        });
    });
}

/**
 * Initialize quick actions like wishlist
 */
function initQuickActions() {
    const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const icon = this.querySelector('i');
            
            // Toggle icon
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#e74c3c';
                
                // Show tooltip
                showTooltip(this, 'Added to wishlist');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                icon.style.color = '';
                
                // Show tooltip
                showTooltip(this, 'Removed from wishlist');
            }
            
            // You can add AJAX call here to update wishlist on server
        });
    });
}

/**
 * Show a temporary tooltip on an element
 */
function showTooltip(element, text) {
    // Create tooltip element
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = text;
    
    // Position it above the element
    const rect = element.getBoundingClientRect();
    tooltip.style.top = (rect.top - 10 - tooltip.offsetHeight) + 'px';
    tooltip.style.left = (rect.left + rect.width / 2) + 'px';
    
    // Add to document
    document.body.appendChild(tooltip);
    
    // Show tooltip
    setTimeout(() => {
        tooltip.style.opacity = '1';
        tooltip.style.top = (rect.top - 10 - tooltip.offsetHeight) + 'px';
    }, 10);
    
    // Remove after delay
    setTimeout(() => {
        tooltip.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(tooltip);
        }, 300);
    }, 2000);
}

/**
 * Update the cart count in the header
 */
function updateCartCount() {
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.ok ? response.json() : Promise.reject('Failed to get cart count'))
    .then(data => {
        if (data.count !== undefined) {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(el => {
                el.textContent = data.count;
                
                // Animate the count update
                el.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    el.style.transform = 'scale(1)';
                }, 300);
            });
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}
