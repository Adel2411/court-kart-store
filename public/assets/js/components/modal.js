/**
 * Modal Component
 * Handles functionality for modals throughout Court Kart
 */

class Modal {
  constructor(modalElement) {
    this.modal = modalElement;
    this.backdrop = this.modal.querySelector('.modal-backdrop');
    this.closeButtons = this.modal.querySelectorAll('[data-close], .modal-close');
    this.openButtons = document.querySelectorAll(`[data-modal="${this.modal.id}"]`);
    this.focusableElements = this.modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    this.firstFocusable = this.focusableElements[0];
    this.lastFocusable = this.focusableElements[this.focusableElements.length - 1];
    this.isOpen = false;
    this.previouslyFocused = null;
    
    this.initEventListeners();
  }
  
  initEventListeners() {
    // Open modal buttons
    this.openButtons.forEach(button => {
      button.addEventListener('click', this.open.bind(this));
    });
    
    // Close modal buttons
    this.closeButtons.forEach(button => {
      button.addEventListener('click', this.close.bind(this));
    });
    
    // Click on backdrop to close
    if (this.backdrop) {
      this.backdrop.addEventListener('click', this.close.bind(this));
    }
    
    // Listen for ESC key
    this.modal.addEventListener('keydown', this.handleKeyDown.bind(this));
  }
  
  open() {
    // Store the previously focused element
    this.previouslyFocused = document.activeElement;
    
    // Show the modal
    this.modal.classList.add('active');
    this.modal.setAttribute('aria-hidden', 'false');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
    
    // Focus the first focusable element
    if (this.firstFocusable) {
      setTimeout(() => {
        this.firstFocusable.focus();
      }, 100);
    }
    
    this.isOpen = true;
    
    // Trigger open event
    const openEvent = new CustomEvent('modal:open', { detail: { modalId: this.modal.id } });
    document.dispatchEvent(openEvent);
    
    return this;
  }
  
  close() {
    if (!this.isOpen) return this;
    
    // Hide the modal
    this.modal.classList.remove('active');
    this.modal.setAttribute('aria-hidden', 'true');
    
    // Restore body scroll
    document.body.style.overflow = '';
    
    // Restore focus to previous element
    if (this.previouslyFocused) {
      this.previouslyFocused.focus();
    }
    
    this.isOpen = false;
    
    // Trigger close event
    const closeEvent = new CustomEvent('modal:close', { detail: { modalId: this.modal.id } });
    document.dispatchEvent(closeEvent);
    
    return this;
  }
  
  handleKeyDown(event) {
    if (!this.isOpen) return;
    
    // Close on ESC key
    if (event.key === 'Escape') {
      event.preventDefault();
      this.close();
    }
    
    // Trap focus inside modal
    if (event.key === 'Tab') {
      // If there are no focusable elements, do nothing
      if (!this.focusableElements.length) return;
      
      // Shift + Tab
      if (event.shiftKey) {
        if (document.activeElement === this.firstFocusable) {
          event.preventDefault();
          this.lastFocusable.focus();
        }
      } 
      // Tab
      else {
        if (document.activeElement === this.lastFocusable) {
          event.preventDefault();
          this.firstFocusable.focus();
        }
      }
    }
  }
}

/**
 * Initialize all modals on the page
 */
function initModals() {
  const modals = document.querySelectorAll('.modal');
  const modalInstances = {};
  
  modals.forEach(modalElement => {
    const modalId = modalElement.id;
    if (modalId) {
      modalInstances[modalId] = new Modal(modalElement);
    }
  });
  
  // Add to window for external access
  window.CourtKartModals = modalInstances;
  
  // Support for dynamic opening via data attributes
  document.addEventListener('click', function(event) {
    const openTrigger = event.target.closest('[data-open-modal]');
    if (openTrigger) {
      const modalId = openTrigger.dataset.openModal;
      if (modalInstances[modalId]) {
        modalInstances[modalId].open();
      }
    }
  });
}

// Initialize modals when the DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initModals);
} else {
  initModals();
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { Modal, initModals };
}
