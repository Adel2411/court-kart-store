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
 * Update the cart count in the header
 */
function updateCartCount() {
  // Call the global updateCartCount function from main.js
  if (window.updateCartCount) {
    window.updateCartCount();
  } else {
    // Fallback implementation
    fetch('/cart/count')
      .then(response => response.json())
      .then(data => {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
          element.textContent = data.count || 0;
          
          // Simple animation
          element.style.transform = 'scale(1.5)';
          setTimeout(() => {
            element.style.transform = 'scale(1)';
          }, 300);
        });
      })
      .catch(error => {
        console.error('Error updating cart count:', error);
      });
  }
}
