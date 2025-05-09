/**
 * Orders Page Functionality
 * Enhanced for Court Kart E-commerce
 */

document.addEventListener("DOMContentLoaded", function () {
  // Initialize order details page functionality
  initOrderDetails();
  
  // Order row highlighting
  initOrderRowHighlighting();
  
  // Print order functionality
  initPrintOrderButton();
  
  // Cancel order confirmation
  initCancelOrderConfirmation();
  
  // Status filter
  initStatusFilter();
  
  // Handle back to top button
  initBackToTop();
});

/**
 * Initialize order details page functionality
 * Handles animations and interactive elements
 */
function initOrderDetails() {
  // Animate progress bar with delayed start for better visual effect
  const progressBar = document.querySelector('.timeline-track .progress-bar');
  const timelineSteps = document.querySelectorAll('.timeline-steps .step');
  
  if (progressBar) {
    // Store the target width set in the inline style
    const targetWidth = progressBar.style.width;
    // Start at 0 width
    progressBar.style.width = '0%';
    
    // Animate to target width after a short delay
    setTimeout(() => {
      progressBar.style.width = targetWidth;
    }, 300);
  }
  
  if (timelineSteps.length) {
    // Staggered animation for steps
    timelineSteps.forEach((step, index) => {
      if (step.classList.contains('completed')) {
        // Apply animation to each step with staggered delay
        setTimeout(() => {
          step.classList.add('animated');
        }, 300 + (index * 150));
      }
    });
  }
  
  // Add scroll behavior to order sections
  const orderSections = document.querySelectorAll('.order-section, .order-summary-card');
  orderSections.forEach(section => {
    animateOnScroll(section);
  });
}

/**
 * Animate elements when they come into viewport
 */
function animateOnScroll(element) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animated');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  
  observer.observe(element);
}

/**
 * Initialize order row highlighting
 */
function initOrderRowHighlighting() {
  const orderRows = document.querySelectorAll(".orders-table tbody tr");
  
  orderRows.forEach((row) => {
    row.addEventListener("click", function (event) {
      // Only trigger if we didn't click on a button or link
      if (!event.target.closest("a") && !event.target.closest("button")) {
        const orderId = this.querySelector("td:first-child").textContent.replace(/\D/g, "");
        
        // Add click animation
        this.classList.add('row-clicked');
        
        // Navigate to order details page after animation
        setTimeout(() => {
          window.location.href = `/orders/${orderId}`;
        }, 150);
      }
    });
  });
}

/**
 * Initialize print order functionality
 */
function initPrintOrderButton() {
  const printOrderBtn = document.querySelector(".print-order-btn");
  if (printOrderBtn) {
    printOrderBtn.addEventListener("click", function (event) {
      event.preventDefault();
      
      // Show print feedback
      this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing...';
      
      setTimeout(() => {
        window.print();
        this.innerHTML = '<i class="fas fa-print"></i> Print';
      }, 300);
    });
  }
}

/**
 * Initialize cancel order confirmation
 */
function initCancelOrderConfirmation() {
  const cancelOrderForm = document.querySelector(".cancel-order-form");
  if (cancelOrderForm) {
    cancelOrderForm.addEventListener("submit", function (event) {
      event.preventDefault();
      
      // Show a better styled confirmation dialog
      if (confirm("Are you sure you want to cancel this order? This action cannot be undone.")) {
        const submitBtn = this.querySelector('button[type="submit"]');
        
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        }
        
        // Submit the form after showing loading state
        setTimeout(() => {
          this.submit();
        }, 300);
      }
    });
  }
}

/**
 * Initialize status filter
 */
function initStatusFilter() {
  const statusFilters = document.querySelectorAll(".status-filter");
  if (statusFilters.length > 0) {
    statusFilters.forEach((filter) => {
      filter.addEventListener("click", function (event) {
        const status = this.getAttribute('data-status');
        
        // Update active class
        statusFilters.forEach(f => f.classList.remove('active'));
        this.classList.add('active');
        
        // Filter orders
        filterOrders(status);
      });
    });
  }
}

/**
 * Filter orders by status
 */
function filterOrders(status) {
  const orderRows = document.querySelectorAll('.orders-table tbody tr');
  let visibleCount = 0;
  
  orderRows.forEach(row => {
    const statusElement = row.querySelector('.status-badge');
    if (!statusElement) return;
    
    const orderStatus = statusElement.textContent.trim().toLowerCase();
    
    if (status === 'all' || orderStatus === status) {
      row.style.display = '';
      visibleCount++;
      
      // Add fade-in animation
      row.style.opacity = '0';
      setTimeout(() => {
        row.style.transition = 'opacity 0.3s ease';
        row.style.opacity = '1';
      }, 50);
    } else {
      row.style.opacity = '0';
      setTimeout(() => {
        row.style.display = 'none';
      }, 300);
    }
  });
  
  // Update count
  updateFilteredCount(visibleCount);
  
  // Show empty state if no results
  toggleEmptyState(visibleCount);
}

/**
 * Update the count of filtered orders
 */
function updateFilteredCount(count) {
  const countElement = document.querySelector('.orders-count');
  
  if (countElement) {
    countElement.textContent = count;
  }
}

/**
 * Toggle empty state message
 */
function toggleEmptyState(count) {
  const tableContainer = document.querySelector('.table-responsive');
  const emptyState = document.querySelector('.empty-filtered-state');
  
  if (count === 0) {
    if (!emptyState) {
      const emptyStateDiv = document.createElement('div');
      emptyStateDiv.className = 'empty-filtered-state';
      emptyStateDiv.innerHTML = `
        <div class="empty-state">
          <i class="fas fa-filter"></i>
          <p>No orders match the selected filter.</p>
          <button class="btn btn-outline reset-filter">Clear Filter</button>
        </div>
      `;
      tableContainer.parentNode.insertBefore(emptyStateDiv, tableContainer.nextSibling);
      
      // Add event listener to reset filter button
      emptyStateDiv.querySelector('.reset-filter').addEventListener('click', () => {
        document.querySelector('.status-filter[data-status="all"]').click();
      });
    }
    tableContainer.style.display = 'none';
  } else {
    if (emptyState) {
      emptyState.remove();
    }
    tableContainer.style.display = '';
  }
}

/**
 * Initialize back to top button
 */
function initBackToTop() {
  const backToTopBtn = document.createElement('button');
  backToTopBtn.className = 'back-to-top';
  backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
  document.body.appendChild(backToTopBtn);
  
  window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
      backToTopBtn.classList.add('show');
    } else {
      backToTopBtn.classList.remove('show');
    }
  });
  
  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}

// Add these styles to the page for the back to top button
document.addEventListener('DOMContentLoaded', () => {
  const style = document.createElement('style');
  style.textContent = `
    .back-to-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: var(--primary);
      color: white;
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .back-to-top.show {
      opacity: 1;
      visibility: visible;
    }
    
    .back-to-top:hover {
      background-color: var(--primary-dark);
      transform: translateY(-3px);
    }
    
    .animated {
      animation: fadeInUp 0.6s ease forwards;
    }
    
    .row-clicked {
      animation: clickPulse 0.3s ease;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes clickPulse {
      0% {
        transform: scale(1);
      }
      50% {
        transform: scale(0.98);
      }
      100% {
        transform: scale(1);
      }
    }
  `;
  document.head.appendChild(style);
});
