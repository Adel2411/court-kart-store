<div class="page-header">
    <h1>Checkout</h1>
    <p class="breadcrumbs">
        <a href="/">Home</a> > 
        <a href="/cart">Cart</a> > 
        <span>Checkout</span>
    </p>
</div>

<div class="checkout-container">
    <div class="checkout-details">
        <form action="/checkout" method="post" id="checkout-form">
            <div class="section-header">
                <h2>Shipping Information</h2>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="fullname">Full Name *</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= \App\Core\Session::get('user_email') ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="shipping_address">Shipping Address *</label>
                <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="city">City *</label>
                    <input type="text" id="city" name="city" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="state">State/Province *</label>
                    <input type="text" id="state" name="state" class="form-control" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="zip">Postal/Zip Code *</label>
                    <input type="text" id="zip" name="zip" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="country">Country *</label>
                    <select id="country" name="country" class="form-control" required>
                        <option value="">Select Country</option>
                        <option value="US">United States</option>
                        <option value="CA">Canada</option>
                        <option value="UK">United Kingdom</option>
                        <!-- Add more countries as needed -->
                    </select>
                </div>
            </div>
            
            <div class="section-header">
                <h2>Payment Method</h2>
            </div>
            
            <div class="form-group payment-methods">
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="credit_card" checked>
                    <div class="payment-method-content">
                        <span class="payment-method-title">Credit Card</span>
                        <span class="payment-icons">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-amex"></i>
                        </span>
                    </div>
                </label>
                
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="paypal">
                    <div class="payment-method-content">
                        <span class="payment-method-title">PayPal</span>
                        <span class="payment-icons">
                            <i class="fab fa-paypal"></i>
                        </span>
                    </div>
                </label>
            </div>
            
            <div id="credit-card-form" class="payment-details">
                <div class="form-row">
                    <div class="form-group">
                        <label for="card_number">Card Number *</label>
                        <input type="text" id="card_number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="card_name">Name on Card *</label>
                        <input type="text" id="card_name" name="card_name" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="expiry">Expiration (MM/YY) *</label>
                        <input type="text" id="expiry" name="expiry" class="form-control" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV *</label>
                        <input type="text" id="cvv" name="cvv" class="form-control" placeholder="123" required>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-large btn-block place-order-btn">
                Place Order
            </button>
        </form>
    </div>
    
    <div class="checkout-sidebar">
        <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="summary-items">
                <?php foreach ($cartItems as $item) { ?>
                    <div class="summary-item">
                        <div class="summary-item-image">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <div class="summary-item-details">
                            <h4><?= htmlspecialchars($item['name']) ?></h4>
                            <div class="summary-item-meta">
                                <span class="quantity">Qty: <?= $item['quantity'] ?></span>
                                <span class="price">$<?= number_format($item['price'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            
            <div class="summary-totals">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>$<?= number_format($totalPrice, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>
                <div class="summary-row">
                    <span>Tax</span>
                    <span>$0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>$<?= number_format($totalPrice, 2) ?></span>
                </div>
            </div>
            
            <div class="summary-footer">
                <a href="/cart" class="btn btn-outline btn-block">Back to Cart</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle payment method details
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const creditCardForm = document.getElementById('credit-card-form');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'credit_card') {
                creditCardForm.style.display = 'block';
            } else {
                creditCardForm.style.display = 'none';
            }
        });
    });
    
    // Form validation
    // const checkoutForm = document.getElementById('checkout-form');
    // checkoutForm.addEventListener('submit', function(event) {
    // });
});
</script>
