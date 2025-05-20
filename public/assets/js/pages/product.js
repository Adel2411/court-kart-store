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
    
    // Tabs functionality
    initTabs();
    
    // Quantity controls
    initQuantityControls();
    
    // Review form display toggle
    initReviewForm();
    
    // Star rating system
    initStarRating();
    
    // Initialize wishlist button
    initWishlistButton();
    
    console.log('Product page JavaScript initialized');
});

/**
 * Initialize product tabs
 */
function initTabs() {
    const tabItems = document.querySelectorAll('.tab-item');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all tabs
            tabItems.forEach(tab => tab.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Hide all tab contents
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Show the selected tab content
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
}

/**
 * Initialize quantity control buttons
 */
function initQuantityControls() {
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input');
            const currentValue = parseInt(input.value);
            const action = this.getAttribute('data-action');
            const max = parseInt(input.getAttribute('max'));
            
            if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            } else if (action === 'increase' && currentValue < max) {
                input.value = currentValue + 1;
            }
        });
    });
}

/**
 * Initialize review form functionality
 */
function initReviewForm() {
    const writeReviewBtn = document.getElementById('writeReviewBtn');
    const cancelReviewBtn = document.getElementById('cancelReviewBtn');
    const reviewFormContainer = document.getElementById('reviewFormContainer');
    const starRating = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.querySelector('.rating-text');
    
    if (writeReviewBtn && reviewFormContainer) {
        writeReviewBtn.addEventListener('click', function() {
            reviewFormContainer.style.display = 'block';
        });
    }
    if (cancelReviewBtn && reviewFormContainer) {
        cancelReviewBtn.addEventListener('click', function() {
            reviewFormContainer.style.display = 'none';
        });
    }
    if (starRating && ratingInput) {
        starRating.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                // Update stars
                starRating.forEach(s => {
                    const r = s.getAttribute('data-rating');
                    if (r <= rating) {
                        s.innerHTML = '<i class="fas fa-star"></i>';
                    } else {
                        s.innerHTML = '<i class="far fa-star"></i>';
                    }
                });
                // Update rating text
                const ratingTexts = {
                    1: 'Poor',
                    2: 'Fair',
                    3: 'Good',
                    4: 'Very Good',
                    5: 'Excellent'
                };
                if (ratingText) {
                    ratingText.textContent = ratingTexts[rating] || 'Select a rating';
                }
            });
            star.addEventListener('mouseover', function() {
                const rating = this.getAttribute('data-rating');
                starRating.forEach(s => {
                    const r = s.getAttribute('data-rating');
                    if (r <= rating) {
                        s.querySelector('i').classList.add('fas');
                        s.querySelector('i').classList.remove('far');
                    }
                });
            });
            star.addEventListener('mouseout', function() {
                const currentRating = ratingInput.value;
                starRating.forEach(s => {
                    const r = s.getAttribute('data-rating');
                    if (r <= currentRating) {
                        s.querySelector('i').classList.add('fas');
                        s.querySelector('i').classList.remove('far');
                    } else {
                        s.querySelector('i').classList.add('far');
                        s.querySelector('i').classList.remove('fas');
                    }
                });
            });
        });
    }
}

/**
 * Initialize wishlist button functionality
 */
function initWishlistButton() {
    const wishlistBtn = document.querySelector('.wishlist-btn');
    if (!wishlistBtn) return;
    
    const productId = document.querySelector('input[name="product_id"]').value;
    
    // Check if product is in wishlist
    WishlistManager.checkProduct(productId, function(inWishlist) {
        updateWishlistButtonState(wishlistBtn, inWishlist);
    });
    
    // Add click event listener
    wishlistBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        WishlistManager.toggleProduct(productId, function(isAdded) {
            updateWishlistButtonState(wishlistBtn, isAdded);
        });
    });
}

// Update the wishlist button state
function updateWishlistButtonState(button, isInWishlist) {
    const icon = button.querySelector('i');
    
    if (isInWishlist) {
        button.classList.add('in-wishlist');
        icon.classList.remove('far');
        icon.classList.add('fas');
        button.innerHTML = `<i class="fas fa-heart"></i> Remove from Wishlist`;
    } else {
        button.classList.remove('in-wishlist');
        icon.classList.remove('fas');
        icon.classList.add('far');
        button.innerHTML = `<i class="far fa-heart"></i> Add to Wishlist`;
    }
}

/**
 * Check if product is in the user's wishlist
 */
function checkWishlistStatus(productId) {
    if (!isUserLoggedIn()) {
        return Promise.resolve(false);
    }
    
    return fetch(`/wishlist/check?product_id=${productId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        return data.in_wishlist;
    })
    .catch(error => {
        console.error('Error checking wishlist status:', error);
        return false;
    });
}

/**
 * Check if user is logged in
 */
function isUserLoggedIn() {
    // This is a simple client-side check that will be complemented by server-side validation
    return document.body.classList.contains('user-logged-in') || 
           document.querySelector('meta[name="user-logged-in"]')?.getAttribute('content') === 'true';
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