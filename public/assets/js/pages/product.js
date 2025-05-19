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
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input');
            const currentValue = parseInt(input.value);
            const action = this.getAttribute('data-action');
            const max = parseInt(input.getAttribute('max'));
            
            if (action === 'increase' && currentValue < max) {
                input.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    });
}

/**
 * Initialize review form display toggle
 */
function initReviewForm() {
    const writeReviewBtn = document.getElementById('writeReviewBtn');
    const reviewFormContainer = document.getElementById('reviewFormContainer');
    const cancelReviewBtn = document.getElementById('cancelReviewBtn');
    
    if (writeReviewBtn && reviewFormContainer) {
        writeReviewBtn.addEventListener('click', function() {
            reviewFormContainer.style.display = 'block';
            writeReviewBtn.style.display = 'none';
            
            // Scroll to the form
            reviewFormContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    }
    
    if (cancelReviewBtn) {
        cancelReviewBtn.addEventListener('click', function() {
            reviewFormContainer.style.display = 'none';
            if (writeReviewBtn) writeReviewBtn.style.display = 'block';
        });
    }
}

/**
 * Initialize star rating functionality
 */
function initStarRating() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.querySelector('.rating-text');
    
    if (!stars.length || !ratingInput) return;
    
    // Rating descriptions for different star levels
    const ratingDescriptions = {
        0: 'Select a rating',
        0.5: 'Terrible',
        1: 'Very Bad',
        1.5: 'Bad',
        2: 'Disappointing',
        2.5: 'Below Average',
        3: 'Average',
        3.5: 'Above Average',
        4: 'Good',
        4.5: 'Very Good',
        5: 'Excellent'
    };
    
    // Handle star hover
    stars.forEach(star => {
        // Show filled stars on hover
        star.addEventListener('mouseenter', function() {
            const rating = parseFloat(this.getAttribute('data-rating'));
            highlightStars(rating);
            
            if (ratingText) {
                ratingText.textContent = ratingDescriptions[rating] || `${rating} Stars`;
            }
        });
        
        // Handle half-star hovers
        star.addEventListener('mousemove', function(e) {
            const rating = parseFloat(this.getAttribute('data-rating'));
            const rect = this.getBoundingClientRect();
            const isHalfStar = (e.clientX - rect.left) < rect.width / 2;
            
            if (isHalfStar && rating > 0.5) {
                // If on the left half of the star
                highlightStars(rating - 0.5);
                if (ratingText) {
                    ratingText.textContent = ratingDescriptions[rating - 0.5] || `${rating - 0.5} Stars`;
                }
            }
        });
        
        // Handle click on star
        star.addEventListener('click', function(e) {
            const rating = parseFloat(this.getAttribute('data-rating'));
            const rect = this.getBoundingClientRect();
            const isHalfStar = (e.clientX - rect.left) < rect.width / 2;
            
            let selectedRating = rating;
            if (isHalfStar && rating > 0.5) {
                selectedRating = rating - 0.5;
            }
            
            // Update the hidden form input with the selected rating
            ratingInput.value = selectedRating;
            
            // Update the UI to show the selected stars
            updateSelectedStars(selectedRating);
            
            if (ratingText) {
                ratingText.textContent = ratingDescriptions[selectedRating] || `${selectedRating} Stars`;
            }
        });
    });
    
    // Reset stars when mouse leaves the rating area
    const starRating = document.querySelector('.star-rating');
    if (starRating) {
        starRating.addEventListener('mouseleave', function() {
            const currentRating = parseFloat(ratingInput.value);
            if (currentRating > 0) {
                // If a rating is already selected, show it
                updateSelectedStars(currentRating);
                if (ratingText) {
                    ratingText.textContent = ratingDescriptions[currentRating] || `${currentRating} Stars`;
                }
            } else {
                // Otherwise reset to empty stars
                resetStars();
                if (ratingText) {
                    ratingText.textContent = ratingDescriptions[0];
                }
            }
        });
    }
    
    // Update review form validation
    const productReviewForm = document.getElementById('productReviewForm');
    if (productReviewForm) {
        productReviewForm.addEventListener('submit', function(e) {
            if (ratingInput.value === '0') {
                e.preventDefault();
                alert('Please select a rating before submitting.');
                return false;
            }
        });
    }
    
    /**
     * Highlights stars up to a specific rating
     */
    function highlightStars(rating) {
        stars.forEach(s => {
            const starRating = parseInt(s.getAttribute('data-rating'));
            const icon = s.querySelector('i');
            
            if (starRating <= Math.floor(rating)) {
                // Full star
                icon.className = 'fas fa-star';
                s.classList.add('hover');
            } else if (starRating === Math.ceil(rating) && rating % 1 !== 0) {
                // Half star
                icon.className = 'fas fa-star-half-alt';
                s.classList.add('hover');
            } else {
                // Empty star
                icon.className = 'far fa-star';
                s.classList.remove('hover');
            }
        });
    }
    
    /**
     * Updates the star display to show the selected rating
     */
    function updateSelectedStars(rating) {
        stars.forEach(s => {
            const starRating = parseInt(s.getAttribute('data-rating'));
            const icon = s.querySelector('i');
            
            if (starRating <= Math.floor(rating)) {
                // Full star
                icon.className = 'fas fa-star';
            } else if (starRating === Math.ceil(rating) && rating % 1 !== 0) {
                // Half star
                icon.className = 'fas fa-star-half-alt';
            } else {
                // Empty star
                icon.className = 'far fa-star';
            }
        });
    }
    
    /**
     * Reset all stars to empty state
     */
    function resetStars() {
        stars.forEach(s => {
            const icon = s.querySelector('i');
            icon.className = 'far fa-star';
            s.classList.remove('hover');
        });
    }
}