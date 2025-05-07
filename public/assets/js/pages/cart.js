/**
 * Cart Page Functionality
 */

document.addEventListener("DOMContentLoaded", function() {
  // Initialize quantity controls
  initQuantityControls();
  
  // Initialize remove item confirmation
  initRemoveItemConfirmation();
});

/**
 * Initialize quantity controls in cart page
 */
function initQuantityControls() {
  const quantityForms = document.querySelectorAll('.quantity-form');
  
  quantityForms.forEach(form => {
    const input = form.querySelector('input[name="quantity"]');
    const updateBtn = form.querySelector('button[type="submit"]');
    
    // Store original value to detect changes
    const originalValue = input.value;
    
    // Show update button only when value changes
    input.addEventListener('change', function() {
      if (this.value !== originalValue) {
        updateBtn.classList.add('btn-primary');
      } else {
        updateBtn.classList.remove('btn-primary');
      }
    });
    
    // Add increment/decrement buttons if they don't exist already
    if (!form.querySelector('.quantity-wrapper')) {
      const wrapper = document.createElement('div');
      wrapper.className = 'quantity-wrapper';
      
      const decrementBtn = document.createElement('button');
      decrementBtn.type = 'button';
      decrementBtn.className = 'quantity-btn';
      decrementBtn.textContent = '-';
      decrementBtn.onclick = function() {
        if (input.value > 1) {
          input.value = parseInt(input.value) - 1;
          input.dispatchEvent(new Event('change'));
        }
      };
      
      const incrementBtn = document.createElement('button');
      incrementBtn.type = 'button';
      incrementBtn.className = 'quantity-btn';
      incrementBtn.textContent = '+';
      incrementBtn.onclick = function() {
        if (input.value < parseInt(input.getAttribute('max') || 10)) {
          input.value = parseInt(input.value) + 1;
          input.dispatchEvent(new Event('change'));
        }
      };
      
      // Insert buttons before and after the input
      input.parentNode.insertBefore(wrapper, input);
      wrapper.appendChild(decrementBtn);
      wrapper.appendChild(input);
      wrapper.appendChild(incrementBtn);
    }
  });
}

/**
 * Initialize confirmation dialog for removing items
 */
function initRemoveItemConfirmation() {
  const removeForms = document.querySelectorAll('form[action="/cart/remove"]');
  
  removeForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      if (!confirm('Are you sure you want to remove this item from your cart?')) {
        e.preventDefault();
      }
    });
  });
}

/**
 * Initialize checkout form functionality (run on checkout page)
 */
function initCheckoutForm() {
  const checkoutForm = document.getElementById('checkout-form');
  
  if (!checkoutForm) return;
  
  // Validate form before submission
  checkoutForm.addEventListener('submit', function(e) {
    // Basic form validation
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        isValid = false;
        field.classList.add('is-invalid');
        
        // Add error message if it doesn't exist
        let errorMsg = field.parentNode.querySelector('.error-message');
        if (!errorMsg) {
          errorMsg = document.createElement('div');
          errorMsg.className = 'error-message';
          errorMsg.textContent = 'This field is required';
          field.parentNode.appendChild(errorMsg);
        }
      } else {
        field.classList.remove('is-invalid');
        const errorMsg = field.parentNode.querySelector('.error-message');
        if (errorMsg) {
          errorMsg.remove();
        }
      }
    });
    
    // Payment method validation
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethod) {
      isValid = false;
      const paymentSection = document.querySelector('.payment-methods');
      
      let errorMsg = paymentSection.querySelector('.error-message');
      if (!errorMsg) {
        errorMsg = document.createElement('div');
        errorMsg.className = 'error-message';
        errorMsg.textContent = 'Please select a payment method';
        paymentSection.appendChild(errorMsg);
      }
    }
    
    // Validate credit card fields if credit card is selected
    if (paymentMethod && paymentMethod.value === 'credit_card') {
      const cardFields = document.querySelectorAll('#credit-card-form input');
      cardFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add('is-invalid');
        }
      });
    }
    
    if (!isValid) {
      e.preventDefault();
      return;
    }
    
    // Show processing state on submit button
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing Order...';
    submitBtn.disabled = true;
  });
}

/**
 * Minimum order validation (run on cart page)
 */
function validateMinimumOrder() {
  const checkoutButton = document.querySelector('a[href="/checkout"]');
  const totalPriceElement = document.querySelector('.summary-row.total span:last-child');
  const minimumOrderAmount = 5; // Set minimum order value as needed
  
  if (checkoutButton && totalPriceElement) {
    const totalPrice = parseFloat(totalPriceElement.textContent.replace('$', ''));
    
    if (totalPrice < minimumOrderAmount) {
      checkoutButton.classList.add('disabled');
      checkoutButton.addEventListener('click', function(e) {
        e.preventDefault();
        alert(`Minimum order amount is $${minimumOrderAmount}. Please add more products to your cart.`);
      });
    }
  }
}

// Call this function if we're on the cart page
if (window.location.pathname === '/cart') {
  validateMinimumOrder();
}

// Call this function if we're on the checkout page
if (window.location.pathname === '/checkout') {
  initCheckoutForm();
  
  // Toggle payment method details
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
        
        // Remove any error messages when payment method changes
        const errorMessages = document.querySelectorAll('.payment-methods .error-message');
        errorMessages.forEach(msg => msg.remove());
      });
    });
    
    // Initial state setup
    const checkedMethod = document.querySelector('input[name="payment_method"]:checked');
    if (checkedMethod) {
      creditCardForm.style.display = checkedMethod.value === 'credit_card' ? 'block' : 'none';
      
      if (checkedMethod.value === 'credit_card') {
        // Make credit card fields required initially
        const cardFields = creditCardForm.querySelectorAll('input');
        cardFields.forEach(field => field.setAttribute('required', 'required'));
      }
    }
  }
  
  // Format credit card fields
  const cardNumberInput = document.getElementById('card_number');
  if (cardNumberInput) {
    cardNumberInput.addEventListener('input', function(e) {
      let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
      let formattedValue = '';
      
      for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
          formattedValue += ' ';
        }
        formattedValue += value[i];
      }
      
      this.value = formattedValue.substring(0, 19); // 16 digits + 3 spaces max
    });
  }
  
  const expiryInput = document.getElementById('expiry');
  if (expiryInput) {
    expiryInput.addEventListener('input', function(e) {
      let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
      
      if (value.length > 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
      }
      
      this.value = value;
    });
  }
}
