/**
 * Orders Page Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Order row highlighting
    const orderRows = document.querySelectorAll('.orders-table tbody tr');
    orderRows.forEach(row => {
        row.addEventListener('click', function(event) {
            // Only trigger if we didn't click on a button or link
            if (!event.target.closest('a') && !event.target.closest('button')) {
                const orderId = this.querySelector('td:first-child').textContent.replace('#', '');
                window.location.href = `/orders/${orderId}`;
            }
        });
    });
    
    // Print order functionality (if present on page)
    const printOrderBtn = document.querySelector('.print-order-btn');
    if (printOrderBtn) {
        printOrderBtn.addEventListener('click', function(event) {
            event.preventDefault();
            window.print();
        });
    }
    
    // Status filter (if present)
    const statusFilters = document.querySelectorAll('.status-filter');
    if (statusFilters.length > 0) {
        statusFilters.forEach(filter => {
            filter.addEventListener('click', function(event) {
                event.preventDefault();
                
                // Remove active class from all filters
                statusFilters.forEach(f => f.classList.remove('active'));
                this.classList.add('active');
                
                const status = this.getAttribute('data-status');
                filterOrdersByStatus(status);
            });
        });
    }
});

/**
 * Filter orders by status
 * @param {string} status - The status to filter by, or 'all'
 */
function filterOrdersByStatus(status) {
    const orderRows = document.querySelectorAll('.orders-table tbody tr');
    
    orderRows.forEach(row => {
        const rowStatus = row.querySelector('.status-badge').className
            .replace('status-badge', '')
            .replace('status-', '')
            .trim();
            
        if (status === 'all' || rowStatus === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
