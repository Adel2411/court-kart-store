<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to access your CourtKart account</p>
        </div>

        <?php if (isset($error)) { ?>
            <div class="auth-alert auth-alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>

        <?php if (isset($success)) { ?>
            <div class="auth-alert auth-alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php } ?>

        <form action="/login" method="post" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            
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
            </div>
            
            <div class="form-group remember-me">
                <input type="checkbox" id="remember_me" name="remember_me">
                <label for="remember_me">Keep me signed in</label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
        </form>
        
        <div class="form-footer">
            <p>Don't have an account? <a href="/register">Create one now</a></p>
        </div>
    </div>
</div>
