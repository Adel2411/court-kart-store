/**
 * Authentication pages functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Password visibility toggle
  initPasswordToggle();
  
  // Password strength meter
  initPasswordStrength();
  
  // Form validation
  initFormValidation();
});

/**
 * Initialize password visibility toggle
 */
function initPasswordToggle() {
  const toggleButtons = document.querySelectorAll('.toggle-password');
  
  toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
      const input = this.parentElement.querySelector('input');
      const icon = this.querySelector('i');
      
      // Toggle between password and text type
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
 * Initialize password strength indicator
 */
function initPasswordStrength() {
  const passwordInput = document.getElementById('password');
  const strengthIndicator = document.getElementById('passwordStrength');
  
  if (passwordInput && strengthIndicator) {
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      let strength = 0;
      
      // Create indicators only once
      if (!strengthIndicator.querySelector('.strength-bar')) {
        const strengthBars = document.createElement('div');
        strengthBars.className = 'strength-bars';
        
        for (let i = 0; i < 4; i++) {
          const bar = document.createElement('div');
          bar.className = 'strength-bar';
          strengthBars.appendChild(bar);
        }
        
        const strengthText = document.createElement('span');
        strengthText.className = 'strength-text';
        
        strengthIndicator.appendChild(strengthBars);
        strengthIndicator.appendChild(strengthText);
      }
      
      const bars = strengthIndicator.querySelectorAll('.strength-bar');
      const strengthText = strengthIndicator.querySelector('.strength-text');
      
      // Calculate password strength
      if (password.length > 8) strength++;
      if (password.match(/[A-Z]/)) strength++;
      if (password.match(/[0-9]/)) strength++;
      if (password.match(/[^A-Za-z0-9]/)) strength++;
      
      // Clear all classes
      bars.forEach(bar => {
        bar.className = 'strength-bar';
      });
      
      // Set appropriate classes based on strength
      for (let i = 0; i < strength; i++) {
        if (bars[i]) {
          switch(strength) {
            case 1:
              bars[i].classList.add('weak');
              break;
            case 2:
              bars[i].classList.add('fair');
              break;
            case 3:
              bars[i].classList.add('good');
              break;
            case 4:
              bars[i].classList.add('strong');
              break;
          }
        }
      }
      
      // Update text
      if (password === '') {
        strengthText.textContent = '';
      } else {
        switch(strength) {
          case 1:
            strengthText.textContent = 'Weak';
            strengthText.className = 'strength-text text-weak';
            break;
          case 2:
            strengthText.textContent = 'Fair';
            strengthText.className = 'strength-text text-fair';
            break;
          case 3:
            strengthText.textContent = 'Good';
            strengthText.className = 'strength-text text-good';
            break;
          case 4:
            strengthText.textContent = 'Strong';
            strengthText.className = 'strength-text text-strong';
            break;
        }
      }
    });
  }
}

/**
 * Initialize form validation
 */
function initFormValidation() {
  const registerForm = document.querySelector('.auth-form');
  const confirmPasswordInput = document.getElementById('confirm_password');
  const passwordInput = document.getElementById('password');
  
  if (registerForm && confirmPasswordInput && passwordInput) {
    registerForm.addEventListener('submit', function(e) {
      if (confirmPasswordInput.value !== passwordInput.value) {
        e.preventDefault();
        
        // Create error message if it doesn't exist
        let errorMessage = document.querySelector('.password-match-error');
        if (!errorMessage) {
          errorMessage = document.createElement('div');
          errorMessage.className = 'auth-alert auth-alert-error password-match-error';
          errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
          
          // Insert after confirm password group
          const formGroup = confirmPasswordInput.closest('.form-group');
          formGroup.parentNode.insertBefore(errorMessage, formGroup.nextSibling);
        }
      }
    });
    
    // Real-time validation for password match
    if (confirmPasswordInput && passwordInput) {
      confirmPasswordInput.addEventListener('input', function() {
        const errorMessage = document.querySelector('.password-match-error');
        
        if (this.value !== passwordInput.value && this.value !== '') {
          if (!errorMessage) {
            const newErrorMessage = document.createElement('div');
            newErrorMessage.className = 'auth-alert auth-alert-error password-match-error';
            newErrorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
            
            const formGroup = this.closest('.form-group');
            formGroup.appendChild(newErrorMessage);
          }
        } else if (errorMessage) {
          errorMessage.remove();
        }
      });
    }
  }
}
