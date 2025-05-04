<h1>My Account</h1>

<div class="account-container">
    <div class="account-info">
        <h2>Account Information</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Account Type:</strong> <?= ucfirst(htmlspecialchars($user['role'])) ?></p>
        <p><strong>Member Since:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
    </div>
    
    <div class="account-actions">
        <h2>Account Actions</h2>
        <div class="action-links">
            <a href="/orders" class="btn">View My Orders</a>
            <a href="/cart" class="btn">View Cart</a>
            <a href="/shop" class="btn">Continue Shopping</a>
        </div>
    </div>
</div>

<style>
    .account-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    
    .account-info, .account-actions {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .action-links {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-width: 200px;
    }
    
    @media (max-width: 768px) {
        .account-container {
            grid-template-columns: 1fr;
        }
    }
</style>
