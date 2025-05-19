/**
 * Orders Page Functionality
 * Enhanced for Court Kart E-commerce
 */

document.addEventListener("DOMContentLoaded", function () {
  console.log("Orders page script loaded"); // Debugging
  
  // Initialize order details page functionality
  initOrderDetails();
  
  // Order row highlighting
  initOrderRowHighlighting();
  
  // Print order functionality
  initPrintOrderButton();
  
  // Cancel order modal functionality
  initCancelOrderModal();
  
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
  console.log("Initializing order details"); // Debugging
  
  // Animate progress bar with delayed start for better visual effect
  const progressBar = document.querySelector('.timeline-track .progress-bar');
  const timelineSteps = document.querySelectorAll('.timeline-steps .step');
  
  if (progressBar) {
    console.log("Progress bar found, animating"); // Debugging
    
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
    console.log("Timeline steps found: " + timelineSteps.length); // Debugging
    
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
  const orderRows = document.querySelectorAll('.orders-table tbody tr');
  
  orderRows.forEach(row => {
    row.addEventListener('click', function() {
      // Remove clicked class from all rows
      orderRows.forEach(r => r.classList.remove('row-clicked'));
      
      // Add clicked class to this row
      this.classList.add('row-clicked');
      
      // Get the order details link and navigate to it
      const detailsLink = this.querySelector('a[href^="/orders/"]');
      if (detailsLink) {
        window.location.href = detailsLink.getAttribute('href');
      }
    });
  });
}

/**
 * Initialize print order functionality
 */
function initPrintOrderButton() {
  const printBtn = document.querySelector('.print-order-btn');
  
  if (printBtn) {
    console.log("Print button found, adding event listener"); // Debugging
    printBtn.addEventListener('click', function() {
      window.print();
    });
  }
}

/**
 * Initialize cancel order modal functionality
 * Updated to use the central modal component
 */
function initCancelOrderModal() {
  const cancelBtn = document.querySelector('.cancel-order-btn');
  const cancelOrderForm = document.getElementById('cancelOrderForm');
  const confirmCancelBtn = document.getElementById('confirmCancelBtn');
  
  if (!cancelBtn || !cancelOrderForm) return;
  
  // Open modal when cancel button is clicked
  cancelBtn.addEventListener('click', function() {
    const orderId = this.getAttribute('data-order-id');
    cancelOrderForm.action = `/orders/${orderId}/cancel`;
    
    // Open modal using our modal component
    if (window.CourtKartModals && window.CourtKartModals.cancelOrderModal) {
      window.CourtKartModals.cancelOrderModal.open();
    } else {
      // Fallback if modal component isn't available yet
      document.getElementById('cancelOrderModal').classList.add('active');
    }
  });
  
  // Handle form submission
  if (confirmCancelBtn) {
    confirmCancelBtn.addEventListener('click', function() {
      const reasonInput = document.getElementById('cancelReason');
      
      if (reasonInput.value.trim() === '') {
        // Show validation error
        reasonInput.classList.add('is-invalid');
        
        // Add error message if not already present
        let errorMessage = document.querySelector('.cancel-reason-error');
        if (!errorMessage) {
          errorMessage = document.createElement('div');
          errorMessage.className = 'error-message cancel-reason-error';
          errorMessage.textContent = 'Please provide a reason for cancellation';
          reasonInput.parentNode.appendChild(errorMessage);
        }
        
        return;
      }
      
      // Remove validation error if exists
      reasonInput.classList.remove('is-invalid');
      const errorMessage = document.querySelector('.cancel-reason-error');
      if (errorMessage) errorMessage.remove();
      
      // Submit the form
      cancelOrderForm.submit();
    });
  }
  
  // Remove validation error when typing
  const reasonInput = document.getElementById('cancelReason');
  if (reasonInput) {
    reasonInput.addEventListener('input', function() {
      this.classList.remove('is-invalid');
      const errorMessage = document.querySelector('.cancel-reason-error');
      if (errorMessage) errorMessage.remove();
    });
  }
}

/**
 * Initialize cancel order confirmation - Deprecated in favor of modal
 * Keeping for backward compatibility
 */
function initCancelOrderConfirmation() {
  const cancelForms = document.querySelectorAll('.cancel-order-form');
  
  if (cancelForms.length) {
    console.log("Cancel forms found: " + cancelForms.length); // Debugging
  }
  
  cancelForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Show the modal instead if it exists
      const modal = document.getElementById('cancelOrderModal');
      if (modal) {
        const orderId = this.getAttribute('action').split('/').pop();
        document.getElementById('cancelOrderForm').action = `/orders/${orderId}/cancel`;
        modal.classList.add('active');
      } else {
        // Fallback to simple confirmation
        if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
          this.submit();
        }
      }
    });
  });
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
  const countElement = document.querySelector('.filtered-count');
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
  
  if (count === 0 && tableContainer) {
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
    if (tableContainer) {
      tableContainer.style.display = '';
    }
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
    
    .print-styles {
      display: none;
    }
    
    @media print {
      body * {
        visibility: hidden;
      }
      
      .order-details-container,
      .order-details-container * {
        visibility: visible;
      }
      
      .order-details-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
      }
      
      .order-actions, 
      .help-card, 
      .continue-shopping,
      .back-to-top {
        display: none !important;
      }
      
      .order-content-grid {
        grid-template-columns: 1fr !important;
      }
    }
  `;
  document.head.appendChild(style);
  
  console.log("Orders script fully initialized"); // Debugging
});

/**
 * Filter functionality for order status
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter pills
    const filterPills = document.querySelectorAll('.filter-pill');
    const orderRows = document.querySelectorAll('.orders-table tbody tr');
    const filteredCountElement = document.getElementById('filtered-count');
    
    // Add click event to each filter pill
    filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
            // Remove active class from all pills
            filterPills.forEach(p => p.classList.remove('active'));
            
            // Add active class to clicked pill
            this.classList.add('active');
            
            // Get the status to filter by
            const status = this.getAttribute('data-status');
            
            // Count how many orders are shown
            let visibleCount = 0;
            
            // Filter table rows
            orderRows.forEach(row => {
                const orderStatus = row.querySelector('.status-badge').className
                    .replace('status-badge', '')
                    .replace('status-', '')
                    .trim();
                
                if (status === 'all' || orderStatus === status) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update the count
            filteredCountElement.textContent = status === 'all' ? 'All' : 
                status.charAt(0).toUpperCase() + status.slice(1);
        });
    });
});
