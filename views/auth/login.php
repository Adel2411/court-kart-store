<div class="auth-container">
    <div class="auth-background">
        <div class="court-lines"></div>
    </div>
    
    <div class="auth-logo-mobile">
        <img src="/assets/images/court-kart-logo.svg" alt="Court Kart">
    </div>
    
    <div class="auth-card">
        <div class="auth-card-inner">
            <div class="auth-side brand-side">
                <div class="brand-content">
                    <div class="logo-container">
                        <img src="/assets/images/court-kart-logo.svg" alt="Court Kart" class="auth-logo">
                    </div>
                    <h2 class="brand-tagline">Gear Up<br>For Greatness</h2>
                    <p class="brand-description">Premium basketball equipment for players who demand excellence on and off the court.</p>
                    
                </div>
            </div>
            
            <div class="auth-side form-side">
                <div class="form-header">
                    <h1>Welcome Back</h1>
                    <p>Sign in to continue your basketball journey</p>
                </div>
                
                <?php if (isset($error)) { ?>
                    <div class="auth-message error">
                        <div class="message-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="message-content">
                            <p><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                <?php } ?>
                
                <?php if (isset($success)) { ?>
                    <div class="auth-message success">
                        <div class="message-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="message-content">
                            <p><?= htmlspecialchars($success) ?></p>
                        </div>
                    </div>
                <?php } ?>
                
                <form action="/login" method="post" class="auth-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" 
                                   placeholder="your.email@example.com" 
                                   required autofocus
                                   autocomplete="email">
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <div class="label-row">
                            <label for="password">Password</label>
                            <a href="/forgot-password" class="forgot-link">Forgot password?</a>
                        </div>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password" 
                                   placeholder="Your password" 
                                   required
                                   autocomplete="current-password">
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-field checkbox-field">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember_me" id="remember_me">
                            <span class="checkmark"></span>
                            <span class="checkbox-label">Keep me signed in</span>
                        </label>
                    </div>
                    
                    <div class="form-field">
                        <button type="submit" class="submit-btn">
                            <span class="btn-text">Sign In</span>
                            <span class="btn-icon">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </button>
                    </div>
                </form>
                
                <div class="auth-footer">
                    <p>Don't have an account? <a href="/register" class="accent-link">Create one now</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const toggleButtons = document.querySelectorAll('.password-toggle');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Input focus effects
    const inputs = document.querySelectorAll('.input-wrapper input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('focused');
            }
        });
        
        if (input.value !== '') {
            input.parentElement.classList.add('focused');
        }
    });
    
    // Animated basketball background
    const createBasketballs = () => {
        const background = document.querySelector('.animated-basketball');
        if (!background) return;
        
        const isMobile = window.innerWidth <= 768;
        const ballCount = isMobile ? 4 : 8;
        
        for (let i = 0; i < ballCount; i++) {
            const ball = document.createElement('div');
            ball.classList.add('ball');
            ball.style.left = `${Math.random() * 100}%`;
            ball.style.animationDuration = `${Math.random() * 20 + 10}s`;
            ball.style.animationDelay = `${Math.random() * 5}s`;
            background.appendChild(ball);
        }
    };
    
    createBasketballs();
    
    // Handle orientation changes
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            const isLandscape = window.matchMedia("(orientation: landscape)").matches;
            const isMobile = window.innerWidth <= 768;
            
            if (isLandscape && isMobile) {
                document.querySelector('.auth-card').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 200);
    });
});
</script>
