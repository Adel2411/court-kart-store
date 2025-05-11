<div class="admin-content-header">
    <h1>User Management</h1>
    <div class="admin-controls">
        <div class="search-container">
            <input type="text" id="userSearch" class="search-input" placeholder="Search users...">
            <i class="fas fa-search search-icon"></i>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <div class="card-title">Registered Users</div>
        <div class="card-actions">
            <select class="form-control" id="userRoleFilter">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="admin-table users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)) { ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-container">
                                <i class="fas fa-users empty-state-icon"></i>
                                <h3>No users found</h3>
                                <p>There are no registered users in the system yet.</p>
                            </div>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($users as $user) { ?>
                        <tr data-role="<?= strtolower($user['role']) ?>">
                            <td><?= $user['id'] ?></td>
                            <td class="user-info">
                                <?php if (!empty($user['profile_image'])) { ?>
                                    <div class="user-avatar">
                                        <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="user-avatar-img">
                                    </div>
                                <?php } else { ?>
                                    <div class="user-avatar">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                <?php } ?>
                                <div class="user-name"><?= htmlspecialchars($user['name']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge role-badge role-<?= strtolower($user['role']) ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <span class="date"><?= date('M j, Y', strtotime($user['created_at'])) ?></span>
                                    <span class="time"><?= date('g:i A', strtotime($user['created_at'])) ?></span>
                                </div>
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <button class="btn-icon view-user" data-id="<?= $user['id'] ?>" title="View User">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-icon edit-user" data-id="<?= $user['id'] ?>" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($user['id'] != $_SESSION['user_id']) { ?>
                                        <button class="btn-icon delete-user" data-id="<?= $user['id'] ?>" title="Delete User">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <div class="admin-pagination">
        <div class="pagination-info">Showing <?= count($users) ?> of <?= $totalUsers ?? count($users) ?> users</div>
        <div class="pagination-controls">
            <button class="btn btn-pagination" disabled>
                <i class="fas fa-chevron-left"></i> Previous
            </button>
            <button class="btn btn-pagination">
                Next <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- User View Modal -->
<div class="admin-modal" id="viewUserModal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>User Details</h2>
            <button type="button" class="close" id="closeViewModal">&times;</button>
        </div>
        <div class="admin-modal-body">
            <div class="user-profile">
                <div class="user-profile-header">
                    <div class="large-avatar" id="userAvatar">
                        <!-- Will be populated with image or initials via JavaScript -->
                    </div>
                    <div class="user-profile-info">
                        <h3 id="userName"></h3>
                        <div id="userEmail"></div>
                        <span class="badge" id="userRole"></span>
                    </div>
                </div>
                
                <div class="user-profile-details">
                    <div class="detail-row">
                        <div class="detail-label">Member Since</div>
                        <div class="detail-value" id="userCreatedAt"></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Orders</div>
                        <div class="detail-value" id="userOrderCount">0</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Last Login</div>
                        <div class="detail-value" id="userLastLogin">N/A</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-outline" id="closeViewModalBtn">Close</button>
            <button type="button" class="btn btn-primary edit-btn" id="editUserBtn">Edit User</button>
        </div>
    </div>
</div>

<!-- User Delete Confirmation Modal -->
<div class="admin-modal" id="deleteUserModal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>Delete User</h2>
            <button type="button" class="close" id="closeDeleteModal">&times;</button>
        </div>
        <div class="admin-modal-body">
            <div class="confirmation-message">
                <i class="fas fa-exclamation-triangle warning-icon"></i>
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <p class="user-to-delete">User: <strong id="deleteUserName"></strong></p>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-outline" id="cancelDeleteBtn">Cancel</button>
            <form id="deleteUserForm" action="/admin/users/delete" method="post" class="inline-form">
                <input type="hidden" name="user_id" id="deleteUserId">
                <button type="submit" class="btn btn-danger">Delete User</button>
            </form>
        </div>
    </div>
</div>

<!-- User Edit Modal -->
<div class="admin-modal" id="editUserModal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>Edit User</h2>
            <button type="button" class="close" id="closeEditModal">&times;</button>
        </div>
        <div class="admin-modal-body">
            <form id="editUserForm" action="/admin/users/update" method="post">
                <input type="hidden" name="user_id" id="editUserId">
                
                <div class="admin-form-group">
                    <label for="editName">Full Name</label>
                    <div class="input-icon-wrapper">
                        <input type="text" id="editName" name="name" class="form-control" required>
                        <i class="fas fa-user form-icon"></i>
                    </div>
                </div>
                
                <div class="admin-form-group">
                    <label for="editEmail">Email Address</label>
                    <div class="input-icon-wrapper">
                        <input type="email" id="editEmail" name="email" class="form-control" required>
                        <i class="fas fa-envelope form-icon"></i>
                    </div>
                </div>
                
                <div class="admin-form-group">
                    <label for="editRole">Role</label>
                    <div class="input-icon-wrapper">
                        <select id="editRole" name="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <i class="fas fa-user-shield form-icon"></i>
                    </div>
                </div>
                
                <div class="admin-form-group">
                    <label for="editPassword">Password (Leave blank to keep unchanged)</label>
                    <div class="input-icon-wrapper">
                        <input type="password" id="editPassword" name="password" class="form-control">
                        <i class="fas fa-lock form-icon"></i>
                        <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-outline" id="cancelEditBtn">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveEditBtn">Save Changes</button>
        </div>
    </div>
</div>

<!-- Include the JavaScript file -->
<script src="/assets/js/pages/admin-users.js"></script>
