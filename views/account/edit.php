<h1>Edit Profile</h1>

<div class="account-container">
    <div class="account-sidebar">
        <div class="account-avatar">
            <?php if (!empty($user['profile_image'])) { ?>
                <div class="avatar-container">
                    <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="avatar-image">
                </div>
            <?php } else { ?>
                <div class="avatar-image">
                    <?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?>
                </div>
            <?php } ?>
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p><?= htmlspecialchars($user['email']) ?></p>
        </div>
        
        <div class="account-menu">
            <a href="/account" class="account-menu-item">Account Overview</a>
            <a href="/orders" class="account-menu-item">My Orders</a>
            <a href="/account/edit" class="account-menu-item active">Edit Profile</a>
            <a href="/logout" class="account-menu-item">Logout</a>
        </div>
    </div>
    
    <div class="account-content">
        <?php if (isset($error)) { ?>
            <div class="alert alert-error">
                <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
                <div class="alert-content"><?= htmlspecialchars($error) ?></div>
            </div>
        <?php } ?>
        
        <?php if (isset($success)) { ?>
            <div class="alert alert-success">
                <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
                <div class="alert-content"><?= htmlspecialchars($success) ?></div>
            </div>
        <?php } ?>
        
        <div class="account-card">
            <div class="account-card-header">
                <h3><i class="fas fa-user-edit"></i> Edit Your Profile</h3>
            </div>
            <div class="account-card-body">
                <form action="/account/update" method="post" class="profile-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    
                    <div class="form-section">
                        <h4>Personal Information</h4>
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4>Change Password</h4>
                        <p class="form-note">Leave the password fields blank if you don't want to change it.</p>
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="current_password" name="current_password" class="form-control">
                                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password" id="new_password" name="new_password" class="form-control">
                                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-meter">
                                    <div class="strength-meter-fill" id="strengthMeter"></div>
                                </div>
                                <div class="strength-text" id="strengthText">Password strength: <span>Not set</span></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-check-circle input-icon"></i>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-match" id="passwordMatch"></div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="/account" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
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
    
    // Password strength meter
    const passwordInput = document.getElementById('new_password');
    const strengthMeter = document.getElementById('strengthMeter');
    const strengthText = document.getElementById('strengthText');
    const strengthSpan = strengthText.querySelector('span');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const value = this.value;
            let strength = 0;
            
            if (!value) {
                strengthMeter.style.width = '0%';
                strengthMeter.className = 'strength-meter-fill';
                strengthSpan.textContent = 'Not set';
                strengthSpan.className = '';
                return;
            }
            
            // Length check
            if (value.length >= 8) {
                strength += 20;
            }
            
            // Uppercase check
            if (/[A-Z]/.test(value)) {
                strength += 20;
            }
            
            // Lowercase check
            if (/[a-z]/.test(value)) {
                strength += 20;
            }
            
            // Number check
            if (/[0-9]/.test(value)) {
                strength += 20;
            }
            
            // Special character check
            if (/[^A-Za-z0-9]/.test(value)) {
                strength += 20;
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
    }
    
    // Password matching validation
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordMatchDisplay = document.getElementById('passwordMatch');
    
    if (confirmPasswordInput && newPasswordInput && passwordMatchDisplay) {
        function checkPasswordMatch() {
            if (confirmPasswordInput.value === '') {
                passwordMatchDisplay.textContent = '';
                passwordMatchDisplay.className = 'password-match';
            } else if (newPasswordInput.value === confirmPasswordInput.value) {
                passwordMatchDisplay.textContent = 'Passwords match';
                passwordMatchDisplay.className = 'password-match match';
            } else {
                passwordMatchDisplay.textContent = 'Passwords do not match';
                passwordMatchDisplay.className = 'password-match no-match';
            }
        }
        
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        newPasswordInput.addEventListener('input', function() {
            if (confirmPasswordInput.value !== '') {
                checkPasswordMatch();
            }
        });
    }
    
    // Form validation
    const form = document.querySelector('.profile-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const currentPassword = document.getElementById('current_password').value;
            
            // Clear previous errors
            const errorMessages = form.querySelectorAll('.error-message');
            errorMessages.forEach(msg => msg.remove());
            
            let isValid = true;
            
            // If trying to set new password without current password
            if ((newPassword || confirmPassword) && !currentPassword) {
                e.preventDefault();
                isValid = false;
                
                const currentPasswordField = document.getElementById('current_password');
                const errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                errorMsg.textContent = 'Current password is required when setting a new password';
                currentPasswordField.parentNode.parentNode.appendChild(errorMsg);
            }
            
            // If passwords don't match
            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                isValid = false;
                
                const confirmPasswordField = document.getElementById('confirm_password');
                const errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                errorMsg.textContent = 'Passwords do not match';
                confirmPasswordField.parentNode.parentNode.appendChild(errorMsg);
            }
            
            // If new password is too short
            if (newPassword && newPassword.length < 8) {
                e.preventDefault();
                isValid = false;
                
                const newPasswordField = document.getElementById('new_password');
                const errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                errorMsg.textContent = 'Password must be at least 8 characters long';
                newPasswordField.parentNode.parentNode.appendChild(errorMsg);
            }
            
            if (!isValid) {
                return false;
            }
            
            // Show saving indicator
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.textContent;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                
                // Restore button after 10 seconds in case of network issues
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                }, 10000);
            }
        });
    }
});
</script>
