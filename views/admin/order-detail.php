<div class="admin-content-header">
    <h1>Order #<?= htmlspecialchars($order->id ?? 'N/A') ?></h1>
    <div class="admin-controls">
        <a href="/admin/orders" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</div>

<div class="order-details-container">
    <!-- Order header section -->
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">Order Summary</div>
            <div class="admin-card-actions">
                <button class="btn btn-outline print-order-btn">
                    <i class="fas fa-print"></i> Print Order
                </button>
            </div>
        </div>
        <div class="admin-card-body">
            <div class="order-meta-grid">
                <div class="order-meta-item">
                    <div class="meta-label">Order Date</div>
                    <div class="meta-value"><?= date('F j, Y', strtotime($order->created_at ?? 'now')) ?></div>
                </div>
                <div class="order-meta-item">
                    <div class="meta-label">Status</div>
                    <div class="meta-value">
                        <?php 
                        $statusClass = '';
                        switch ($order->status ?? '') {
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
                            <?= ucfirst($order->status ?? 'Processing') ?>
                        </span>
                    </div>
                </div>
                <div class="order-meta-item">
                    <div class="meta-label">Customer</div>
                    <div class="meta-value"><?= htmlspecialchars($order->customer_name ?? 'N/A') ?></div>
                </div>
                <div class="order-meta-item">
                    <div class="meta-label">Email</div>
                    <div class="meta-value"><?= htmlspecialchars($order->customer_email ?? 'N/A') ?></div>
                </div>
                <div class="order-meta-item">
                    <div class="meta-label">Total</div>
                    <div class="meta-value"><?= '$' . number_format($order->total ?? 0, 2) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order content in two column layout -->
    <div class="order-content">
        <div class="order-details-column">
            <!-- Order Items List -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">Order Items</div>
                </div>
                <div class="admin-card-body">
                    <?php if (!empty($orderItems)): ?>
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
                                    <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="product-info-cell">
                                                    <img src="<?= htmlspecialchars($item->image_url) ?>" 
                                                         alt="<?= htmlspecialchars($item->product_name) ?>"
                                                         class="product-thumbnail"
                                                         onerror="this.src='/assets/images/placeholder-product.png'">
                                                    <div>
                                                        <div class="product-name"><?= htmlspecialchars($item->product_name) ?></div>
                                                        <div class="product-id">ID: <?= $item->product_id ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= '$' . number_format($item->price, 2) ?></td>
                                            <td><?= $item->quantity ?></td>
                                            <td><strong><?= '$' . number_format($item->subtotal, 2) ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right">Subtotal</td>
                                        <td><strong><?= '$' . number_format($order->subtotal, 2) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Shipping</td>
                                        <td>
                                            <?= $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Total</td>
                                        <td><strong class="order-total"><?= '$' . number_format($order->total, 2) ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>No items found in this order.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">Customer Information</div>
                </div>
                <div class="admin-card-body">
                    <div class="info-columns">
                        <div class="info-column">
                            <h3><i class="fas fa-user"></i> Customer Details</h3>
                            <p><strong>Name:</strong> <?= htmlspecialchars($order->customer_name ?? 'N/A') ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($order->customer_email ?? 'N/A') ?></p>
                        </div>
                        <div class="info-column">
                            <h3><i class="fas fa-truck-loading"></i> Shipping Address</h3>
                            <p><?= nl2br(htmlspecialchars($order->address ?? 'No address provided')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="order-actions-column">
            <!-- Update Order Status -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">Update Status</div>
                </div>
                <div class="admin-card-body">
                    <form action="/admin/orders/update-status" method="post" id="updateStatusForm">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order->id ?? '') ?>">
                        
                        <div class="admin-form-group">
                            <label for="orderStatus">Status</label>
                            <select id="orderStatus" name="status" class="form-control" required>
                                <option value="pending" <?= ($order->status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= ($order->status ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="shipped" <?= ($order->status ?? '') === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= ($order->status ?? '') === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= ($order->status ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">Payment Information</div>
                </div>
                <div class="admin-card-body">
                    <p><strong>Method:</strong> <?= htmlspecialchars(ucfirst($order->payment_method ?? 'Credit Card')) ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-success">Paid</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional styles for order detail page */
.order-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.order-meta-item {
    margin-bottom: 1rem;
}

.meta-label {
    font-size: 0.85rem;
    color: var(--gray);
    margin-bottom: 0.25rem;
}

.meta-value {
    font-weight: 500;
    color: var(--secondary-dark);
}

.order-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.text-right {
    text-align: right;
}

.product-info-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    border: 1px solid var(--light-gray);
}

.product-name {
    font-weight: 500;
}

.product-id {
    font-size: 0.85rem;
    color: var(--gray);
}

.order-total {
    color: var(--primary);
    font-size: 1.1rem;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-pill);
    font-size: 0.75rem;
    font-weight: 500;
}

.bg-success {
    background-color: var(--success);
    color: white;
}

@media (max-width: 992px) {
    .order-content {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print functionality
    const printBtn = document.querySelector('.print-order-btn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Form submission confirmation
    const updateForm = document.getElementById('updateStatusForm');
    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            const status = document.getElementById('orderStatus').value;
            const currentStatus = '<?= $order->status ?? 'pending' ?>';
            
            if (status === 'cancelled' && currentStatus !== 'cancelled') {
                if (!confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
                    e.preventDefault();
                }
            }
        });
    }
});
</script>
