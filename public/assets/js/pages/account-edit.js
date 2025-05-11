/**
 * Account Edit Page Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize password toggle functionality
  initPasswordToggles();
  
  // Initialize password strength meter
  initPasswordStrength();
  
  // Initialize password matching validation
  initPasswordMatch();
  
  // Initialize form validation
  initFormValidation();
});

/**
 * Initialize password visibility toggles
 */
function initPasswordToggles() {
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
}

/**
 * Initialize password strength meter
 */
function initPasswordStrength() {
  const passwordInput = document.getElementById('new_password');
  const strengthMeter = document.getElementById('strengthMeter');
  const strengthText = document.getElementById('strengthText');
  
  if (!passwordInput || !strengthMeter || !strengthText) return;
  
  const strengthSpan = strengthText.querySelector('span');
  
  passwordInput.addEventListener('input', function() {
    const value = this.value;
    
    if (!value) {
      strengthMeter.style.width = '0%';
      strengthMeter.className = 'strength-meter-fill';
      strengthSpan.textContent = 'Not set';
      strengthSpan.className = '';
      return;
    }
    
    // Calculate password strength
    let strength = 0;
    
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

/**
 * Initialize password matching validation
 */
function initPasswordMatch() {
  const newPasswordInput = document.getElementById('new_password');
  const confirmPasswordInput = document.getElementById('confirm_password');
  const passwordMatchDisplay = document.getElementById('passwordMatch');
  
  if (!newPasswordInput || !confirmPasswordInput || !passwordMatchDisplay) return;
  
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

/**
 * Initialize form validation
 */
function initFormValidation() {
  const form = document.querySelector('.profile-form');
  
  if (!form) return;
  
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
      currentPasswordField.parentNode.appendChild(errorMsg);
    }
    
    // If passwords don't match
    if (newPassword && newPassword !== confirmPassword) {
      e.preventDefault();
      isValid = false;
      
      const confirmPasswordField = document.getElementById('confirm_password');
      const errorMsg = document.createElement('div');
      errorMsg.className = 'error-message';
      errorMsg.textContent = 'Passwords do not match';
      confirmPasswordField.parentNode.appendChild(errorMsg);
    }
    
    // If new password is too short
    if (newPassword && newPassword.length < 8) {
      e.preventDefault();
      isValid = false;
      
      const newPasswordField = document.getElementById('new_password');
      const errorMsg = document.createElement('div');
      errorMsg.className = 'error-message';
      errorMsg.textContent = 'Password must be at least 8 characters long';
      newPasswordField.parentNode.appendChild(errorMsg);
    }
    
    if (!isValid) {
      return false;
    }
    
    // Show saving indicator
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    }
  });
}
