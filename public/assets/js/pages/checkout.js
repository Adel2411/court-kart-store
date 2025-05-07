/**
 * Checkout Page JavaScript
 */

document.addEventListener("DOMContentLoaded", function () {
  // Credit card form formatting
  initCreditCardFormatting();
  
  // Payment method toggle
  initPaymentMethodToggle();
  
  // Form validation
  initFormValidation();
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
 * Initialize payment method toggle
 */
function initPaymentMethodToggle() {
  const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
  const creditCardForm = document.getElementById("credit-card-form");
  
  if (paymentMethods.length && creditCardForm) {
    paymentMethods.forEach(method => {
      method.addEventListener("change", function() {
        if (this.value === "credit_card") {
          creditCardForm.style.display = "block";
          enableCardValidation(true);
        } else {
          creditCardForm.style.display = "none";
          enableCardValidation(false);
        }
      });
    });
  }
}

/**
 * Enable or disable credit card validation
 */
function enableCardValidation(enable) {
  const cardFields = [
    document.getElementById("card_number"),
    document.getElementById("card_name"),
    document.getElementById("expiry"),
    document.getElementById("cvv")
  ];
  
  cardFields.forEach(field => {
    if (field) {
      if (enable) {
        field.setAttribute("required", "required");
      } else {
        field.removeAttribute("required");
      }
    }
  });
}

/**
 * Initialize form validation
 */
function initFormValidation() {
  const checkoutForm = document.getElementById("checkout-form");
  
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function(e) {
      // Using built-in browser validation via required attributes
      // Additional custom validation can be added here if needed
      
      // Show loading state
      const submitBtn = this.querySelector(".place-order-btn");
      if (submitBtn && this.checkValidity()) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Processing...`;
      }
    });
  }
}
