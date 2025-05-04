<h1>Register</h1>

<div class="auth-form">
    <form action="/register" method="post">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Register</button>
        </div>
    </form>
    
    <div class="form-footer">
        <p>Already have an account? <a href="/login">Login here</a></p>
    </div>
</div>
