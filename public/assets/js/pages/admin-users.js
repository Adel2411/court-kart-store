/**
 * Admin Users Page Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize search functionality
  initSearch();
  
  // Initialize role filter
  initRoleFilter();
  
  // Initialize view user modal
  initViewModal();
  
  // Initialize edit user modal
  initEditModal();
  
  // Initialize delete user modal
  initDeleteModal();
  
  // Initialize password toggles
  initPasswordToggles();
});

/**
 * Initialize search functionality
 */
function initSearch() {
  const searchInput = document.getElementById('userSearch');
  if (!searchInput) return;
  
  searchInput.addEventListener('input', debounce(function() {
    const searchTerm = this.value.toLowerCase();
    const userRows = document.querySelectorAll('.users-table tbody tr:not(.empty-state)');
    
    let hasResults = false;
    
    userRows.forEach(row => {
      const userName = row.querySelector('.user-name').textContent.toLowerCase();
      const userEmail = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
      const userRole = row.querySelector('.role-badge').textContent.toLowerCase();
      
      // Search in name, email and role
      if (userName.includes(searchTerm) || 
          userEmail.includes(searchTerm) || 
          userRole.includes(searchTerm)) {
        row.style.display = '';
        hasResults = true;
      } else {
        row.style.display = 'none';
      }
    });
    
    // Show no results message if no matches
    const tbodyElement = document.querySelector('.users-table tbody');
    let noResultsRow = document.querySelector('.no-search-results');
    
    if (!hasResults) {
      if (!noResultsRow) {
        noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-search-results';
        noResultsRow.innerHTML = `
          <td colspan="6" class="empty-state">
            <div class="empty-state-container">
              <i class="fas fa-search empty-state-icon"></i>
              <h3>No results found</h3>
              <p>No users match your search criteria: "${searchTerm}"</p>
            </div>
          </td>
        `;
        tbodyElement.appendChild(noResultsRow);
      }
    } else if (noResultsRow) {
      noResultsRow.remove();
    }
  }, 300));
}

/**
 * Initialize role filter functionality
 */
function initRoleFilter() {
  const roleFilter = document.getElementById('userRoleFilter');
  if (!roleFilter) return;
  
  roleFilter.addEventListener('change', function() {
    const selectedRole = this.value.toLowerCase();
    const userRows = document.querySelectorAll('.users-table tbody tr:not(.empty-state):not(.no-search-results)');
    
    userRows.forEach(row => {
      if (!selectedRole || row.getAttribute('data-role') === selectedRole) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
}

/**
 * Initialize user view modal
 * Updated to use the modal component
 */
function initViewModal() {
  const viewButtons = document.querySelectorAll('.view-user');
  const editUserBtn = document.getElementById('editUserBtn');
  
  viewButtons.forEach(button => {
    button.addEventListener('click', function() {
      const userId = this.getAttribute('data-id');
      const row = this.closest('tr');
      
      // Populate modal with data from the row
      const userEmail = row.querySelector('td:nth-child(3)').textContent;
      const userName = userEmail.split('@')[0]; // Extract name from email as fallback
      const userRole = row.querySelector('.role-badge').textContent;
      const userCreatedAt = row.querySelector('.date').textContent + ' at ' + 
                           row.querySelector('.time').textContent;
      const profileImage = row.querySelector('.user-avatar-img')?.src || '';
      
      document.getElementById('userName').textContent = userName;
      document.getElementById('userEmail').textContent = userEmail;
      document.getElementById('userRole').textContent = userRole;
      document.getElementById('userRole').className = 'badge role-badge role-' + userRole.toLowerCase();
      document.getElementById('userCreatedAt').textContent = userCreatedAt;
      
      // Set user avatar (either image or initials)
      const userAvatarElement = document.getElementById('userAvatar');
      if (profileImage) {
        userAvatarElement.innerHTML = `<img src="${profileImage}" alt="${userName}" class="user-avatar-img">`;
      } else {
        userAvatarElement.textContent = userName.charAt(0).toUpperCase();
      }
      
      // Set edit button data
      editUserBtn.setAttribute('data-id', userId);
      
      // Open the modal using our modal component
      if (window.CourtKartModals && window.CourtKartModals.viewUserModal) {
        window.CourtKartModals.viewUserModal.open();
      } else {
        // Fallback if modal component isn't loaded yet
        document.getElementById('viewUserModal').classList.add('active');
      }
    });
  });
  
  // Edit button functionality
  if (editUserBtn) {
    editUserBtn.addEventListener('click', function() {
      const userId = this.getAttribute('data-id');
      
      // Close the view modal
      if (window.CourtKartModals && window.CourtKartModals.viewUserModal) {
        window.CourtKartModals.viewUserModal.close();
      } else {
        document.getElementById('viewUserModal').classList.remove('active');
      }
      
      // Find and trigger the edit button for this user
      setTimeout(() => {
        const editButton = document.querySelector(`.edit-user[data-id="${userId}"]`);
        if (editButton) {
          editButton.click();
        }
      }, 300); // Add a small delay to allow modal animation to complete
    });
  }
}

/**
 * Initialize edit user modal
 */
function initEditModal() {
  const editButtons = document.querySelectorAll('.edit-user');
  const editModal = document.getElementById('editUserModal');
  const editUserForm = document.getElementById('editUserForm');
  const saveEditBtn = document.getElementById('saveEditBtn');
  const cancelEditBtn = document.getElementById('cancelEditBtn');
  
  if (!editModal || !editButtons.length) return;
  
  editButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const userId = this.getAttribute('data-id');
      const row = this.closest('tr');
      
      // Correctly fetch user details from the row
      // Get the user's email from the third column
      const userEmail = row.querySelector('td:nth-child(3)').textContent.trim();
      // Extract name from email as fallback or use real name if available
      let userName = userEmail.split('@')[0]; 
      
      // If the row has the user's name stored elsewhere, try to get it
      const userNameEl = row.querySelector('.user-info');
      if (userNameEl) {
        const nameFromImg = userNameEl.querySelector('img')?.getAttribute('alt');
        if (nameFromImg) userName = nameFromImg;
      }
      
      // Get the user role from the badge
      const userRole = row.querySelector('.role-badge').textContent.toLowerCase().trim();
      
      // Populate the form fields
      document.getElementById('editUserId').value = userId;
      document.getElementById('editName').value = userName;
      document.getElementById('editEmail').value = userEmail;
      document.getElementById('editRole').value = userRole;
      document.getElementById('editPassword').value = ''; // Clear password field
      
      // Show modal
      editModal.classList.add('active');
    });
  });
  
  // Close modal functionality
  const closeEditModalFn = function() {
    editModal.classList.remove('active');
  };
  
  // Add click handler to close button
  const closeButtons = editModal.querySelectorAll('.modal-close, [data-close]');
  closeButtons.forEach(button => {
    button.addEventListener('click', closeEditModalFn);
  });
  
  if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEditModalFn);
  
  // Save changes functionality
  if (saveEditBtn && editUserForm) {
    saveEditBtn.addEventListener('click', function() {
      if (editUserForm.checkValidity()) {
        // Add form submission handling with error prevention
        try {
          editUserForm.submit();
        } catch (error) {
          console.error('Error submitting edit user form:', error);
          alert('Failed to update user. Please try again.');
        }
      } else {
        // Trigger HTML5 validation
        editUserForm.reportValidity();
      }
    });
  }
}

/**
 * Initialize delete confirmation modal
 */
function initDeleteModal() {
  const deleteButtons = document.querySelectorAll('.delete-user');
  const deleteModal = document.getElementById('deleteUserModal');
  const closeDeleteModal = document.getElementById('closeDeleteModal');
  const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
  const deleteUserForm = document.getElementById('deleteUserForm');
  const deleteUserId = document.getElementById('deleteUserId');
  const deleteUserName = document.getElementById('deleteUserName');
  const confirmDeleteBtn = document.querySelector('#deleteUserForm button[type="submit"]');
  
  if (!deleteModal) return;
  
  deleteButtons.forEach(button => {
    button.addEventListener('click', function() {
      const userId = this.getAttribute('data-id');
      const row = this.closest('tr');
      const userName = row.querySelector('.user-name').textContent;
      
      // Set form values
      deleteUserId.value = userId;
      deleteUserName.textContent = userName;
      
      // Show modal
      deleteModal.classList.add('active');
    });
  });
  
  // Close modal functionality
  const closeDeleteModalFn = function() {
    deleteModal.classList.remove('active');
  };
  
  // Add click handler to close button
  deleteModal.querySelector('.modal-close').addEventListener('click', closeDeleteModalFn);
  
  // Add click handler to backdrop
  deleteModal.querySelector('.modal-backdrop').addEventListener('click', closeDeleteModalFn);
  
  if (closeDeleteModal) closeDeleteModal.addEventListener('click', closeDeleteModalFn);
  if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModalFn);
  
  // Add submit handler for delete form
  if (confirmDeleteBtn && deleteUserForm) {
    confirmDeleteBtn.addEventListener('click', function(e) {
      e.preventDefault();
      
      if (confirm('Are you absolutely sure you want to delete this user? This action cannot be undone.')) {
        try {
          deleteUserForm.submit();
        } catch (error) {
          console.error('Error submitting delete user form:', error);
          alert('Failed to delete user. Please try again.');
        }
      }
    });
  }
}

/**
 * Initialize password visibility toggles
 */
function initPasswordToggles() {
  const toggleButtons = document.querySelectorAll('.toggle-password');
  
  toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
      const passwordField = this.previousElementSibling;
      const eyeIcon = this.querySelector('i');
      
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    });
  });
}

/**
 * Debounce function to limit how often a function runs
 */
function debounce(func, wait) {
  let timeout;
  return function() {
    const context = this;
    const args = arguments;
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(context, args), wait);
  };
}
