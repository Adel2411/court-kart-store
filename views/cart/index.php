<h1>Your Shopping Cart</h1>

<?php if (empty($cart_items)) { ?>
    <div class="empty-cart">
        <div class="empty-state-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <h2>Your cart is empty</h2>
        <p>Looks like you haven't added any products to your cart yet.</p>
        <a href="/shop" class="btn btn-primary">Continue Shopping</a>
    </div>
<?php } else { ?>
    <div class="cart-container">
        <div class="cart-items">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item) { ?>
                        <tr>
                            <td class="product-info">
                                <div class="product-image">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                </div>
                                <div class="product-details">
                                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                                    <a href="/shop/product/<?= $item['product_id'] ?>">View product</a>
                                </div>
                            </td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <form action="/cart/update" method="post" class="quantity-form">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="10">
                                    <button type="submit" class="btn btn-sm">Update</button>
                                </form>
                            </td>
                            <td>$<?= number_format($item['subtotal'], 2) ?></td>
                            <td>
                                <form action="/cart/remove" method="post">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <div class="cart-summary">
            <h2>Order Summary</h2>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>$<?= number_format($total_price, 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span>Free</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span>$<?= number_format($total_price, 2) ?></span>
            </div>
            
            <div class="cart-actions">
                <a href="/shop" class="btn btn-outline">Continue Shopping</a>
                <a href="/checkout" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        </div>
    </div>
<?php } ?>
