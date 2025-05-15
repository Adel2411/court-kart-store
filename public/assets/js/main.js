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
  
  // Initialize mobile menu toggle
  initMobileMenu();
  
  // Initialize responsive tables
  initResponsiveTables();
  
  // Initialize alert dismissal
  initAlertDismissal();
  
  // Handle admin sidebar responsive behavior if on admin page
  if (document.querySelector('.admin-wrapper')) {
      initAdminResponsive();
  }
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
  // Mobile menu toggle - FIXED to match correct class names
  const menuToggle = document.querySelector('.mobile-menu-btn');
  const navMenu = document.querySelector('nav');
  
  if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', function() {
      navMenu.classList.toggle('active');
      menuToggle.classList.toggle('active');
      document.body.classList.toggle('menu-open');
    });
    
    // Close menu when clicking on a link
    const navLinks = navMenu.querySelectorAll('a');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        navMenu.classList.remove('active');
        menuToggle.classList.remove('active');
        document.body.classList.remove('menu-open');
      });
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!navMenu.contains(event.target) && !menuToggle.contains(event.target) && navMenu.classList.contains('active')) {
        navMenu.classList.remove('active');
        menuToggle.classList.remove('active');
        document.body.classList.remove('menu-open');
      }
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
 * Initialize mobile menu toggle
 */
function initMobileMenu() {
    const menuBtn = document.getElementById('mobileMenuBtn');
    const nav = document.querySelector('header nav');
    
    if (menuBtn && nav) {
        menuBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            nav.classList.toggle('active');
            
            // Add or remove no-scroll class from body
            document.body.classList.toggle('menu-open');
        });
    }
}

/**
 * Initialize responsive tables
 */
function initResponsiveTables() {
    const tables = document.querySelectorAll('.admin-table');
    
    tables.forEach(table => {
        // Add horizontal scroll indicator if table is wider than container
        const wrapper = table.closest('.admin-table-wrapper');
        if (wrapper && table.offsetWidth > wrapper.offsetWidth) {
            wrapper.classList.add('scrollable');
        }
    });
}

/**
 * Initialize alert dismissal
 */
function initAlertDismissal() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade-out');
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
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

// Function to update cart count - removing fetch API
function updateCartCount() {
    const cartCountElements = document.querySelectorAll(".cart-count");
    
    if (cartCountElements.length === 0) return;
    
    // Instead of fetch, just add animation to existing cart count
    // The PHP will set the correct count when rendering the page
    cartCountElements.forEach(element => {
        // Add a simple pulse animation
        element.style.transform = 'scale(1.5)';
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 300);
    });
}

/**
 * Handle admin sidebar responsive behavior
 */
function initAdminResponsive() {
    const wrapper = document.getElementById('adminWrapper');
    const toggle = document.getElementById('sidebarToggle');
    const backdrop = document.getElementById('sidebarBackdrop');
    
    // Fix table cell width on mobile
    const fixTableCellWidth = () => {
        const tables = document.querySelectorAll('.admin-table');
        tables.forEach(table => {
            if (window.innerWidth < 768) {
                // Force minimum width for action columns
                const actionCells = table.querySelectorAll('.actions, .actions-cell');
                actionCells.forEach(cell => {
                    cell.style.minWidth = '100px';
                });
            }
        });
    };
    
    // Call once on load
    fixTableCellWidth();
    
    // And on resize
    window.addEventListener('resize', fixTableCellWidth);
    
    // Handle table horizontal scrolling indicators
    const tableWrappers = document.querySelectorAll('.admin-table-wrapper');
    tableWrappers.forEach(wrapper => {
        wrapper.addEventListener('scroll', function() {
            const maxScroll = this.scrollWidth - this.clientWidth;
            
            if (this.scrollLeft === 0) {
                this.classList.add('scroll-start');
                this.classList.remove('scroll-middle', 'scroll-end');
            } else if (this.scrollLeft >= maxScroll - 5) {
                this.classList.add('scroll-end');
                this.classList.remove('scroll-start', 'scroll-middle');
            } else {
                this.classList.add('scroll-middle');
                this.classList.remove('scroll-start', 'scroll-end');
            }
        });
        
        // Trigger the scroll event once to set the initial state
        wrapper.dispatchEvent(new Event('scroll'));
    });
}
