/**
 * Shop Page Functionality
 */

document.addEventListener("DOMContentLoaded", function () {
  // Initialize product card interactions
  initProductCards();
  
  // Initialize search and filter functionality
  initFilters();
  
  // Quick add-to-cart functionality
  initQuickAddToCart();
});

/**
 * Initialize product card hover effects and interactions
 */
function initProductCards() {
  const productCards = document.querySelectorAll(".product-card");
  
  productCards.forEach((card) => {
    // Add hover effects or additional interaction if needed
    card.addEventListener("mouseenter", function() {
      const button = this.querySelector(".btn-primary");
      if (button) {
        button.style.transform = "translateY(-3px)";
      }
    });
    
    card.addEventListener("mouseleave", function() {
      const button = this.querySelector(".btn-primary");
      if (button) {
        button.style.transform = "translateY(0)";
      }
    });
  });
}

/**
 * Initialize search and filter functionality
 */
function initFilters() {
  const searchForm = document.querySelector(".search-form");
  const clearFiltersBtn = document.querySelector(".btn-link");
  
  // Debounce function to limit how often the filter function runs
  function debounce(func, wait) {
    let timeout;
    return function() {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }
  
  // Auto-submit form when select inputs change
  const selectInputs = searchForm?.querySelectorAll("select");
  if (selectInputs) {
    selectInputs.forEach((select) => {
      select.addEventListener("change", debounce(function() {
        searchForm.submit();
      }, 300));
    });
  }
  
  // Price range validation
  const minPriceInput = searchForm?.querySelector("[name='min_price']");
  const maxPriceInput = searchForm?.querySelector("[name='max_price']");
  
  if (minPriceInput && maxPriceInput) {
    // When min price changes, ensure max price is greater than min price
    minPriceInput.addEventListener("change", function() {
      if (this.value && maxPriceInput.value && parseFloat(this.value) > parseFloat(maxPriceInput.value)) {
        maxPriceInput.value = this.value;
      }
    });
    
    // When max price changes, ensure min price is less than max price
    maxPriceInput.addEventListener("change", function() {
      if (this.value && minPriceInput.value && parseFloat(this.value) < parseFloat(minPriceInput.value)) {
        minPriceInput.value = this.value;
      }
    });
  }
  
  // Handle category pills if present
  const categoryPills = document.querySelectorAll(".category-pill");
  categoryPills.forEach((pill) => {
    pill.addEventListener("click", function(e) {
      if (!this.classList.contains("active")) {
        // Remove active class from all pills
        categoryPills.forEach((p) => p.classList.remove("active"));
        
        // Add active class to clicked pill
        this.classList.add("active");
        
        // If this pill has a data-category attribute, set it in the form
        if (this.dataset.category) {
          const categorySelect = searchForm?.querySelector("[name='category']");
          if (categorySelect) {
            categorySelect.value = this.dataset.category;
            searchForm.submit();
          }
        }
      }
      
      // If pill has no category (All Products), clear the category filter
      if (this.dataset.category === "") {
        const categorySelect = searchForm?.querySelector("[name='category']");
        if (categorySelect) {
          categorySelect.value = "";
          searchForm.submit();
        }
      }
    });
  });
}

/**
 * Add to cart functionality directly from product listings
 */
function initQuickAddToCart() {
  const addToCartForms = document.querySelectorAll(".add-to-cart-form");
  
  addToCartForms.forEach((form) => {
    form.addEventListener("submit", function(e) {
      e.preventDefault();
      
      const productId = this.querySelector("[name='product_id']").value;
      const quantity = this.querySelector("[name='quantity']")?.value || 1;
      
      // Show loading state
      const submitBtn = this.querySelector("button[type='submit']");
      const originalText = submitBtn.textContent;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
      submitBtn.disabled = true;
      
      // Send AJAX request to add item to cart
      fetch('/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=${quantity}`
      })
      .then(response => response.ok ? response.json() : Promise.reject('Network response was not ok'))
      .then(data => {
        // Show success indicator
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Added';
        submitBtn.classList.add("btn-success");
        
        // Update cart count
        updateCartCount();
        
        // Reset button after delay
        setTimeout(() => {
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
          submitBtn.classList.remove("btn-success");
        }, 2000);
      })
      .catch(error => {
        console.error('Error:', error);
        submitBtn.innerHTML = '<i class="fas fa-exclamation-circle"></i> Failed';
        submitBtn.classList.add("btn-danger");
        
        // Reset button after delay
        setTimeout(() => {
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
          submitBtn.classList.remove("btn-danger");
        }, 2000);
      });
    });
  });
}

/**
 * Update the cart count in the header
 */
function updateCartCount() {
  fetch('/cart/count', {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.ok ? response.json() : Promise.reject('Failed to get cart count'))
  .then(data => {
    if (data.count !== undefined) {
      const cartCountElements = document.querySelectorAll('.cart-count');
      cartCountElements.forEach(el => {
        el.textContent = data.count;
        
        // Animate the count update with a small bounce effect
        el.style.transform = 'scale(1.5)';
        setTimeout(() => {
          el.style.transform = 'scale(1)';
        }, 300);
      });
    }
  })
  .catch(error => {
    console.error('Error updating cart count:', error);
  });
}
