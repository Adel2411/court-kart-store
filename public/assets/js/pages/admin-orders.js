/**
 * Admin Orders Page Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize filter dropdown
  initFilterDropdown();
  
  // Initialize order detail modal
  initOrderDetailModal();
  
  // Initialize update status modal
  initUpdateStatusModal();
});

/**
 * Initialize filter dropdown
 */
function initFilterDropdown() {
  const filterBtn = document.getElementById('filterBtn');
  const filterDropdown = document.getElementById('filterDropdown');
  
  if (filterBtn && filterDropdown) {
    filterBtn.addEventListener('click', function() {
      filterDropdown.style.display = filterDropdown.style.display === 'none' ? 'block' : 'none';
    });
    
    document.addEventListener('click', function(event) {
      if (!filterBtn.contains(event.target) && !filterDropdown.contains(event.target)) {
        filterDropdown.style.display = 'none';
      }
    });
  }
}

/**
 * Initialize order detail modal
 */
function initOrderDetailModal() {
  const viewOrderBtns = document.querySelectorAll('.view-order');
  const printOrderBtn = document.getElementById('printOrderBtn');
  const orderDetailContent = document.getElementById('orderDetailContent');
  
  viewOrderBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const orderId = this.getAttribute('data-id');
      
      // Show loading state
      if (orderDetailContent) {
        orderDetailContent.innerHTML = `
          <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading order details...</p>
          </div>
        `;
      }
      
      // Open the modal using the modal component
      if (window.CourtKartModals && window.CourtKartModals.orderDetailModal) {
        window.CourtKartModals.orderDetailModal.open();
      } else {
        // Fallback if modal component isn't loaded yet
        const orderDetailModal = document.getElementById('orderDetailModal');
        if (orderDetailModal) {
          orderDetailModal.classList.add('active');
        }
      }
      
      // In a real application, you would fetch order details via AJAX
      // For now, we'll just show mock data after a short delay
      setTimeout(function() {
        if (orderDetailContent) {
          orderDetailContent.innerHTML = getOrderDetailMarkup(orderId);
        }
      }, 500);
    });
  });
  
  // Add print functionality
  if (printOrderBtn) {
    printOrderBtn.addEventListener('click', function() {
      const printContents = orderDetailContent.innerHTML;
      const originalContents = document.body.innerHTML;
      
      document.body.innerHTML = `
        <div class="print-only">${printContents}</div>
        <style>
          @media print {
            body { margin: 0; padding: 15px; }
            .print-only { padding: 20px; }
          }
        </style>
      `;
      
      window.print();
      document.body.innerHTML = originalContents;
      
      // Re-initialize modals after restoring content
      if (window.initModals) {
        window.initModals();
      }
    });
  }
}

/**
 * Initialize update status modal
 */
function initUpdateStatusModal() {
  const updateStatusBtns = document.querySelectorAll('.update-status');
  const saveStatusBtn = document.getElementById('saveStatusBtn');
  const cancelStatusBtn = document.getElementById('cancelStatusBtn');
  const statusOrderId = document.getElementById('statusOrderId');
  const orderStatus = document.getElementById('orderStatus');
  const updateStatusForm = document.getElementById('updateStatusForm');
  
  updateStatusBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const orderId = this.getAttribute('data-id');
      const currentStatus = this.getAttribute('data-status');
      
      if (statusOrderId) {
        statusOrderId.value = orderId;
      }
      
      // Set the current status as selected in dropdown
      if (orderStatus && currentStatus) {
        orderStatus.value = currentStatus;
      }
      
      // Open modal using the modal component
      if (window.CourtKartModals && window.CourtKartModals.updateStatusModal) {
        window.CourtKartModals.updateStatusModal.open();
      } else {
        // Fallback if modal component isn't loaded yet
        const updateStatusModal = document.getElementById('updateStatusModal');
        if (updateStatusModal) {
          updateStatusModal.classList.add('active');
        }
      }
    });
  });
  
  // Close modal functionality
  function closeUpdateStatusModal() {
    if (window.CourtKartModals && window.CourtKartModals.updateStatusModal) {
      window.CourtKartModals.updateStatusModal.close();
    } else {
      const updateStatusModal = document.getElementById('updateStatusModal');
      if (updateStatusModal) {
        updateStatusModal.classList.remove('active');
      }
    }
  }
  
  // Add close button handlers
  if (cancelStatusBtn) {
    cancelStatusBtn.addEventListener('click', closeUpdateStatusModal);
  }
  
  document.querySelectorAll('#updateStatusModal [data-close]').forEach(element => {
    element.addEventListener('click', closeUpdateStatusModal);
  });
  
  // Form submission
  if (saveStatusBtn && updateStatusForm) {
    saveStatusBtn.addEventListener('click', function() {
      if (updateStatusForm.checkValidity()) {
        updateStatusForm.submit();
      } else {
        // Trigger HTML5 validation
        updateStatusForm.reportValidity();
      }
    });
  }
}

/**
 * Helper function to get mock order detail markup
 */
function getOrderDetailMarkup(orderId) {
  return `
    <div style="padding: 1rem; background-color: var(--light); border-radius: var(--radius-md); margin-bottom: 1.5rem;">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h4 style="margin: 0;">Order #${orderId}</h4>
          <p style="margin: 0.5rem 0 0; color: var(--gray);">Placed on ${new Date().toLocaleDateString()}</p>
        </div>
        <span class="status-badge bg-primary">
          <i class="fas fa-check-circle"></i>
          Confirmed
        </span>
      </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
      <div style="padding: 1rem; background-color: var(--light); border-radius: var(--radius-md);">
        <h5 style="margin-top: 0;">Customer</h5>
        <p>John Doe<br>john@example.com<br>+1 (555) 123-4567</p>
      </div>
      <div style="padding: 1rem; background-color: var(--light); border-radius: var(--radius-md);">
        <h5 style="margin-top: 0;">Shipping Address</h5>
        <p>123 Main St<br>Apt 4B<br>New York, NY 10001<br>United States</p>
      </div>
      <div style="padding: 1rem; background-color: var(--light); border-radius: var(--radius-md);">
        <h5 style="margin-top: 0;">Payment Information</h5>
        <p>Method: Credit Card<br>Status: Paid<br>Transaction ID: TXN${Math.floor(Math.random() * 10000)}</p>
      </div>
    </div>
    
    <h4>Order Items</h4>
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="display: flex; align-items: center; gap: 12px;">
              <div style="width: 50px; height: 50px; background-color: #f3f4f6; border-radius: 4px;"></div>
              <div>
                <div style="font-weight: 500;">Basketball Shoes</div>
                <div style="font-size: 0.85rem; color: var(--gray);">SKU: SH-001</div>
              </div>
            </td>
            <td>$129.99</td>
            <td>1</td>
            <td style="font-weight: 500;">$129.99</td>
          </tr>
          <tr>
            <td style="display: flex; align-items: center; gap: 12px;">
              <div style="width: 50px; height: 50px; background-color: #f3f4f6; border-radius: 4px;"></div>
              <div>
                <div style="font-weight: 500;">Basketball Jersey</div>
                <div style="font-size: 0.85rem; color: var(--gray);">SKU: JR-002</div>
              </div>
            </td>
            <td>$59.99</td>
            <td>1</td>
            <td style="font-weight: 500;">$59.99</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align: right; font-weight: 500;">Subtotal</td>
            <td style="font-weight: 500;">$189.98</td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right; font-weight: 500;">Shipping</td>
            <td style="font-weight: 500;">$5.99</td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right; font-weight: 600;">Total</td>
            <td style="font-weight: 600; color: var(--primary);">$195.97</td>
          </tr>
        </tfoot>
      </table>
    </div>
  `;
}
