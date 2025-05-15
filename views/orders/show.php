<div class="order-details-container">
    <nav class="breadcrumb" aria-label="breadcrumb">
        <a href="/">Home</a>
        <span class="separator">/</span>
        <a href="/orders">My Orders</a>
        <span class="separator">/</span>
        <span class="current">Order #<?= htmlspecialchars($order->id ?? 'N/A') ?></span>
    </nav>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <h3>Error</h3>
                <p><?= htmlspecialchars($error) ?></p>
            </div>
            <div class="alert-actions">
                <a href="/orders" class="btn btn-primary">View All Orders</a>
                <a href="/shop" class="btn btn-outline">Continue Shopping</a>
            </div>
        </div>
    <?php } elseif (isset($order)) { ?>
        <div class="order-content-grid">
            <div class="order-main-column">
                <div class="order-header">
                    <div class="order-title">
                        <h1>Order #<?= htmlspecialchars($order->id ?? 'N/A') ?></h1>
                        <div class="order-meta">
                            <span class="order-date">
                                <i class="far fa-calendar-alt"></i> 
                                Placed on <?= date('F j, Y', strtotime($order->created_at ?? 'now')) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-actions">
                        <button type="button" class="btn btn-outline-secondary print-order-btn">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <?php if (($order->status ?? '') !== 'cancelled' && ($order->status ?? '') !== 'delivered') { ?>
                            <button type="button" class="btn btn-outline-danger cancel-order-btn" data-order-id="<?= htmlspecialchars($order->id ?? '') ?>">
                                <i class="fas fa-times-circle"></i> Cancel Order
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <div class="order-status-card">
                    <div class="status-header">
                        <h2>Order Status</h2>
                        <?php
                        $statusIcon = '';
        $statusClass = '';
        switch ($order->status ?? '') {
            case 'pending':
                $statusIcon = 'clock';
                $statusClass = 'pending';
                break;
            case 'confirmed':
                $statusIcon = 'check-circle';
                $statusClass = 'confirmed';
                break;
            case 'shipped':
                $statusIcon = 'truck';
                $statusClass = 'shipped';
                break;
            case 'delivered':
                $statusIcon = 'box-open';
                $statusClass = 'delivered';
                break;
            case 'cancelled':
                $statusIcon = 'times-circle';
                $statusClass = 'cancelled';
                break;
            default:
                $statusIcon = 'circle';
                $statusClass = 'pending';
        }
        ?>
                        <div class="status-badge status-<?= $statusClass ?>">
                            <i class="fas fa-<?= $statusIcon ?>"></i>
                            <?= ucfirst($order->status ?? 'Processing') ?>
                        </div>
                    </div>

                    <?php if (($order->status ?? '') !== 'cancelled') { ?>
                        <div class="order-timeline">
                            <div class="timeline-track">
                                <?php
                $progress = 0;
                        switch ($order->status ?? '') {
                            case 'pending': $progress = 25;
                                break;
                            case 'confirmed': $progress = 50;
                                break;
                            case 'shipped': $progress = 75;
                                break;
                            case 'delivered': $progress = 100;
                                break;
                            default: $progress = 0;
                        }
                        ?>
                                <div class="progress-bar" style="width: <?= $progress ?>%"></div>
                            </div>
                            
                            <div class="timeline-steps">
                                <div class="step <?= in_array($order->status, ['pending', 'confirmed', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                                    <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
                                    <div class="step-label">Order Placed</div>
                                    <div class="step-date"><?= date('M d', strtotime($order->created_at ?? 'now')) ?></div>
                                </div>
                                <div class="step <?= in_array($order->status, ['confirmed', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                                    <div class="step-icon"><i class="fas fa-check"></i></div>
                                    <div class="step-label">Confirmed</div>
                                    <div class="step-date"><?= in_array($order->status, ['confirmed', 'shipped', 'delivered']) ? date('M d', strtotime('+1 day', strtotime($order->created_at ?? 'now'))) : '' ?></div>
                                </div>
                                <div class="step <?= in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' ?>">
                                    <div class="step-icon"><i class="fas fa-truck"></i></div>
                                    <div class="step-label">Shipped</div>
                                    <div class="step-date"><?= in_array($order->status, ['shipped', 'delivered']) ? date('M d', strtotime('+3 days', strtotime($order->created_at ?? 'now'))) : '' ?></div>
                                </div>
                                <div class="step <?= ($order->status ?? '') === 'delivered' ? 'completed' : '' ?>">
                                    <div class="step-icon"><i class="fas fa-box-open"></i></div>
                                    <div class="step-label">Delivered</div>
                                    <div class="step-date"><?= ($order->status ?? '') === 'delivered' ? date('M d', strtotime('+5 days', strtotime($order->created_at ?? 'now'))) : '' ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="cancelled-notice">
                            <i class="fas fa-ban"></i>
                            <p>This order was cancelled on <?= date('F j, Y', strtotime($order->updated_at ?? $order->created_at ?? 'now')) ?></p>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="order-section">
                    <h2>Order Items</h2>
                    
                    <?php if (isset($orderItems) && ! empty($orderItems)) { ?>
                        <ul class="order-items">
                            <?php foreach ($orderItems as $item) { ?>
                                <li class="order-item">
                                    <div class="item-image">
                                        <img src="<?= htmlspecialchars($item->image_url) ?>" alt="<?= htmlspecialchars($item->product_name) ?>" 
                                             onerror="this.src='/assets/images/placeholder-product.png'">
                                    </div>
                                    <div class="item-details">
                                        <h3 class="item-name"><?= htmlspecialchars($item->product_name) ?></h3>
                                        <div class="item-meta">
                                            <span class="item-price">$<?= number_format($item->price, 2) ?></span>
                                            <span class="item-quantity">× <?= $item->quantity ?></span>
                                        </div>
                                    </div>
                                    <div class="item-subtotal">
                                        $<?= number_format($item->subtotal, 2) ?>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>No items found in this order.</p>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="order-section">
                    <h2>Customer Information</h2>
                    <div class="info-columns">
                        <div class="info-column">
                            <h3><i class="fas fa-user"></i> Details</h3>
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
            
            <div class="order-summary-column">
                <div class="order-summary-card">
                    <h2>Order Summary</h2>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?= number_format($order->subtotal ?? 0, 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span><?= ($order->shipping ?? 0) > 0 ? '$'.number_format($order->shipping, 2) : 'Free' ?></span>
                    </div>
                    <?php if (isset($order->tax) && $order->tax > 0) { ?>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span>$<?= number_format($order->tax, 2) ?></span>
                    </div>
                    <?php } ?>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$<?= number_format($order->total_price ?? 0, 2) ?></span>
                    </div>
                    
                    <div class="payment-info">
                        <div class="payment-method">
                            <span class="label">Payment Method:</span>
                            <span class="value"><?= htmlspecialchars(ucfirst($order->payment_method ?? 'Credit Card')) ?></span>
                        </div>
                        <div class="payment-status <?= ($order->status === 'cancelled') ? 'cancelled' : 'paid' ?>">
                            <?= ($order->status === 'cancelled') ? 'Cancelled' : 'Paid' ?>
                        </div>
                    </div>
                </div>
                
                <div class="help-card">
                    <h3>Need Help?</h3>
                    <p>If you have any questions about your order, please contact our customer service team.</p>
                    <div class="help-actions">
                        <a href="tel:+1234567890" class="btn btn-link btn-block">
                            <i class="fas fa-phone"></i> Call Support
                        </a>
                        <a href="/contact" class="btn btn-link btn-block">Contact Us</a>
                    </div>
                </div>
                
                <div class="continue-shopping">
                    <a href="/shop" class="btn btn-primary btn-block">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        
    <?php } else { ?>
        <div class="alert alert-warning">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <h3>Order Not Found</h3>
                <p>We couldn't find the order you're looking for.</p>
            </div>
            <div class="alert-actions">
                <a href="/orders" class="btn btn-primary">View All Orders</a>
                <a href="/shop" class="btn btn-outline">Continue Shopping</a>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Cancel Order Modal -->
<div class="modal" id="cancelOrderModal" aria-hidden="true" role="dialog" aria-labelledby="cancelOrderTitle">
    <div class="modal-backdrop" tabindex="-1" data-close></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="cancelOrderTitle">Cancel Order</h3>
            <button type="button" class="modal-close" data-close aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
            <form id="cancelOrderForm" method="post">
                <div class="form-group">
                    <label for="cancelReason">Please tell us why you're cancelling this order:</label>
                    <textarea id="cancelReason" name="reason" class="form-control" rows="3" required></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" id="cancelModalBtn" data-close>Back</button>
            <button type="button" class="btn btn-danger" id="confirmCancelBtn">Confirm Cancellation</button>
        </div>
    </div>
</div>
