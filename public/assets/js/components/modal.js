/**
 * Modal Component for Court Kart
 * Provides reusable modal dialog functionality
 */

class Modal {
  constructor(options = {}) {
    this.options = {
      closeOnEscape: true,
      closeOnBackdropClick: true,
      onOpen: null,
      onClose: null,
      ...options
    };
    
    this.isOpen = false;
    this.modal = null;
    this.backdrop = null;
    this.closeBtn = null;
    this.modalContent = null;
    this.lastFocusedElement = null;
    
    this.handleEscapeKey = this.handleEscapeKey.bind(this);
    this.handleBackdropClick = this.handleBackdropClick.bind(this);
  }
  
  /**
   * Create a new modal from HTML content
   * @param {string} content - HTML content for the modal
   * @param {object} options - Optional configuration options
   * @return {Modal} - New modal instance
   */
  static create(content, options = {}) {
    const modal = new Modal(options);
    modal.setContent(content);
    return modal;
  }
  
  /**
   * Create modal from a template element
   * @param {string} templateId - ID of the template element
   * @param {object} options - Optional configuration options
   * @return {Modal} - New modal instance
   */
  static fromTemplate(templateId, options = {}) {
    const template = document.getElementById(templateId);
    if (!template) {
      console.error(`Template with ID "${templateId}" not found`);
      return null;
    }
    
    const content = template.content.cloneNode(true);
    const modal = new Modal(options);
    modal.setContent(content);
    return modal;
  }
  
  /**
   * Set modal content
   * @param {string|Node} content - Content to display in modal
   */
  setContent(content) {
    if (!this.modal) {
      this.createModal();
    }
    
    const modalBody = this.modal.querySelector('.modal-body');
    modalBody.innerHTML = '';
    
    if (typeof content === 'string') {
      modalBody.innerHTML = content;
    } else {
      modalBody.appendChild(content);
    }
    
    return this;
  }
  
  /**
   * Set modal title
   * @param {string} title - Title text
   */
  setTitle(title) {
    if (!this.modal) {
      this.createModal();
    }
    
    const modalTitle = this.modal.querySelector('.modal-title');
    modalTitle.textContent = title;
    
    return this;
  }
  
  /**
   * Create the modal DOM elements
   */
  createModal() {
    // Create modal container
    this.modal = document.createElement('div');
    this.modal.className = 'modal';
    this.modal.setAttribute('role', 'dialog');
    this.modal.setAttribute('aria-modal', 'true');
    this.modal.setAttribute('aria-hidden', 'true');
    
    // Create backdrop
    this.backdrop = document.createElement('div');
    this.backdrop.className = 'modal-backdrop';
    
    // Create modal content
    this.modalContent = document.createElement('div');
    this.modalContent.className = 'modal-content';
    
    // Create modal header
    const modalHeader = document.createElement('div');
    modalHeader.className = 'modal-header';
    
    const modalTitle = document.createElement('h3');
    modalTitle.className = 'modal-title';
    modalTitle.textContent = 'Modal Title';
    
    this.closeBtn = document.createElement('button');
    this.closeBtn.className = 'modal-close';
    this.closeBtn.setAttribute('aria-label', 'Close modal');
    this.closeBtn.innerHTML = '&times;';
    
    // Create modal body
    const modalBody = document.createElement('div');
    modalBody.className = 'modal-body';
    
    // Create modal footer
    const modalFooter = document.createElement('div');
    modalFooter.className = 'modal-footer';
    
    // Assemble modal
    modalHeader.appendChild(modalTitle);
    modalHeader.appendChild(this.closeBtn);
    
    this.modalContent.appendChild(modalHeader);
    this.modalContent.appendChild(modalBody);
    this.modalContent.appendChild(modalFooter);
    
    this.modal.appendChild(this.backdrop);
    this.modal.appendChild(this.modalContent);
    
    // Add event listeners
    this.closeBtn.addEventListener('click', () => this.close());
    
    // Append to DOM
    document.body.appendChild(this.modal);
    
    return this;
  }
  
  /**
   * Open the modal
   */
  open() {
    if (!this.modal) {
      this.createModal();
    }
    
    // Save the currently focused element to restore focus later
    this.lastFocusedElement = document.activeElement;
    
    // Show modal
    this.modal.classList.add('active');
    this.modal.setAttribute('aria-hidden', 'false');
    
    // Add event listeners
    if (this.options.closeOnEscape) {
      document.addEventListener('keydown', this.handleEscapeKey);
    }
    
    if (this.options.closeOnBackdropClick) {
      this.backdrop.addEventListener('click', this.handleBackdropClick);
    }
    
    // Set focus to the first focusable element in the modal
    this.trapFocus();
    
    // Prevent body scrolling
    document.body.style.overflow = 'hidden';
    
    this.isOpen = true;
    
    // Trigger onOpen callback
    if (typeof this.options.onOpen === 'function') {
      this.options.onOpen(this);
    }
    
    return this;
  }
  
  /**
   * Close the modal
   */
  close() {
    if (!this.isOpen || !this.modal) return;
    
    // Hide modal
    this.modal.classList.remove('active');
    this.modal.setAttribute('aria-hidden', 'true');
    
    // Remove event listeners
    document.removeEventListener('keydown', this.handleEscapeKey);
    this.backdrop.removeEventListener('click', this.handleBackdropClick);
    
    // Restore body scrolling
    document.body.style.overflow = '';
    
    // Restore focus
    if (this.lastFocusedElement) {
      this.lastFocusedElement.focus();
    }
    
    this.isOpen = false;
    
    // Trigger onClose callback
    if (typeof this.options.onClose === 'function') {
      this.options.onClose(this);
    }
    
    return this;
  }
  
  /**
   * Handle ESC key press
   * @param {KeyboardEvent} event - Keyboard event
   */
  handleEscapeKey(event) {
    if (event.key === 'Escape') {
      this.close();
    }
  }
  
  /**
   * Handle backdrop click
   */
  handleBackdropClick() {
    this.close();
  }
  
  /**
   * Set up focus trap within the modal
   */
  trapFocus() {
    // Find all focusable elements
    const focusableElements = this.modalContent.querySelectorAll('a[href], button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled])');
    
    if (focusableElements.length > 0) {
      // Focus the first element
      focusableElements[0].focus();
    } else {
      // If no focusable elements, focus the modal itself
      this.modalContent.setAttribute('tabindex', '-1');
      this.modalContent.focus();
    }
  }
}

// Initialize modals on the page
document.addEventListener('DOMContentLoaded', function() {
  // Find elements with data-modal-target attribute
  const modalTriggers = document.querySelectorAll('[data-modal-target]');
  
  modalTriggers.forEach(trigger => {
    const targetId = trigger.getAttribute('data-modal-target');
    const modalEl = document.getElementById(targetId);
    
    if (modalEl) {
      // Create a Modal instance for this element
      const modal = new Modal({
        closeOnEscape: modalEl.getAttribute('data-close-on-escape') !== 'false',
        closeOnBackdropClick: modalEl.getAttribute('data-close-on-backdrop') !== 'false'
      });
      
      // Set content from the target element
      modal.setContent(modalEl.innerHTML);
      
      // Handle modal opening
      trigger.addEventListener('click', event => {
        event.preventDefault();
        modal.open();
      });
    }
  });
});

// Export the Modal class for use in other scripts
window.Modal = Modal;
