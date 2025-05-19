/**
 * Wishlist functionality - handles adding and removing products from wishlist
 */
const WishlistManager = {
    /**
     * Toggle product in wishlist (add if not in wishlist, remove if already in wishlist)
     * @param {number} productId - Product ID to toggle
     * @param {Function} callback - Callback function to run after toggling
     */
    toggleProduct: function(productId, callback) {
        // Send AJAX request to toggle product in wishlist
        fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `product_id=${productId}`
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    // User not logged in, redirect to login
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                    throw new Error('Please log in to add items to your wishlist');
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update UI based on response
            if (data.success) {
                // Update wishlist count in header
                this.updateWishlistCount(data.count);
                
                // Call callback if provided
                if (typeof callback === 'function') {
                    callback(data.is_added);
                }
                
                // Show toast notification
                this.showNotification(data.message, 'success');
            } else {
                this.showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error toggling wishlist item:', error);
            this.showNotification('Failed to update wishlist', 'error');
        });
    },
    
    /**
     * Check if product is in wishlist
     * @param {number} productId - Product ID to check
     * @param {Function} callback - Callback function with result
     */
    checkProduct: function(productId, callback) {
        fetch(`/wishlist/check?product_id=${productId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (typeof callback === 'function') {
                callback(data.in_wishlist);
            }
        })
        .catch(error => {
            console.error('Error checking wishlist status:', error);
            if (typeof callback === 'function') {
                callback(false);
            }
        });
    },
    
    /**
     * Update wishlist count in header
     * @param {number} count - New wishlist count
     */
    updateWishlistCount: function(count) {
        const wishlistCountEl = document.querySelector('.wishlist-count');
        if (wishlistCountEl) {
            wishlistCountEl.textContent = count;
            
            // Toggle visibility of count badge
            if (count > 0) {
                wishlistCountEl.classList.remove('hidden');
            } else {
                wishlistCountEl.classList.add('hidden');
            }
        }
    },
    
    /**
     * Show notification toast
     * @param {string} message - Message to display
     * @param {string} type - Notification type (success, error)
     */
    showNotification: function(message, type = 'success') {
        // Create toast notification element if it doesn't exist
        let toast = document.getElementById('wishlistNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'wishlistNotification';
            toast.className = 'toast-notification';
            toast.innerHTML = `
                <div class="toast-icon ${type}">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-circle'}"></i>
                </div>
                <div class="toast-content">
                    <p>${message}</p>
                </div>
                <button type="button" class="toast-close">
                    <i class="fas fa-times"></i>
                </button>
            `;
            document.body.appendChild(toast);
            
            // Add close button event listener
            toast.querySelector('.toast-close').addEventListener('click', () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            });
        } else {
            // Update existing toast
            toast.querySelector('.toast-icon').className = `toast-icon ${type}`;
            toast.querySelector('.toast-icon i').className = `fas fa-${type === 'success' ? 'check' : 'exclamation-circle'}`;
            toast.querySelector('.toast-content p').textContent = message;
        }
        
        // Show toast
        toast.classList.add('show');
        
        // Auto-hide toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
};

// Initialize wishlist functionality on all pages
document.addEventListener('DOMContentLoaded', function() {
    // Get all wishlist buttons on the page
    const wishlistButtons = document.querySelectorAll('[data-action="wishlist"]');
    
    wishlistButtons.forEach(button => {
        const productId = button.dataset.id;
        
        // Check initial wishlist status
        WishlistManager.checkProduct(productId, function(inWishlist) {
            if (inWishlist) {
                button.classList.add('in-wishlist');
                button.querySelector('i').classList.remove('far');
                button.querySelector('i').classList.add('fas');
            }
        });
        
        // Add click event for toggling wishlist
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;
            
            WishlistManager.toggleProduct(productId, function(isAdded) {
                if (isAdded) {
                    button.classList.add('in-wishlist');
                    button.querySelector('i').classList.remove('far');
                    button.querySelector('i').classList.add('fas');
                } else {
                    button.classList.remove('in-wishlist');
                    button.querySelector('i').classList.remove('fas');
                    button.querySelector('i').classList.add('far');
                }
            });
        });
    });
});
