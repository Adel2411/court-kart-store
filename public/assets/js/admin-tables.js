/**
 * Admin Tables Responsiveness
 * This script adds visual indicators and improves table responsiveness
 */
document.addEventListener('DOMContentLoaded', function() {
    // Find all table wrappers
    const tableWrappers = document.querySelectorAll('.admin-table-wrapper, .users-table-wrapper');
    
    tableWrappers.forEach(wrapper => {
        // Check if table is wider than its container
        checkTableOverflow(wrapper);
        
        // Check again on window resize
        window.addEventListener('resize', () => {
            checkTableOverflow(wrapper);
        });
        
        // Add scroll indication when scrolling
        wrapper.addEventListener('scroll', () => {
            if (wrapper.scrollLeft > 0) {
                wrapper.classList.add('scrolled-horizontally');
            } else {
                wrapper.classList.remove('scrolled-horizontally');
            }
        });
    });
    
    // Add class to parent for tables that overflow
    function checkTableOverflow(wrapper) {
        const table = wrapper.querySelector('table');
        if (table && table.offsetWidth > wrapper.offsetWidth) {
            wrapper.closest('.admin-card, .card').classList.add('admin-table-scroll');
        } else {
            wrapper.closest('.admin-card, .card')?.classList.remove('admin-table-scroll');
        }
    }
    
    // Add column classes to table cells for better responsive control
    const tables = document.querySelectorAll('.admin-table, .users-table');
    tables.forEach(table => {
        // ID columns (usually first column)
        const firstCells = table.querySelectorAll('tr > *:first-child');
        firstCells.forEach(cell => cell.classList.add('col-id'));
        
        // Image columns (usually have img tag)
        table.querySelectorAll('td').forEach(cell => {
            if (cell.querySelector('img')) {
                cell.classList.add('col-image');
                const headerIndex = Array.from(cell.parentNode.children).indexOf(cell);
                const headerRow = table.querySelector('thead tr');
                if (headerRow && headerRow.children[headerIndex]) {
                    headerRow.children[headerIndex].classList.add('col-image');
                }
            }
        });
        
        // Action columns (usually last column)
        const lastCells = table.querySelectorAll('tr > *:last-child');
        lastCells.forEach(cell => cell.classList.add('col-actions'));
    });
});
