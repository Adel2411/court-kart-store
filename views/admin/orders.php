<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Orders</h2>
        <div class="admin-card-actions">
            <div class="filter-dropdown" style="position: relative;">
                <button type="button" class="btn btn-outline btn-sm" id="filterBtn">
                    <i class="fas fa-filter"></i> Filter by Status
                </button>
                <div id="filterDropdown" style="display: none; position: absolute; top: 100%; right: 0; z-index: 10; background: white; box-shadow: var(--shadow-md); border-radius: var(--radius-md); min-width: 180px; margin-top: 4px;">
                    <a href="/admin/orders?status=all" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary);">All Orders</a>
                    <a href="/admin/orders?status=pending" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary);">Pending</a>
                    <a href="/admin/orders?status=confirmed" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary);">Confirmed</a>
                    <a href="/admin/orders?status=shipped" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary);">Shipped</a>
                    <a href="/admin/orders?status=delivered" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary);">Delivered</a>
                    <a href="/admin/orders?status=cancelled" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary);">Cancelled</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)) { ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">
                            <div style="color: var(--gray);">
                                <i class="fas fa-shopping-bag" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                <p>No orders found</p>
                            </div>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td>
                                <div style="font-weight: 500;"><?= htmlspecialchars($order['customer_name']) ?></div>
                                <div style="font-size: 0.85rem; color: var(--gray);">
                                    <?= htmlspecialchars($order['customer_email'] ?? '') ?>
                                </div>
                            </td>
                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <span class="badge" style="background-color: var(--light); color: var(--secondary);">
                                    <?= $order['items_count'] ?> item<?= $order['items_count'] !== 1 ? 's' : '' ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: var(--primary);">$<?= number_format($order['total_price'], 2) ?></span>
                            </td>
                            <td>
                                <?php 
                                $statusClass = '';
                                switch ($order['status']) {
                                    case 'pending':
                                        $statusClass = 'bg-warning';
                                        $icon = 'clock';
                                        break;
                                    case 'confirmed':
                                        $statusClass = 'bg-primary';
                                        $icon = 'check-circle';
                                        break;
                                    case 'shipped':
                                        $statusClass = 'bg-info';
                                        $icon = 'truck';
                                        break;
                                    case 'delivered':
                                        $statusClass = 'bg-success';
                                        $icon = 'box-open';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-danger';
                                        $icon = 'times-circle';
                                        break;
                                    default:
                                        $statusClass = 'bg-secondary';
                                        $icon = 'circle';
                                }
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <i class="fas fa-<?= $icon ?>"></i>
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-primary update-status" data-id="<?= $order['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="admin-modal" id="orderDetailModal">
    <div class="admin-modal-content" style="max-width: 800px;">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Order Details</h3>
            <button type="button" class="admin-modal-close" id="closeOrderModal">&times;</button>
        </div>
        
        <div class="admin-modal-body">
            <div id="orderDetailContent">
                <!-- Order details will be loaded here -->
                <div class="loading-spinner" style="text-align: center; padding: 2rem;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="admin-modal" id="updateStatusModal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Update Order Status</h3>
            <button type="button" class="admin-modal-close" id="closeStatusModal">&times;</button>
        </div>
        
        <div class="admin-modal-body">
            <form id="updateStatusForm" action="/admin/orders/update-status" method="post">
                <input type="hidden" name="order_id" id="statusOrderId">
                
                <div class="admin-form-group">
                    <label for="orderStatus">Status</label>
                    <select id="orderStatus" name="status" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </form>
        </div>
        
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-outline" id="cancelStatusBtn">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveStatusBtn">Update Status</button>
        </div>
    </div>
</div>

<style>
    .bg-warning {
        background-color: var(--warning);
        color: white;
    }
    
    .bg-primary {
        background-color: var(--primary);
        color: white;
    }
    
    .bg-info {
        background-color: var(--info);
        color: white;
    }
    
    .bg-success {
        background-color: var(--success);
        color: white;
    }
    
    .bg-danger {
        background-color: var(--danger);
        color: white;
    }
    
    .bg-secondary {
        background-color: var(--secondary);
        color: white;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter dropdown
    const filterBtn = document.getElementById('filterBtn');
    const filterDropdown = document.getElementById('filterDropdown');
    
    filterBtn.addEventListener('click', function() {
        filterDropdown.style.display = filterDropdown.style.display === 'none' ? 'block' : 'none';
    });
    
    document.addEventListener('click', function(event) {
        if (!filterBtn.contains(event.target) && !filterDropdown.contains(event.target)) {
            filterDropdown.style.display = 'none';
        }
    });
    
    // Order Detail Modal
    const orderDetailModal = document.getElementById('orderDetailModal');
    const closeOrderModal = document.getElementById('closeOrderModal');
    const orderDetailContent = document.getElementById('orderDetailContent');
    const viewOrderBtns = document.querySelectorAll('.view-order');
    
    viewOrderBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            orderDetailModal.classList.add('active');
            
            // In a real application, you would fetch order details via AJAX
            // For now, we'll just show mock data
            orderDetailContent.innerHTML = `
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
        });
    });
    
    closeOrderModal.addEventListener('click', function() {
        orderDetailModal.classList.remove('active');
    });
    
    // Update Status Modal
    const updateStatusModal = document.getElementById('updateStatusModal');
    const closeStatusModal = document.getElementById('closeStatusModal');
    const cancelStatusBtn = document.getElementById('cancelStatusBtn');
    const saveStatusBtn = document.getElementById('saveStatusBtn');
    const statusOrderId = document.getElementById('statusOrderId');
    const updateStatusForm = document.getElementById('updateStatusForm');
    const updateStatusBtns = document.querySelectorAll('.update-status');
    
    updateStatusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            statusOrderId.value = orderId;
            updateStatusModal.classList.add('active');
        });
    });
    
    function closeUpdateStatusModal() {
        updateStatusModal.classList.remove('active');
    }
    
    closeStatusModal.addEventListener('click', closeUpdateStatusModal);
    cancelStatusBtn.addEventListener('click', closeUpdateStatusModal);
    
    saveStatusBtn.addEventListener('click', function() {
        if (updateStatusForm.checkValidity()) {
            updateStatusForm.submit();
        } else {
            // Trigger HTML5 validation
            updateStatusForm.reportValidity();
        }
    });
});
</script>
