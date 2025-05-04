/**
 * Court Kart Store - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const nav = document.querySelector('nav');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            nav.classList.toggle('active');
            
            // Change button appearance
            const spans = mobileMenuBtn.querySelectorAll('span');
            if (nav.classList.contains('active')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(function() {
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
    }
    
    // Quantity input controls
    const quantityInputs = document.querySelectorAll('input[type="number"][name="quantity"]');
    quantityInputs.forEach(input => {
        // Create increment/decrement buttons
        const wrapper = document.createElement('div');
        wrapper.className = 'quantity-wrapper';
        
        const decrementBtn = document.createElement('button');
        decrementBtn.type = 'button';
        decrementBtn.className = 'quantity-btn';
        decrementBtn.textContent = '-';
        
        const incrementBtn = document.createElement('button');
        incrementBtn.type = 'button';
        incrementBtn.className = 'quantity-btn';
        incrementBtn.textContent = '+';
        
        // Replace input with our custom control
        const parent = input.parentNode;
        parent.insertBefore(wrapper, input);
        wrapper.appendChild(decrementBtn);
        wrapper.appendChild(input);
        wrapper.appendChild(incrementBtn);
        
        // Add event listeners
        decrementBtn.addEventListener('click', () => {
            const min = parseInt(input.getAttribute('min') || 1);
            const currentValue = parseInt(input.value);
            if (currentValue > min) {
                input.value = currentValue - 1;
                input.dispatchEvent(new Event('change'));
            }
        });
        
        incrementBtn.addEventListener('click', () => {
            const max = parseInt(input.getAttribute('max') || 99);
            const currentValue = parseInt(input.value);
            if (currentValue < max) {
                input.value = currentValue + 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Form validations
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Add error message if doesn't exist
                    let errorMsg = field.parentNode.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message';
                        errorMsg.textContent = 'This field is required';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('is-invalid');
                    const errorMsg = field.parentNode.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
    
    // Update cart counts via AJAX if available
    updateCartCount();
    
    // Initialize tooltips
    initializeTooltips();
});

// Function to update cart count
function updateCartCount() {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        // Simulate AJAX call to get cart count
        // In a real application, you would make an actual AJAX call to your server
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                if (data && data.count !== undefined) {
                    cartCountElement.textContent = data.count;
                }
            })
            .catch(() => {
                // Fallback: leave as is or set to a default
                console.log('Could not update cart count');
            });
    }
}

// Function to initialize tooltips
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        const tooltipText = element.getAttribute('data-tooltip');
        
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = tooltipText;
            document.body.appendChild(tooltip);
            
            const rect = element.getBoundingClientRect();
            const tooltipHeight = tooltip.offsetHeight;
            const tooltipWidth = tooltip.offsetWidth;
            
            tooltip.style.top = (rect.top - tooltipHeight - 10) + 'px';
            tooltip.style.left = (rect.left + (rect.width / 2) - (tooltipWidth / 2)) + 'px';
            tooltip.style.opacity = '1';
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
}
