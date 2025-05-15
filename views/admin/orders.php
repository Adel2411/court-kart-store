<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Orders</h2>
        <div class="admin-card-actions">
            <div class="filter-dropdown" style="position: relative;">
                <button type="button" class="btn btn-outline btn-sm" id="filterBtn">
                    <i class="fas fa-filter"></i> Filter by Status
                </button>
                <div id="filterDropdown" style="display: none; position: absolute; top: 100%; right: 0; z-index: 10; background: white; box-shadow: var(--shadow-md); border-radius: var(--radius-md); min-width: 180px; margin-top: 4px;">
                    <a href="/admin/orders?status=all" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary); <?= ($currentStatus ?? '') === 'all' ? 'background-color: var(--light); font-weight: bold;' : '' ?>">All Orders</a>
                    <a href="/admin/orders?status=pending" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary); <?= ($currentStatus ?? '') === 'pending' ? 'background-color: var(--light); font-weight: bold;' : '' ?>">Pending</a>
                    <a href="/admin/orders?status=confirmed" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary); <?= ($currentStatus ?? '') === 'confirmed' ? 'background-color: var(--light); font-weight: bold;' : '' ?>">Confirmed</a>
                    <a href="/admin/orders?status=shipped" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary); <?= ($currentStatus ?? '') === 'shipped' ? 'background-color: var(--light); font-weight: bold;' : '' ?>">Shipped</a>
                    <a href="/admin/orders?status=delivered" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary); <?= ($currentStatus ?? '') === 'delivered' ? 'background-color: var(--light); font-weight: bold;' : '' ?>">Delivered</a>
                    <a href="/admin/orders?status=cancelled" style="display: block; padding: 8px 16px; text-decoration: none; color: var(--secondary); <?= ($currentStatus ?? '') === 'cancelled' ? 'background-color: var(--light); font-weight: bold;' : '' ?>">Cancelled</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status indicator for filtered view -->
    <?php if (($currentStatus ?? 'all') !== 'all') { ?>
    <div class="filter-status-indicator" style="padding: 0.5rem 1.5rem; background: var(--light); border-radius: var(--radius-sm); margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <i class="fas fa-filter"></i> Showing only <strong><?= ucfirst($currentStatus ?? 'all') ?></strong> orders
        </div>
        <a href="/admin/orders?status=all" class="btn btn-sm btn-outline">Clear Filter</a>
    </div>
    <?php } ?>
    
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
                                <button class="btn btn-sm btn-primary update-status" data-id="<?= $order['id'] ?>" data-status="<?= $order['status'] ?>">
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
<div class="modal" id="orderDetailModal" aria-hidden="true" role="dialog" aria-labelledby="orderDetailTitle">
    <div class="modal-backdrop" tabindex="-1" data-close></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="orderDetailTitle">Order Details</h3>
            <button type="button" class="modal-close" data-close aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div id="orderDetailContent">
                <!-- Order details will be loaded here dynamicly -->
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" data-close>Close</button>
            <button type="button" class="btn btn-primary print-order" id="printOrderBtn">Print Order</button>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal" id="updateStatusModal" aria-hidden="true" role="dialog" aria-labelledby="updateStatusTitle">
    <div class="modal-backdrop" tabindex="-1" data-close></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="updateStatusTitle">Update Order Status</h3>
            <button type="button" class="modal-close" data-close aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
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
        
        <div class="modal-footer">
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

<script src="/assets/js/pages/admin-orders.js"></script>
</body>
</html>
