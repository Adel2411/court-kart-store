/**
 * Checkout Page Functionality
 */

document.addEventListener("DOMContentLoaded", function() {
  // Initialize form validation
  initFormValidation();
  
  // Initialize credit card formatting
  initCreditCardFormatting();
  
  // Initialize payment method switcher
  initPaymentMethodSwitcher();
});

/**
 * Initialize credit card input formatting
 */
function initCreditCardFormatting() {
  // Format card number with spaces
  const cardInput = document.getElementById("card_number");
  if (cardInput) {
    cardInput.addEventListener("input", function(e) {
      let value = e.target.value.replace(/\s+/g, "").replace(/[^0-9]/gi, "");
      let formattedValue = "";
      
      for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
          formattedValue += " ";
        }
        formattedValue += value[i];
      }
      
      // Limit to 19 characters (16 digits + 3 spaces)
      if (formattedValue.length <= 19) {
        e.target.value = formattedValue;
      } else {
        e.target.value = formattedValue.substring(0, 19);
      }
    });
  }
  
  // Format expiry date
  const expiryInput = document.getElementById("expiry");
  if (expiryInput) {
    expiryInput.addEventListener("input", function(e) {
      let value = e.target.value.replace(/\s+/g, "").replace(/[^0-9]/gi, "");
      
      if (value.length > 2) {
        value = value.substring(0, 2) + "/" + value.substring(2, 4);
      }
      
      e.target.value = value;
    });
  }
  
  // Format CVV to allow only numbers
  const cvvInput = document.getElementById("cvv");
  if (cvvInput) {
    cvvInput.addEventListener("input", function(e) {
      e.target.value = e.target.value.replace(/\D/g, "").substring(0, 4);
    });
  }
}

/**
 * Initialize form validation
 */
function initFormValidation() {
  const checkoutForm = document.getElementById("checkout-form");
  
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function(e) {
      // Using built-in browser validation via required attributes
      if (!this.checkValidity()) {
        e.preventDefault();
        return false;
      }
      
      // Additional payment method validation
      const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
      if (!paymentMethod) {
        e.preventDefault();
        showError("Please select a payment method");
        return false;
      }
      
      // Credit card validation if credit card is selected
      if (paymentMethod.value === "credit_card") {
        const cardNumber = document.getElementById("card_number");
        const cardName = document.getElementById("card_name");
        const expiry = document.getElementById("expiry");
        const cvv = document.getElementById("cvv");
        
        if (!validateCreditCard(cardNumber.value)) {
          e.preventDefault();
          showError("Please enter a valid credit card number");
          cardNumber.focus();
          return false;
        }
        
        if (!validateExpiry(expiry.value)) {
          e.preventDefault();
          showError("Please enter a valid expiration date (MM/YY)");
          expiry.focus();
          return false;
        }
        
        if (!validateCVV(cvv.value)) {
          e.preventDefault();
          showError("Please enter a valid CVV code");
          cvv.focus();
          return false;
        }
      }
      
      // Show loading state
      const submitBtn = this.querySelector(".place-order-btn");
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Processing...`;
      }
      
      // Form is valid, continue with submission
      return true;
    });
  }
}

/**
 * Initialize payment method switcher
 */
function initPaymentMethodSwitcher() {
  const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
  const creditCardForm = document.getElementById('credit-card-form');
  
  if (paymentMethods.length > 0 && creditCardForm) {
    paymentMethods.forEach(method => {
      method.addEventListener('change', function() {
        if (this.value === 'credit_card') {
          creditCardForm.style.display = 'block';
          
          // Make credit card fields required
          const cardFields = creditCardForm.querySelectorAll('input');
          cardFields.forEach(field => field.setAttribute('required', 'required'));
        } else {
          creditCardForm.style.display = 'none';
          
          // Remove required attribute from credit card fields
          const cardFields = creditCardForm.querySelectorAll('input');
          cardFields.forEach(field => field.removeAttribute('required'));
        }
      });
    });
    
    // Initial state setup
    const checkedMethod = document.querySelector('input[name="payment_method"]:checked');
    if (checkedMethod) {
      creditCardForm.style.display = checkedMethod.value === 'credit_card' ? 'block' : 'none';
    }
  }
}

/**
 * Display error message
 */
function showError(message) {
  // Remove any existing error messages
  const existingErrors = document.querySelectorAll('.form-error');
  existingErrors.forEach(error => error.remove());
  
  // Create error element
  const errorDiv = document.createElement('div');
  errorDiv.className = 'form-error';
  errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
  
  // Insert at the top of the form
  const form = document.getElementById("checkout-form");
  form.insertBefore(errorDiv, form.firstChild);
  
  // Scroll to error
  errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/**
 * Validate credit card number (basic Luhn algorithm)
 */
function validateCreditCard(cardNumber) {
  const sanitized = cardNumber.replace(/\D/g, '');
  
  // Basic length check
  if (sanitized.length < 13 || sanitized.length > 19) {
    return false;
  }
  
  // Basic Luhn algorithm for credit card validation
  let sum = 0;
  let double = false;
  
  for (let i = sanitized.length - 1; i >= 0; i--) {
    let digit = parseInt(sanitized.charAt(i));
    
    if (double) {
      digit *= 2;
      if (digit > 9) digit -= 9;
    }
    
    sum += digit;
    double = !double;
  }
  
  return sum % 10 === 0;
}

/**
 * Validate expiry date format MM/YY and not expired
 */
function validateExpiry(expiry) {
  // Check format MM/YY
  if (!/^\d{2}\/\d{2}$/.test(expiry)) {
    return false;
  }
  
  const [month, year] = expiry.split('/');
  const currentDate = new Date();
  const currentYear = currentDate.getFullYear() % 100; // Get last 2 digits
  const currentMonth = currentDate.getMonth() + 1; // getMonth() is 0-indexed
  
  // Convert to numbers
  const expiryMonth = parseInt(month, 10);
  const expiryYear = parseInt(year, 10);
  
  // Validate month range
  if (expiryMonth < 1 || expiryMonth > 12) {
    return false;
  }
  
  // Check if card is expired
  if (expiryYear < currentYear || (expiryYear === currentYear && expiryMonth < currentMonth)) {
    return false;
  }
  
  return true;
}

/**
 * Validate CVV code (3-4 digits)
 */
function validateCVV(cvv) {
  return /^\d{3,4}$/.test(cvv);
}
