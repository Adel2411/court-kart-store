/**
 * Orders Page Functionality
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
});

/**
 * Initialize order details page functionality
 */
function initOrderDetails() {
  // Animate the progress bar and timeline steps on page load
  const progressBar = document.querySelector('.timeline-track .progress-bar');
  const timelineSteps = document.querySelectorAll('.timeline-steps .step');
  
  if (progressBar) {
    // Start with 0 width and animate to final width
    progressBar.style.width = '0%';
    
    setTimeout(() => {
      progressBar.style.width = progressBar.getAttribute('style').replace('width: 0%', 'width: ');
    }, 300);
  }
  
  if (timelineSteps.length) {
    // Add animation delay to each completed step
    timelineSteps.forEach((step, index) => {
      if (step.classList.contains('completed')) {
        step.style.opacity = '0';
        step.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
          step.style.opacity = '1';
          step.style.transform = 'translateY(0)';
        }, 300 + (index * 200));
      }
    });
  }
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
        window.location.href = `/orders/${orderId}`;
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
      window.print();
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
      if (!confirm("Are you sure you want to cancel this order? This action cannot be undone.")) {
        event.preventDefault();
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
  
  orderRows.forEach(row => {
    const orderStatus = row.querySelector('.status-badge').textContent.trim().toLowerCase();
    
    if (status === 'all' || orderStatus === status) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
  
  // Update count
  updateFilteredCount();
}

/**
 * Update the count of filtered orders
 */
function updateFilteredCount() {
  const visibleOrders = document.querySelectorAll('.orders-table tbody tr:not([style*="display: none"])');
  const countElement = document.querySelector('.orders-count');
  
  if (countElement) {
    countElement.textContent = visibleOrders.length;
  }
}
