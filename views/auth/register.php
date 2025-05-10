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
                    <h2 class="brand-tagline">Join The<br>Starting Five</h2>
                    <p class="brand-description">Create your account and get access to exclusive deals and faster checkout.</p>
                    
                    <div class="membership-perks">
                        <div class="perk">
                            <div class="perk-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="perk-text">
                                <h3>Member Discounts</h3>
                                <p>Special pricing on premium gear</p>
                            </div>
                        </div>
                        <div class="perk">
                            <div class="perk-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="perk-text">
                                <h3>Fast Shipping</h3>
                                <p>Priority processing on all orders</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="auth-side form-side">
                <div class="form-header">
                    <h1>Create Account</h1>
                    <p>Sign up to start your basketball journey</p>
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
                
                <form action="/register" method="post" class="auth-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    
                    <div class="form-field">
                        <label for="name">Full Name</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" id="name" name="name" 
                                   placeholder="Your full name" 
                                   required autofocus
                                   autocomplete="name">
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" 
                                   placeholder="your.email@example.com" 
                                   required
                                   autocomplete="email">
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password" 
                                   placeholder="Create a password" 
                                   required
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-meter">
                                <div class="strength-meter-fill" id="strengthMeter"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Password strength: <span>Too weak</span></div>
                        </div>
                        
                        <div class="password-requirements" id="passwordRequirements">
                            <p>Your password must:</p>
                            <ul>
                                <li id="req-length" class="requirement"><i class="fas fa-check-circle"></i> Be at least 8 characters</li>
                                <li id="req-uppercase" class="requirement"><i class="fas fa-check-circle"></i> Include uppercase letter</li>
                                <li id="req-lowercase" class="requirement"><i class="fas fa-check-circle"></i> Include lowercase letter</li>
                                <li id="req-number" class="requirement"><i class="fas fa-check-circle"></i> Include a number</li>
                                <li id="req-special" class="requirement"><i class="fas fa-check-circle"></i> Include special character</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   placeholder="Confirm your password" 
                                   required
                                   autocomplete="new-password">
                        </div>
                        <div class="password-match" id="passwordMatch"></div>
                    </div>
                    
                    <div class="form-field checkbox-field">
                        <label class="checkbox-container">
                            <input type="checkbox" name="agree_terms" id="agree_terms" required>
                            <span class="checkmark"></span>
                            <span class="checkbox-label">I agree to the <a href="/terms" class="terms-link">Terms of Service</a> and <a href="/privacy" class="terms-link">Privacy Policy</a></span>
                        </label>
                    </div>
                    
                    <div class="form-field">
                        <button type="submit" class="submit-btn">
                            <span class="btn-text">Create Account</span>
                            <span class="btn-icon">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </button>
                    </div>
                </form>
                
                <div class="auth-footer">
                    <p>Already have an account? <a href="/login" class="accent-link">Sign in</a></p>
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
        
        // Set initial state for prefilled inputs
        if (input.value !== '') {
            input.parentElement.classList.add('focused');
        }
    });
    
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthMeter = document.getElementById('strengthMeter');
    const strengthText = document.getElementById('strengthText');
    const strengthSpan = strengthText.querySelector('span');
    
    // Password requirements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');
    
    passwordInput.addEventListener('input', function() {
        const value = passwordInput.value;
        let strength = 0;
        let meetsRequirements = 0;
        
        // Reset requirements
        reqLength.classList.remove('met');
        reqUppercase.classList.remove('met');
        reqLowercase.classList.remove('met');
        reqNumber.classList.remove('met');
        reqSpecial.classList.remove('met');
        
        // Check requirements
        if (value.length >= 8) {
            strength += 20;
            meetsRequirements++;
            reqLength.classList.add('met');
        }
        
        if (/[A-Z]/.test(value)) {
            strength += 20;
            meetsRequirements++;
            reqUppercase.classList.add('met');
        }
        
        if (/[a-z]/.test(value)) {
            strength += 20;
            meetsRequirements++;
            reqLowercase.classList.add('met');
        }
        
        if (/[0-9]/.test(value)) {
            strength += 20;
            meetsRequirements++;
            reqNumber.classList.add('met');
        }
        
        if (/[^A-Za-z0-9]/.test(value)) {
            strength += 20;
            meetsRequirements++;
            reqSpecial.classList.add('met');
        }
        
        // Update strength meter
        strengthMeter.style.width = strength + '%';
        
        // Update strength label and color
        if (strength <= 20) {
            strengthMeter.className = 'strength-meter-fill weak';
            strengthSpan.textContent = 'Too weak';
            strengthSpan.className = 'weak';
        } else if (strength <= 60) {
            strengthMeter.className = 'strength-meter-fill medium';
            strengthSpan.textContent = 'Medium';
            strengthSpan.className = 'medium';
        } else {
            strengthMeter.className = 'strength-meter-fill strong';
            strengthSpan.textContent = 'Strong';
            strengthSpan.className = 'strong';
        }
    });
    
    // Password match validation
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordMatchDisplay = document.getElementById('passwordMatch');
    
    function checkPasswordMatch() {
        if (confirmPasswordInput.value === '') {
            passwordMatchDisplay.textContent = '';
            passwordMatchDisplay.className = 'password-match';
            confirmPasswordInput.setCustomValidity('');
        } else if (passwordInput.value === confirmPasswordInput.value) {
            passwordMatchDisplay.textContent = 'Passwords match';
            passwordMatchDisplay.className = 'password-match match';
            confirmPasswordInput.setCustomValidity('');
        } else {
            passwordMatchDisplay.textContent = 'Passwords do not match';
            passwordMatchDisplay.className = 'password-match no-match';
            confirmPasswordInput.setCustomValidity('Passwords do not match');
        }
    }
    
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    passwordInput.addEventListener('input', function() {
        if (confirmPasswordInput.value !== '') {
            checkPasswordMatch();
        }
    });
    
    // Dynamic UI adjustments based on viewport
    const adjustUIForViewport = () => {
        const viewport = window.innerWidth;
        const passwordReqs = document.getElementById('passwordRequirements');
        
        // On small screens, show password requirements only when field is focused
        if (viewport <= 576 && passwordReqs) {
            const passwordInput = document.getElementById('password');
            
            passwordInput.addEventListener('focus', function() {
                passwordReqs.style.display = 'block';
            });
            
            passwordInput.addEventListener('blur', function() {
                if (this.value === '') {
                    passwordReqs.style.display = 'none';
                }
            });
            
            // Initially hide if empty
            if (passwordInput.value === '') {
                passwordReqs.style.display = 'none';
            }
        }
    };
    
    // Create basketballs for animation
    const createBasketballs = () => {
        const background = document.querySelector('.animated-basketball');
        if (!background) return;
        
        // Adjust number of basketballs based on screen size
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
    
    adjustUIForViewport();
    createBasketballs();
    
    // Handle orientation changes and resize events
    window.addEventListener('orientationchange', adjustUIForViewport);
    window.addEventListener('resize', adjustUIForViewport);
});
</script>
