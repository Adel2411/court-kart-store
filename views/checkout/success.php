<div class="page-header">
    <h1>Order Confirmed!</h1>
    <p class="breadcrumbs">
        <a href="/">Home</a> > 
        <a href="/shop">Shop</a> > 
        <span>Order Confirmation</span>
    </p>
</div>

<div class="success-container">
    <div class="success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    
    <h2>Thank You for Your Order!</h2>
    
    <div class="order-details">
        <p>Your order <strong>#<?= htmlspecialchars($orderId ?? '') ?></strong> has been placed successfully.</p>
        <p>We've sent a confirmation email to <strong><?= htmlspecialchars($email ?? '') ?></strong> with all the details.</p>
    </div>
    
    <div class="order-summary-card">
        <h3>Order Summary</h3>
        
        <div class="summary-details">
            <div class="summary-row">
                <span>Order Date:</span>
                <span><?= date('F j, Y') ?></span>
            </div>
            
            <div class="summary-row">
                <span>Payment Method:</span>
                <span><?= htmlspecialchars(ucfirst($paymentMethod ?? 'Credit Card')) ?></span>
            </div>
            
            <?php if (isset($subtotal) && isset($discountAmount) && $discountAmount > 0): ?>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>$<?= number_format($subtotal ?? 0, 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Discount:</span>
                <span>-$<?= number_format($discountAmount ?? 0, 2) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="summary-row">
                <span>Total:</span>
                <span>$<?= number_format($totalPrice ?? 0, 2) ?></span>
            </div>
        </div>
    </div>
    
    <div class="next-steps">
        <h3>What's Next?</h3>
        <ul>
            <li>We're preparing your order for shipment.</li>
            <li>You'll receive updates about your order status via email.</li>
            <li>You can track your order anytime in your <a href="/orders">order history</a>.</li>
        </ul>
    </div>
    
    <div class="action-buttons">
        <a href="/orders/<?= htmlspecialchars($orderId ?? '') ?>" class="btn btn-outline">View Order Details</a>
        <a href="/shop" class="btn btn-primary">Continue Shopping</a>
    </div>
</div>
