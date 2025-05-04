<h1>Login</h1>

<?php if (isset($error)) { ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php } ?>

<?php if (isset($success)) { ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php } ?>

<div class="auth-form">
    <form action="/login" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group remember-me">
            <input type="checkbox" id="remember_me" name="remember_me">
            <label for="remember_me">Remember me</label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">Login</button>
        </div>
    </form>
    
    <div class="form-footer">
        <p>Don't have an account? <a href="/register">Register here</a></p>
    </div>
</div>
