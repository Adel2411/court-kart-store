<h1>Your Shopping Cart</h1>

<?php if (empty($cart_items ?? [])): ?>
    <div class="empty-cart">
        <p>Your cart is empty.</p>
        <a href="/shop" class="btn">Continue Shopping</a>
    </div>
<?php else: ?>
    <table>
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
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <tr>
                    <td>Sample Product <?= $i ?></td>
                    <td>$<?= $price = rand(50, 200) ?>.99</td>
                    <td>
                        <form action="/cart/update" method="post">
                            <input type="hidden" name="product_id" value="<?= $i ?>">
                            <input type="number" name="quantity" value="<?= $qty = rand(1, 3) ?>" min="1" max="10">
                            <button type="submit" class="btn">Update</button>
                        </form>
                    </td>
                    <td>$<?= $price * $qty ?>.99</td>
                    <td>
                        <form action="/cart/remove" method="post">
                            <input type="hidden" name="product_id" value="<?= $i ?>">
                            <button type="submit" class="btn">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endfor; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td colspan="2"><strong>$<?= rand(100, 800) ?>.99</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="cart-actions">
        <a href="/shop" class="btn">Continue Shopping</a>
        <a href="/checkout" class="btn">Proceed to Checkout</a>
    </div>
<?php endif; ?>
