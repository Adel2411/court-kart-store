/**
 * Main JavaScript file for Court Kart
 * Loads common functionality across all pages
 */

// Import components
document.addEventListener('DOMContentLoaded', function() {
  // Load modal functionality
  loadScript('/assets/js/components/modal.js');
  
  // Initialize global components
  initGlobalComponents();
});

/**
 * Load a script dynamically
 * @param {string} src - Script source path
 * @param {Function} callback - Optional callback after script loads
 */
function loadScript(src, callback) {
  const script = document.createElement('script');
  script.src = src;
  script.async = true;
  
  if (callback && typeof callback === 'function') {
    script.onload = callback;
  }
  
  document.body.appendChild(script);
}

/**
 * Initialize components that should be available on all pages
 */
function initGlobalComponents() {
  // Mobile menu toggle
  const menuToggle = document.querySelector('.mobile-menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  
  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', function() {
      mobileMenu.classList.toggle('active');
      document.body.classList.toggle('menu-open');
    });
  }
  
  // Initialize tooltips
  initTooltips();
  
  // Handle form validation
  initFormValidation();
  
  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert");
  if (alerts.length > 0) {
    setTimeout(function () {
      alerts.forEach((alert) => {
        alert.style.opacity = "0";
        setTimeout(() => {
          alert.style.display = "none";
        }, 500);
      });
    }, 5000);
  }

  // Quantity input controls
  const quantityInputs = document.querySelectorAll(
    'input[type="number"][name="quantity"]',
  );
  quantityInputs.forEach((input) => {
    // Create increment/decrement buttons
    const wrapper = document.createElement("div");
    wrapper.className = "quantity-wrapper";

    const decrementBtn = document.createElement("button");
    decrementBtn.type = "button";
    decrementBtn.className = "quantity-btn";
    decrementBtn.textContent = "-";

    const incrementBtn = document.createElement("button");
    incrementBtn.type = "button";
    incrementBtn.className = "quantity-btn";
    incrementBtn.textContent = "+";

    // Replace input with our custom control
    const parent = input.parentNode;
    parent.insertBefore(wrapper, input);
    wrapper.appendChild(decrementBtn);
    wrapper.appendChild(input);
    wrapper.appendChild(incrementBtn);

    // Add event listeners
    decrementBtn.addEventListener("click", () => {
      const min = parseInt(input.getAttribute("min") || 1);
      const currentValue = parseInt(input.value);
      if (currentValue > min) {
        input.value = currentValue - 1;
        input.dispatchEvent(new Event("change"));
      }
    });

    incrementBtn.addEventListener("click", () => {
      const max = parseInt(input.getAttribute("max") || 99);
      const currentValue = parseInt(input.value);
      if (currentValue < max) {
        input.value = currentValue + 1;
        input.dispatchEvent(new Event("change"));
      }
    });
  });

  // Update cart counts via AJAX if available
  updateCartCount();
}

/**
 * Initialize tooltips
 */
function initTooltips() {
  const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
  
  tooltipTriggers.forEach(trigger => {
    const tooltipText = trigger.dataset.tooltip;
    
    trigger.addEventListener('mouseenter', function() {
      const tooltip = document.createElement('div');
      tooltip.className = 'tooltip';
      tooltip.textContent = tooltipText;
      document.body.appendChild(tooltip);
      
      const triggerRect = trigger.getBoundingClientRect();
      tooltip.style.top = `${triggerRect.top - tooltip.offsetHeight - 10}px`;
      tooltip.style.left = `${triggerRect.left + (trigger.offsetWidth / 2) - (tooltip.offsetWidth / 2)}px`;
      tooltip.style.opacity = '1';
      
      this._tooltip = tooltip;
    });
    
    trigger.addEventListener('mouseleave', function() {
      if (this._tooltip) {
        this._tooltip.remove();
        this._tooltip = null;
      }
    });
  });
}

/**
 * Initialize form validation
 */
function initFormValidation() {
  document.querySelectorAll('form').forEach(form => {
    const submitButton = form.querySelector('button[type="submit"]');
    
    if (submitButton) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          
          // Find and highlight invalid fields
          form.querySelectorAll('input, select, textarea').forEach(field => {
            if (!field.validity.valid) {
              field.classList.add('is-invalid');
              
              field.addEventListener('input', function() {
                if (this.validity.valid) {
                  this.classList.remove('is-invalid');
                }
              }, { once: true });
            }
          });
        }
      });
    }
  });
}

// Function to update cart count
function updateCartCount() {
  const cartCountElements = document.querySelectorAll(".cart-count");
  
  if (cartCountElements.length === 0) return;
  
  // Make AJAX call to get cart count
  fetch("/cart/count")
    .then((response) => response.json())
    .then((data) => {
      if (data && data.count !== undefined) {
        cartCountElements.forEach(element => {
          // Update the text
          element.textContent = data.count;
          
          // Add a simple pulse animation
          element.style.transform = 'scale(1.5)';
          setTimeout(() => {
            element.style.transform = 'scale(1)';
          }, 300);
        });
      }
    })
    .catch((error) => {
      console.error("Could not update cart count:", error);
    });
}
