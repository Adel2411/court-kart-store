document.addEventListener("DOMContentLoaded", function () {
  // DOM element references
  const filterToggle = document.querySelector(".filters-toggle");
  const mobileFilterToggle = document.querySelector(".mobile-filters-toggle");
  const filterClose = document.querySelector(".filters-close");
  const filtersElement = document.querySelector(".filters");
  const filtersBackdrop = document.querySelector(".filters-backdrop");
  const quickViewModal = document.getElementById("quickViewModal");
  const quickViewContent = document.getElementById("quickViewContent");
  const cartNotification = document.getElementById("cartNotification");
  const minPriceInput = document.getElementById("min-price");
  const maxPriceInput = document.getElementById("max-price");
  const productsGrid = document.getElementById("products-grid");
  const filtersForm = document.getElementById("filters-form");
  const wishlistFilterToggle = document.getElementById("wishlistFilterToggle");

  // Initialize the page components
  initFilterAccordions();
  initMobileFilters();
  initActiveFilters();
  initPriceRange();
  initClearFilters();
  initViewSwitcher();
  initQuickView();
  initModalHandlers();
  initWishlistButtons();
  initWishlistFilterToggle(); // Add this new function call

  /**
   * Initialize filter accordion functionality
   */
  function initFilterAccordions() {
    document.querySelectorAll(".filter-title").forEach((title) => {
      title.addEventListener("click", function () {
        const details = this.parentNode;
        const isOpen = details.hasAttribute("open");

        const icon = this.querySelector(".toggle-icon");
        if (icon) {
          icon.className = isOpen
            ? "fas fa-chevron-down toggle-icon"
            : "fas fa-chevron-up toggle-icon";
        }
      });
    });
  }

  /**
   * Initialize mobile filters functionality
   */
  function initMobileFilters() {
    const mobileFilterToggle = document.querySelector('.mobile-filters-toggle');
    const filtersElement = document.querySelector('.filters');
    const filtersBackdrop = document.querySelector('.filters-backdrop');
    const filterClose = document.querySelector('.filters-close');
    
    if (mobileFilterToggle) {
      mobileFilterToggle.addEventListener('click', function () {
        filtersElement.classList.add('active');
        filtersBackdrop.style.display = 'block';
        setTimeout(() => {
          filtersBackdrop.classList.add('active');
        }, 10);
        document.body.style.overflow = 'hidden'; // Prevent scrolling
      });
    }

    // Close button for filters - hide filters sidebar
    if (filterClose) {
      filterClose.addEventListener('click', closeFilters);
    }

    // Backdrop click also closes filters
    if (filtersBackdrop) {
      filtersBackdrop.addEventListener('click', closeFilters);
    }

    // Close filters function
    function closeFilters() {
      filtersElement.classList.remove('active');
      filtersBackdrop.classList.remove('active');
      setTimeout(() => {
        filtersBackdrop.style.display = 'none';
      }, 300);
      document.body.style.overflow = '';
    }

    // Close filters when window is resized to desktop size
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 992 && filtersElement.classList.contains('active')) {
        closeFilters();
      }
    });
  }

  /**
   * Initialize active filters functionality
   */
  function initActiveFilters() {
    const activeFilters = document.getElementById("active-filters");

    function updateActiveFilters() {
      activeFilters.innerHTML = "";
      let hasFilters = false;

      const searchValue = document.getElementById("search").value.trim();
      if (searchValue) {
        const tag = createFilterTag("search", searchValue, "fa-search");
        activeFilters.appendChild(tag);
        hasFilters = true;
      }

      document
        .querySelectorAll('input[name="category[]"]:checked')
        .forEach((input) => {
          const tag = createFilterTag(
            "category",
            input.value,
            "fa-tag",
            input.value,
          );
          activeFilters.appendChild(tag);
          hasFilters = true;
        });

      const minPrice = parseInt(minPriceInput.value);
      const maxPrice = parseInt(maxPriceInput.value);
      if (minPrice > 0 || maxPrice < 1000) {
        const tag = createFilterTag(
          "price",
          `$${minPrice} - $${maxPrice}`,
          "fa-dollar-sign",
        );
        activeFilters.appendChild(tag);
        hasFilters = true;
      }

      activeFilters.style.display = hasFilters ? "flex" : "none";
    }

    function createFilterTag(type, text, icon, value = null) {
      const tag = document.createElement("div");
      tag.className = "filter-tag";

      tag.innerHTML = `
                <i class="fas ${icon}"></i> 
                <span>${text}</span>
                <button type="button" class="remove-tag" data-filter="${type}" ${value ? `data-value="${value}"` : ""} aria-label="Remove filter">
                    <i class="fas fa-times"></i>
                </button>
            `;

      return tag;
    }

    // Initialize active filters
    updateActiveFilters();

    document
      .querySelectorAll("#filters-form input, #filters-form select")
      .forEach((input) => {
        input.addEventListener("change", updateActiveFilters);
      });

    // Remove filter tag handler
    document.addEventListener("click", function (e) {
      if (e.target.closest(".remove-tag")) {
        const button = e.target.closest(".remove-tag");
        const filterType = button.getAttribute("data-filter");

        if (filterType === "search") {
          document.getElementById("search").value = "";
        } else if (filterType === "category") {
          const value = button.getAttribute("data-value");
          const checkbox = document.querySelector(
            `input[name="category[]"][value="${value}"]`,
          );
          if (checkbox) {
            checkbox.checked = false;
          }
          filtersForm.submit();
        } else if (filterType === "price") {
          minPriceInput.value = 0;
          maxPriceInput.value = 1000;
        }

        updateActiveFilters();
      }
    });
  }

  /**
   * Initialize price range inputs
   */
  function initPriceRange() {
    if (minPriceInput && maxPriceInput) {
      // Ensure min price doesn't exceed max price
      minPriceInput.addEventListener("change", function () {
        if (parseInt(this.value) > parseInt(maxPriceInput.value)) {
          this.value = maxPriceInput.value;
        }
      });

      maxPriceInput.addEventListener("change", function () {
        if (parseInt(this.value) < parseInt(minPriceInput.value)) {
          this.value = minPriceInput.value;
        }
      });
    }
  }

  /**
   * Initialize clear filters button
   */
  function initClearFilters() {
    const clearFiltersBtn = document.getElementById("clear-filters");
    if (clearFiltersBtn) {
      clearFiltersBtn.addEventListener("click", function () {
        document.getElementById("search").value = "";

        document
          .querySelectorAll('input[name="category[]"]')
          .forEach((checkbox) => {
            checkbox.checked = false;
          });

        minPriceInput.value = 0;
        maxPriceInput.value = 500;

        document.querySelectorAll('input[name="sort"]')[0].checked = true;

        updateActiveFilters();
      });
    }
  }

  /**
   * Initialize view switcher between grid and list
   */
  function initViewSwitcher() {
    const viewButtons = document.querySelectorAll(".view-btn");
    const productsGrid = document.getElementById("products-grid");

    if (viewButtons.length && productsGrid) {
      viewButtons.forEach((button) => {
        button.addEventListener("click", function () {
          viewButtons.forEach((btn) => btn.classList.remove("active"));

          this.classList.add("active");

          const viewType = this.getAttribute("data-view");
          productsGrid.className = "";
          productsGrid.classList.add("product-" + viewType);
        });
      });
    }
  }

  /**
   * Initialize quick view functionality
   * Updated to use the modal component
   */
  function initQuickView() {
    document.querySelectorAll('[data-action="quickview"]').forEach((button) => {
      button.addEventListener("click", function () {
        const productId = this.getAttribute("data-id");

        quickViewContent.innerHTML =
          '<div class="loader"><i class="fas fa-basketball-ball fa-spin"></i><p>Loading product details...</p></div>';

        if (window.CourtKartModals && window.CourtKartModals.quickViewModal) {
          window.CourtKartModals.quickViewModal.open();
        } else {
          quickViewModal.setAttribute("aria-hidden", "false");
        }

        fetchProductDetails(productId);
      });
    });
  }

  /**
   * Fetch product details for quick view
   * @param {string} productId The ID of the product to fetch
   */
  function fetchProductDetails(productId) {
    fetch(`/api/products/${productId}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        renderQuickView(data);
      })
      .catch((error) => {
        console.error("Error fetching product details:", error);
        quickViewContent.innerHTML = `
                    <div class="error">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Failed to load product details</p>
                        <p class="error-message">${error.message}</p>
                        <button class="btn outline" data-close>Close</button>
                    </div>`;
      });
  }

  /**
   * Render the quick view modal with product data
   * @param {object} data The product data
   */
  function renderQuickView(data) {
    let stockStatus = "";
    if (data.stock > 10) {
      stockStatus = `<span class="stock-status in-stock"><i class="fas fa-check-circle"></i> In Stock (${data.stock} available)</span>`;
    } else if (data.stock > 0) {
      stockStatus = `<span class="stock-status low-stock"><i class="fas fa-exclamation-circle"></i> Low Stock (${data.stock} left)</span>`;
    } else {
      stockStatus =
        '<span class="stock-status out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>';
    }

    const rating = data.rating || 0;
    const reviewCount = data.reviews_count || 0;
    let stars = "";
    for (let i = 1; i <= 5; i++) {
      stars += `<i class="${i <= rating ? "fas" : "far"} fa-star"></i>`;
    }

    // Create badges for special product statuses
    let badges = "";
    if (data.is_new) {
      badges += '<span class="badge new">New</span>';
    }
    if (data.discount > 0) {
      badges += `<span class="badge sale">-${data.discount_percent || Math.round(data.discount * 100)}%</span>`;
    }

    let priceDisplay = `<div class="quick-view-price">$${parseFloat(data.price).toFixed(2)}</div>`;
    if (data.discount > 0) {
      priceDisplay = `
                <div class="quick-view-price">
                    <span class="current-price">$${parseFloat(data.price).toFixed(2)}</span>
                    <span class="original-price">$${parseFloat(data.original_price).toFixed(2)}</span>
                </div>`;
    }

    // Ensure description is not undefined
    const description = data.description || "No description available";

    quickViewContent.innerHTML = `
            <div class="quick-view">
                <div class="quick-view-image">
                    ${badges}
                    <img src="${data.image_url}" alt="${data.name}" onerror="this.src='/assets/images/placeholder-product.png'">
                </div>
                
                <div class="quick-view-details">
                    <h3>${data.name}</h3>
                    
                    <div class="product-meta">
                        <span class="product-category"><i class="fas fa-tag"></i> ${data.category}</span>
                        <div class="product-rating">
                            ${stars}
                            <span>(${reviewCount})</span>
                        </div>
                    </div>
                    
                    ${priceDisplay}
                    
                    <div class="availability">
                        ${stockStatus}
                    </div>
                    
                    <div class="product-description">
                        <p>${description}</p>
                    </div>
                    
                    <form action="/cart/add" method="post" class="quick-view-form" id="quickAddToCartForm">
                        <input type="hidden" name="product_id" value="${data.id}">
                        
                        <div class="quantity-control">
                            <label for="quantity">Quantity</label>
                            <div class="quantity-wrapper">
                                <button type="button" class="quantity-btn" data-action="decrease">−</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="${data.stock}" ${data.stock < 1 ? "disabled" : ""}>
                                <button type="button" class="quantity-btn" data-action="increase" ${data.stock < 1 ? "disabled" : ""}>+</button>
                            </div>
                        </div>
                        
                        <div class="quick-view-actions">
                            <button type="submit" class="btn primary" ${data.stock < 1 ? "disabled" : ""}>
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </form>
                    
                    <div class="product-links">
                        <a href="/shop/product/${data.id}" class="view-details">
                            <i class="fas fa-external-link-alt"></i> View Full Details
                        </a>
                    </div>
                </div>
            </div>
        `;

    // Initialize quantity controls
    initQuantityControls();

    // Set up Ajax form submission
    setupQuickAddToCart();
    
    // Initialize wishlist button in quick view modal
    const wishlistBtn = quickViewContent.querySelector('[data-action="wishlist"]');
    if (wishlistBtn && window.WishlistManager) {
        // Check initial wishlist status
        window.WishlistManager.checkProduct(data.id, function(inWishlist) {
            if (inWishlist) {
                wishlistBtn.classList.add('in-wishlist');
                wishlistBtn.querySelector('i').classList.remove('far');
                wishlistBtn.querySelector('i').classList.add('fas');
            }
        });
        
        // Add click event
        wishlistBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.WishlistManager.toggleProduct(data.id, function(isAdded) {
                if (isAdded) {
                    wishlistBtn.classList.add('in-wishlist');
                    wishlistBtn.querySelector('i').classList.remove('far');
                    wishlistBtn.querySelector('i').classList.add('fas');
                    
                    // Also update the button in the product grid
                    const gridBtn = document.querySelector(`.product-card [data-action="wishlist"][data-id="${data.id}"]`);
                    if (gridBtn) {
                        gridBtn.classList.add('in-wishlist');
                        gridBtn.querySelector('i').classList.remove('far');
                        gridBtn.querySelector('i').classList.add('fas');
                    }
                } else {
                    wishlistBtn.classList.remove('in-wishlist');
                    wishlistBtn.querySelector('i').classList.remove('fas');
                    wishlistBtn.querySelector('i').classList.add('far');
                    
                    // Also update the button in the product grid
                    const gridBtn = document.querySelector(`.product-card [data-action="wishlist"][data-id="${data.id}"]`);
                    if (gridBtn) {
                        gridBtn.classList.remove('in-wishlist');
                        gridBtn.querySelector('i').classList.remove('fas');
                        gridBtn.querySelector('i').classList.add('far');
                    }
                }
            });
        });
    }
  }

  /**
   * Initialize modal close handlers
   * Updated to use the modal component
   */
  function initModalHandlers() {
    if (!window.CourtKartModals || !window.CourtKartModals.quickViewModal) {
      document.querySelectorAll("[data-close]").forEach((element) => {
        element.addEventListener("click", function () {
          quickViewModal.setAttribute("aria-hidden", "true");
        });
      });

      // Close modal on escape key
      document.addEventListener("keydown", function (e) {
        if (
          e.key === "Escape" &&
          quickViewModal.getAttribute("aria-hidden") === "false"
        ) {
          quickViewModal.setAttribute("aria-hidden", "true");
        }
      });
    }

    // Add close button functionality for cart notification
    const cartNotification = document.getElementById("cartNotification");
    const closeBtn = cartNotification.querySelector(".toast-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", function () {
        cartNotification.classList.remove("active");
      });
    }
  }

  /**
   * Initialize quantity control buttons
   */
  function initQuantityControls() {
    document.querySelectorAll(".quantity-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const input = this.parentNode.querySelector("input");
        const currentValue = parseInt(input.value);
        const action = this.getAttribute("data-action");

        if (action === "decrease" && currentValue > parseInt(input.min)) {
          input.value = currentValue - 1;
        } else if (
          action === "increase" &&
          currentValue < parseInt(input.max)
        ) {
          input.value = currentValue + 1;
        }
      });
    });
  }

  /**
   * Setup Ajax cart submission for quick view
   */
  function setupQuickAddToCart() {
    const form = document.getElementById("quickAddToCartForm");
    if (form) {
      form.addEventListener("submit", function (e) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');

        // Show loading state
        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Adding...';
        submitBtn.disabled = true;

        // Update cart count in UI
        const cartCountElem = document.querySelector(".cart-count");
        if (cartCountElem) {
          const currentCount = parseInt(cartCountElem.textContent) || 0;
          cartCountElem.textContent = currentCount + 1;

          // Add animation
          cartCountElem.classList.add("pulse");
          setTimeout(() => {
            cartCountElem.classList.remove("pulse");
          }, 500);
        }

        // Close modal
        quickViewModal.setAttribute("aria-hidden", "true");

        // Show success notification
        showCartNotification();

        // Traditional form submission
        setTimeout(() => {
          form.removeEventListener("submit", arguments.callee);
          form.submit();
        }, 500);
      });
    }
  }

  /**
   * Show cart notification
   */
  function showCartNotification() {
    cartNotification.classList.add("active");

    // Auto-hide notification after 3 seconds
    setTimeout(() => {
      cartNotification.classList.remove("active");
    }, 3000);
  }

  /**
   * Update cart count in header - removing fetch API call
   */
  function updateCartCount() {
    const cartCountElem = document.querySelector(".cart-count");
    if (cartCountElem) {
      const currentCount = parseInt(cartCountElem.textContent) || 0;
      cartCountElem.textContent = currentCount + 1;

      // Add animation
      cartCountElem.classList.add("pulse");
      setTimeout(() => {
        cartCountElem.classList.remove("pulse");
      }, 500);
    }
  }

  /**
   * Update active filters - defined as global function
   * to be called from various event handlers
   */
  function updateActiveFilters() {
    const activeFilters = document.getElementById("active-filters");
    activeFilters.innerHTML = "";
    let hasFilters = false;

    // Search filter tag
    const searchValue = document.getElementById("search").value.trim();
    if (searchValue) {
      const tag = createFilterTag("search", searchValue, "fa-search");
      activeFilters.appendChild(tag);
      hasFilters = true;
    }

    // Category filter tags
    document
      .querySelectorAll('input[name="category[]"]:checked')
      .forEach((input) => {
        const tag = createFilterTag(
          "category",
          input.value,
          "fa-tag",
          input.value,
        );
        activeFilters.appendChild(tag);
        hasFilters = true;
      });

    // Price range filter tag
    const minPrice = parseInt(minPriceInput.value);
    const maxPrice = parseInt(maxPriceInput.value);
    if (minPrice > 0 || maxPrice < 1000) {
      const tag = createFilterTag(
        "price",
        `$${minPrice} - $${maxPrice}`,
        "fa-dollar-sign",
      );
      activeFilters.appendChild(tag);
      hasFilters = true;
    }

    // Show/hide the active filters container
    activeFilters.style.display = hasFilters ? "flex" : "none";
  }

  /**
   * Create a filter tag element
   */
  function createFilterTag(type, text, icon, value = null) {
    const tag = document.createElement("div");
    tag.className = "filter-tag";

    tag.innerHTML = `
            <i class="fas ${icon}"></i> 
            <span>${text}</span>
            <button type="button" class="remove-tag" data-filter="${type}" ${value ? `data-value="${value}"` : ""} aria-label="Remove filter">
                <i class="fas fa-times"></i>
            </button>
        `;

    return tag;
  }

  /**
   * Initialize wishlist button functionality
   */
  function initWishlistButtons() {
    const wishlistButtons = document.querySelectorAll('.product-card .quick-action-btn[data-action="wishlist"]');
    
    if (window.WishlistManager) {
        wishlistButtons.forEach(button => {
            const productId = button.dataset.id;
            
            // Check initial wishlist status
            window.WishlistManager.checkProduct(productId, function(inWishlist) {
                if (inWishlist) {
                    button.classList.add('in-wishlist');
                    button.querySelector('i').classList.remove('far');
                    button.querySelector('i').classList.add('fas');
                }
            });
            
            // Add click event for toggling wishlist
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                window.WishlistManager.toggleProduct(productId, function(isAdded) {
                    if (isAdded) {
                        button.classList.add('in-wishlist');
                        button.querySelector('i').classList.remove('far');
                        button.querySelector('i').classList.add('fas');
                    } else {
                        button.classList.remove('in-wishlist');
                        button.querySelector('i').classList.remove('fas');
                        button.querySelector('i').classList.add('far');
                    }
                });
            });
        });
    } else {
        document.querySelectorAll('[data-action="wishlist"]').forEach((button) => {
          button.addEventListener("click", function(e) {
            e.preventDefault();
            const productId = this.getAttribute("data-id");
            const icon = this.querySelector("i");

            // Check if user is logged in
            if (!isUserLoggedIn()) {
              window.location.href = "/login?redirect=" + window.location.pathname;
              return;
            }

            const formData = new FormData();
            formData.append("product_id", productId);

            // Show loading state
            icon.className = "fas fa-spinner fa-spin";
            this.disabled = true;

            // Send AJAX request
            fetch("/wishlist/add", {
              method: "POST",
              body: formData,
              headers: {
                "X-Requested-With": "XMLHttpRequest",
              },
            })
              .then((response) => response.json())
              .then((data) => {
                this.disabled = false;

                if (data.success) {
                  if (data.is_added) {
                    // Added to wishlist
                    icon.className = "fas fa-heart";
                    icon.style.color = "#e74c3c";
                    showToast("success", "Added to your wishlist");
                  } else {
                    // Removed from wishlist
                    icon.className = "far fa-heart";
                    icon.style.color = "";
                    showToast("info", "Removed from your wishlist");
                  }
                } else {
                  // Error occurred
                  icon.className = "far fa-heart";
                  showToast("error", data.message || "An error occurred");
                }
              })
              .catch((error) => {
                console.error("Error toggling wishlist:", error);
                this.disabled = false;
                icon.className = "far fa-heart";
                showToast("error", "An error occurred");
              });
          });
        });
    }
  }

  /**
   * Initialize wishlist filter toggle
   * This adds a fallback in case the dedicated wishlist-filter.js doesn't load
   */
  function initWishlistFilterToggle() {
    const wishlistFilterToggle = document.getElementById('wishlistFilterToggle');
    
    if (wishlistFilterToggle && !wishlistFilterToggle.hasInitialized) {
      // Check if already has event listeners (from wishlist-filter.js)
      wishlistFilterToggle.hasInitialized = true;
      
      // Check URL to see if wishlist filter is already active
      const urlParams = new URLSearchParams(window.location.search);
      const isWishlistActive = urlParams.get('wishlist_only') === '1';
      
      // Set initial state based on URL
      if (isWishlistActive) {
        wishlistFilterToggle.classList.add('active');
        if (wishlistFilterToggle.querySelector('i')) {
          wishlistFilterToggle.querySelector('i').classList.remove('far');
          wishlistFilterToggle.querySelector('i').classList.add('fas');
        }
        if (wishlistFilterToggle.querySelector('span')) {
          wishlistFilterToggle.querySelector('span').textContent = 'All Products';
        }
      }
      
      // Toggle wishlist filter when clicked
      wishlistFilterToggle.addEventListener('click', function() {
        const isActive = this.classList.contains('active');
        
        // Check if user is logged in
        const isLoggedIn = document.body.classList.contains('user-logged-in') || 
                          document.querySelector('meta[name="user-logged-in"]')?.getAttribute('content') === 'true';
        
        if (!isActive && !isLoggedIn) {
          // Redirect to login page if not logged in
          window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname + window.location.search);
          return;
        }
        
        // Create current URL object
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        
        if (isActive) {
          // Switch to showing all products
          params.delete('wishlist_only');
        } else {
          // Switch to showing only wishlist products
          params.set('wishlist_only', '1');
        }
        
        // Reset to page 1 when toggling filter
        params.delete('page');
        
        // Update URL and reload
        url.search = params.toString();
        window.location.href = url.toString();
      });
    }
  }

  /**
   * Check if user is logged in
   */
  function isUserLoggedIn() {
    return (
      document.body.classList.contains("user-logged-in") ||
      document.querySelector('meta[name="user-logged-in"]')?.getAttribute("content") ===
        "true"
    );
  }
  
  /**
   * Show toast notification
   */
  function showToast(type, message) {
    const toast = document.getElementById("toast-notification") || createToastElement();
    toast.className = `toast ${type}`;
    toast.querySelector(".toast-message").textContent = message;
    toast.classList.add("show");
    
    setTimeout(() => {
      toast.classList.remove("show");
    }, 3000);
  }
  
  /**
   * Create toast notification element if it doesn't exist
   */
  function createToastElement() {
    const toast = document.createElement("div");
    toast.id = "toast-notification";
    toast.className = "toast";
    toast.innerHTML = `
      <div class="toast-content">
        <span class="toast-message"></span>
        <button class="toast-close">&times;</button>
      </div>
    `;
    
    document.body.appendChild(toast);
    
    toast.querySelector(".toast-close").addEventListener("click", () => {
      toast.classList.remove("show");
    });
    
    return toast;
  }
});

// Wishlist Manager Module
window.WishlistManager = (function() {
  const wishlistKey = 'court_kart_wishlist';
  
  function getWishlist() {
    const list = localStorage.getItem(wishlistKey);
    return list ? JSON.parse(list) : [];
  }
  
  function saveWishlist(wishlist) {
    localStorage.setItem(wishlistKey, JSON.stringify(wishlist));
  }
  
  return {
    checkProduct: function(productId, callback) {
      // If user is logged in, check on server
      if (document.body.classList.contains("user-logged-in")) {
        fetch(`/api/wishlist/check/${productId}`)
          .then(response => response.json())
          .then(data => {
            callback(data.in_wishlist);
          })
          .catch(() => {
            // Fallback to local storage
            const wishlist = getWishlist();
            callback(wishlist.includes(productId));
          });
      } else {
        // Use local storage for guests
        const wishlist = getWishlist();
        callback(wishlist.includes(productId));
      }
    },
    
    toggleProduct: function(productId, callback) {
      const wishlist = getWishlist();
      const index = wishlist.indexOf(productId);
      
      if (index === -1) {
        wishlist.push(productId);
        saveWishlist(wishlist);
        callback(true);
      } else {
        wishlist.splice(index, 1);
        saveWishlist(wishlist);
        callback(false);
      }
    }
  };
})();

// Initialize Quick View Modal if present
if (document.getElementById("quickViewModal")) {
  document.addEventListener("DOMContentLoaded", function() {
    const initQuickViewModal = function() {
      const quickViewModal = document.getElementById("quickViewModal");
      const quickViewContent = document.getElementById("quickViewContent");
      
      function loadQuickViewContent(productId) {
        quickViewContent.innerHTML = '<div class="loader"><i class="fas fa-basketball-ball fa-spin"></i><p>Loading product details...</p></div>';
        
        fetch(`/api/products/${productId}`)
          .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            // After content is loaded, initialize wishlist button in modal
            renderQuickView(data);
            
            // Initialize wishlist button in modal
            const wishlistBtn = quickViewContent.querySelector('[data-action="wishlist"]');
            if (wishlistBtn && window.WishlistManager) {
                // Check initial wishlist status
                window.WishlistManager.checkProduct(productId, function(inWishlist) {
                    if (inWishlist) {
                        wishlistBtn.classList.add('in-wishlist');
                        wishlistBtn.querySelector('i').classList.remove('far');
                        wishlistBtn.querySelector('i').classList.add('fas');
                    }
                });
                
                // Add click event
                wishlistBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.WishlistManager.toggleProduct(productId, function(isAdded) {
                        if (isAdded) {
                            wishlistBtn.classList.add('in-wishlist');
                            wishlistBtn.querySelector('i').classList.remove('far');
                            wishlistBtn.querySelector('i').classList.add('fas');
                            
                            // Also update the button in the product grid
                            const gridBtn = document.querySelector(`.product-card [data-action="wishlist"][data-id="${productId}"]`);
                            if (gridBtn) {
                                gridBtn.classList.add('in-wishlist');
                                gridBtn.querySelector('i').classList.remove('far');
                                gridBtn.querySelector('i').classList.add('fas');
                            }
                        } else {
                            wishlistBtn.classList.remove('in-wishlist');
                            wishlistBtn.querySelector('i').classList.remove('fas');
                            wishlistBtn.querySelector('i').classList.add('far');
                            
                            // Also update the button in the product grid
                            const gridBtn = document.querySelector(`.product-card [data-action="wishlist"][data-id="${productId}"]`);
                            if (gridBtn) {
                                gridBtn.classList.remove('in-wishlist');
                                gridBtn.querySelector('i').classList.remove('fas');
                                gridBtn.querySelector('i').classList.add('far');
                            }
                        }
                    });
                });
            }
          })
          .catch(error => {
            console.error("Error loading quick view:", error);
            quickViewContent.innerHTML = `
              <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                <p>Failed to load product details</p>
                <p class="error-message">${error.message}</p>
                <button class="btn outline" data-close>Close</button>
              </div>`;
          });
      }
      
      // Initialize quick view triggers
      document.querySelectorAll('[data-action="quickview"]').forEach(button => {
        button.addEventListener('click', function() {
          const productId = this.getAttribute('data-id');
          
          if (window.CourtKartModals && window.CourtKartModals.quickViewModal) {
            window.CourtKartModals.quickViewModal.open();
          } else {
            quickViewModal.setAttribute('aria-hidden', 'false');
          }
          
          loadQuickViewContent(productId);
        });
      });
    };
    
    if (window.CourtKartModals) {
      initQuickViewModal();
    } else {
      // If modal component isn't loaded yet, wait for it
      window.addEventListener('CourtKartModalsLoaded', initQuickViewModal);
    }
  });
}
