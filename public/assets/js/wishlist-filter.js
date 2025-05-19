/**
 * Wishlist Filter functionality
 * Toggles between showing all products and only wishlist products
 */

document.addEventListener('DOMContentLoaded', function() {
    // Look for wishlist filter toggle button
    const wishlistToggle = document.getElementById('wishlistFilterToggle');
    
    if (!wishlistToggle) {
        console.warn('Wishlist filter toggle element not found');
        return;
    }
    
    // Check URL to see if wishlist filter is already active
    const urlParams = new URLSearchParams(window.location.search);
    const isWishlistActive = urlParams.get('wishlist_only') === '1';
    
    // Set initial state based on URL parameter
    if (isWishlistActive) {
        wishlistToggle.classList.add('active');
        if (wishlistToggle.querySelector('i')) {
            wishlistToggle.querySelector('i').classList.remove('far');
            wishlistToggle.querySelector('i').classList.add('fas');
        }
        if (wishlistToggle.querySelector('span')) {
            wishlistToggle.querySelector('span').textContent = 'All Products';
        }
    }
    
    // Toggle wishlist filter when clicked
    wishlistToggle.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Check if user is logged in (for wishlist filter)
        const isLoggedIn = document.querySelector('meta[name="user-logged-in"]')?.getAttribute('content') === 'true';
        
        if (!isWishlistActive && !isLoggedIn) {
            // Redirect to login page if not logged in and trying to activate wishlist filter
            window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname + window.location.search);
            return;
        }
        
        // Create current URL object
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        
        if (isWishlistActive) {
            // Switch to showing all products
            params.delete('wishlist_only');
        } else {
            // Switch to showing only wishlist products
            params.set('wishlist_only', '1');
        }
        
        // Reset to page 1 when toggling filter
        params.delete('page');
        
        // Update URL and reload
        url.search = params.toString();
        window.location.href = url.toString();
    });
    
    // Add keyboard accessibility
    wishlistToggle.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
        }
    });
});
