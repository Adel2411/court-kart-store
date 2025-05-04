<h1>Login</h1>

<div class="auth-form">
    <form action="/login" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Login</button>
        </div>
    </form>
    
    <div class="form-footer">
        <p>Don't have an account? <a href="/register">Register here</a></p>
    </div>
</div>
