<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join CourtKart for the best basketball gear</p>
        </div>

        <?php if (isset($error)) { ?>
            <div class="auth-alert auth-alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>

        <form action="/register" method="post" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <div class="input-icon-wrapper">
                    <input type="text" id="name" name="name" class="form-control" required>
                    <i class="fas fa-user form-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-icon-wrapper">
                    <input type="email" id="email" name="email" class="form-control" required>
                    <i class="fas fa-envelope form-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon-wrapper">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <i class="fas fa-lock form-icon"></i>
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength" id="passwordStrength"></div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-icon-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    <i class="fas fa-lock form-icon"></i>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
        
        <div class="form-footer">
            <p>Already have an account? <a href="/login">Sign in</a></p>
        </div>
    </div>
</div>
