/**
 * Home Page JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize add to cart functionality
    initAddToCart();
    
    // Initialize quick actions like wishlist
    initQuickActions();
    
    // Initialize animations and interactions
    initAnimations();
    
    // Initialize newsletter form
    initNewsletterForm();
    
    // Initialize counter animations
    initCounterAnimations();
});

/**
 * Initialize add to cart functionality - convert to traditional form submission
 */
function initAddToCart() {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        // Add a hidden input for the return URL to redirect back after form submission
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'return_url';
        hiddenInput.value = window.location.href;
        form.appendChild(hiddenInput);
        
        // Update action to enable direct form submission
        form.action = '/cart/add';
        form.method = 'POST';
        
        // Add visual feedback without preventing form submission
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            
            form.addEventListener('submit', function() {
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                submitBtn.disabled = true;
                
                // The form will submit naturally and redirect
                // No need to prevent default or use fetch
                
                // Store button state in localStorage to restore after redirect
                localStorage.setItem('lastAddedProductId', form.querySelector('[name="product_id"]').value);
                
                return true; // Allow form to submit normally
            });
        }
    });
    
    // Check if we just returned from adding an item
    const lastAddedProductId = localStorage.getItem('lastAddedProductId');
    if (lastAddedProductId) {
        // Show success animation on the button for that product
        const form = document.querySelector(`.add-to-cart-form input[value="${lastAddedProductId}"]`)?.closest('form');
        if (form) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-check"></i> Added';
                btn.classList.add('btn-success');
                
                // Reset button after delay
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
                    btn.disabled = false;
                    btn.classList.remove('btn-success');
                }, 2000);
            }
        }
        
        // Clear the stored ID
        localStorage.removeItem('lastAddedProductId');
    }
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
 * Initialize animations for landing page elements
 */
function initAnimations() {
    // Animate hero section on page load
    const heroContent = document.querySelector('.hero-content');
    const heroImage = document.querySelector('.hero-image');
    
    if (heroContent) {
        setTimeout(() => {
            heroContent.style.opacity = '1';
            heroContent.style.transform = 'translateY(0)';
        }, 300);
    }
    
    if (heroImage) {
        setTimeout(() => {
            heroImage.style.opacity = '1';
            heroImage.style.transform = 'translateY(0)';
        }, 600);
    }
    
    // Initialize intersection observer for scroll animations
    if ('IntersectionObserver' in window) {
        const animateOnScroll = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    animateOnScroll.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        // Elements to animate on scroll
        const animatedElements = document.querySelectorAll('.section-title, .product-card, .category-feature-card, .testimonial-card, .category-card');
        animatedElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            animateOnScroll.observe(el);
        });
    }
}

/**
 * Initialize newsletter form submission
 */
function initNewsletterForm() {
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button');
            const originalText = submitBtn.textContent;
            
            // Basic validation
            if (!emailInput.value) {
                showTooltip(emailInput, 'Please enter your email');
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual API call)
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Subscribed!';
                submitBtn.classList.add('btn-success');
                
                // Reset form after delay
                setTimeout(() => {
                    emailInput.value = '';
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-success');
                }, 2000);
            }, 1000);
        });
    }
}

/**
 * Initialize counter animations for stat numbers
 */
function initCounterAnimations() {
    // Find all elements with counter-animation class
    const counters = document.querySelectorAll('.counter-animation');
    
    // Process each counter element
    counters.forEach(counter => {
        // Get the target value from data-counter attribute
        const counterValue = counter.getAttribute('data-counter');
        
        // Make sure we have a valid number to count to
        if (!counterValue) {
            console.error('Missing data-counter attribute on counter element');
            return;
        }
        
        const target = parseInt(counterValue, 10);
        
        // Verify we got a valid number
        if (isNaN(target)) {
            console.error('Invalid counter value:', counterValue);
            return;
        }
        
        // Set initial value to 0
        counter.textContent = '0';
        
        // Configure animation parameters
        const duration = 2000; // 2 seconds animation
        const frameDuration = 30; // Update every 30ms
        const totalFrames = duration / frameDuration;
        const increment = Math.ceil(target / totalFrames);
        
        // Start animation with a small delay
        setTimeout(() => {
            let current = 0;
            
            // Update function that increments the counter
            const updateCounter = () => {
                current += increment;
                
                // If we've reached or exceeded the target, set to exact target value
                if (current >= target) {
                    counter.textContent = target;
                } else {
                    // Otherwise update to current value and continue animation
                    counter.textContent = current;
                    // Schedule next update
                    requestAnimationFrame(() => {
                        setTimeout(updateCounter, frameDuration);
                    });
                }
            };
            
            // Start the counter animation
            updateCounter();
        }, 500); // 500ms delay before starting animation
    });
}

/**
 * Show a temporary tooltip on an element
 */
function showTooltip(element, text) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = text;
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10 + window.scrollY}px`;
    tooltip.style.left = `${rect.left + rect.width/2 - tooltip.offsetWidth/2}px`;
    tooltip.style.opacity = '1';
    
    setTimeout(() => {
        tooltip.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(tooltip);
        }, 300);
    }, 2000);
}

/**
 * Update the cart count in the header - no fetch call
 */
function updateCartCount() {
    // The cart count will be set by PHP directly in the HTML
    // Just add animation if needed
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        // Simple animation
        element.style.transform = 'scale(1.5)';
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 300);
    });
}
