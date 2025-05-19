document.addEventListener('DOMContentLoaded', function() {
    // Initialize remove from wishlist functionality
    initRemoveFromWishlist();
    
    // Initialize add to cart functionality
    initAddToCart();
    
    // Initialize alerts auto-dismiss
    initAlertDismiss();
});

/**
 * Initialize remove from wishlist with AJAX
 */
function initRemoveFromWishlist() {
    const removeButtons = document.querySelectorAll('.remove-from-wishlist-form');
    
    removeButtons.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = this.querySelector('input[name="product_id"]').value;
            const wishlistItem = this.closest('.wishlist-item');
            const button = this.querySelector('button');
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removing...';
            button.disabled = true;
            
            // Prepare form data
            const formData = new FormData();
            formData.append('product_id', productId);
            
            // Send AJAX request
            fetch('/wishlist/remove', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the item with animation
                    wishlistItem.style.opacity = '0';
                    setTimeout(() => {
                        wishlistItem.remove();
                        
                        // Update count
                        const countElement = document.querySelector('.wishlist-count');
                        if (countElement) {
                            countElement.textContent = `${data.count} item${data.count !== 1 ? 's' : ''}`;
                        }
                        
                        // Show empty state if no items left
                        if (data.count === 0) {
                            const wishlistItems = document.querySelector('.wishlist-items');
                            const actions = document.querySelector('.wishlist-actions');
                            
                            if (wishlistItems && actions) {
                                // Create empty state
                                const emptyState = document.createElement('div');
                                emptyState.className = 'empty-wishlist';
                                emptyState.innerHTML = `
                                    <div class="empty-icon">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <h2>Your wishlist is empty</h2>
                                    <p>Browse our products and add items you love to your wishlist</p>
                                    <a href="/shop" class="btn btn-primary">Browse Products</a>
                                `;
                                
                                // Replace wishlist items with empty state
                                wishlistItems.replaceWith(emptyState);
                                actions.remove();
                            }
                        }
                        
                        // Show success message
                        showToast('success', 'Item removed from wishlist');
                    }, 300);
                } else {
                    // Reset button and show error
                    button.innerHTML = '<i class="far fa-trash-alt"></i> Remove';
                    button.disabled = false;
                    showToast('error', data.message || 'Failed to remove item');
                }
            })
            .catch(error => {
                console.error('Error removing from wishlist:', error);
                button.innerHTML = '<i class="far fa-trash-alt"></i> Remove';
                button.disabled = false;
                showToast('error', 'An error occurred');
            });
        });
    });
}

/**
 * Initialize add to cart functionality
 */
function initAddToCart() {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            submitBtn.disabled = true;
            
            // Let the form submit normally, but show visual feedback
            setTimeout(() => {
                // Enable button after a delay (form will have submitted by then)
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
            
            // Don't prevent default - let the form submit normally
        });
    });
}

/**
 * Initialize auto-dismissing alerts
 */
function initAlertDismiss() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
}

/**
 * Show toast notification
 */
function showToast(type, message) {
    // Create toast element if it doesn't exist
    let toast = document.getElementById('toast-notification');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast-notification';
        toast.className = 'toast-notification';
        document.body.appendChild(toast);
    }
    
    // Set content based on type
    let iconClass = 'info-circle';
    if (type === 'success') {
        iconClass = 'check-circle';
    } else if (type === 'error') {
        iconClass = 'exclamation-circle';
    }
    
    toast.innerHTML = `
        <div class="toast-icon ${type}">
            <i class="fas fa-${iconClass}"></i>
        </div>
        <div class="toast-content">
            <p>${message}</p>
        </div>
        <button type="button" class="toast-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Show the toast
    toast.classList.add('active');
    
    // Set up close button
    const closeBtn = toast.querySelector('.toast-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            toast.classList.remove('active');
        });
    }
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        toast.classList.remove('active');
    }, 3000);
}
