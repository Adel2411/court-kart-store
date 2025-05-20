<?php
// Default to the overview page if no active page is specified
$activePage = $activePage ?? 'overview';
?>

<div class="account-sidebar">
    <div class="account-avatar">
        <?php if (! empty($user['profile_image'])) { ?>
            <div class="avatar-container">
                <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="avatar-image">
            </div>
        <?php } else { ?>
            <div class="avatar-image">
                <?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?>
            </div>
        <?php } ?>
        <h2><?= htmlspecialchars($user['name']) ?></h2>
        <p><?= htmlspecialchars($user['email']) ?></p>
    </div>
    
    <div class="account-menu">
        <a href="/account" class="account-menu-item <?= $activePage === 'overview' ? 'active' : '' ?>">
            <i class="fas fa-user-circle"></i> Account Overview
        </a>
        <a href="/orders" class="account-menu-item <?= $activePage === 'orders' ? 'active' : '' ?>">
            <i class="fas fa-shopping-bag"></i> My Orders
        </a>
        <a href="/account/edit" class="account-menu-item <?= $activePage === 'edit' ? 'active' : '' ?>">
            <i class="fas fa-user-edit"></i> Edit Profile
        </a>
        <a href="/logout" class="account-menu-item">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>
