<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - Court Kart' ?></title>
    <link rel="icon" type="image/x-icon" href="public/assets/images/court-kart-logo-dark.ico">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/layouts.css">
    <link rel="stylesheet" href="/assets/css/pages/admin.css">
    
    <?php if (isset($page_css)) { ?>
        <link rel="stylesheet" href="/assets/css/pages/<?= $page_css ?>.css">
    <?php } ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper" id="adminWrapper">
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
        
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <img src="/assets/images/court-kart-logo.svg" alt="Court Kart">
            </div>
            
            <div class="admin-menu">
                <div class="admin-menu-title">Main</div>
                <ul>
                    <li class="admin-menu-item">
                        <a href="/admin" class="admin-menu-link <?= $_SERVER['REQUEST_URI'] == '/admin' ? 'active' : '' ?>">
                            <span class="admin-menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                            Dashboard
                        </a>
                    </li>
                </ul>
                
                <div class="admin-menu-title">Management</div>
                <ul>
                    <li class="admin-menu-item">
                        <a href="/admin/products" class="admin-menu-link <?= $_SERVER['REQUEST_URI'] == '/admin/products' ? 'active' : '' ?>">
                            <span class="admin-menu-icon"><i class="fas fa-box"></i></span>
                            Products
                        </a>
                    </li>
                    <li class="admin-menu-item">
                        <a href="/admin/orders" class="admin-menu-link <?= $_SERVER['REQUEST_URI'] == '/admin/orders' ? 'active' : '' ?>">
                            <span class="admin-menu-icon"><i class="fas fa-shopping-bag"></i></span>
                            Orders
                        </a>
                    </li>
                    <li class="admin-menu-item">
                        <a href="/admin/users" class="admin-menu-link <?= $_SERVER['REQUEST_URI'] == '/admin/users' ? 'active' : '' ?>">
                            <span class="admin-menu-icon"><i class="fas fa-users"></i></span>
                            Users
                        </a>
                    </li>
                </ul>
                
                <div class="admin-menu-title">Settings</div>
                <ul>
                    <li class="admin-menu-item">
                        <a href="/" class="admin-menu-link">
                            <span class="admin-menu-icon"><i class="fas fa-home"></i></span>
                            View Store
                        </a>
                    </li>
                    <li class="admin-menu-item">
                        <a href="/logout" class="admin-menu-link">
                            <span class="admin-menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- Main content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1 class="admin-title"><?= $title ?? 'Dashboard' ?></h1>
                <div class="admin-header-actions">
                    <div class="admin-user">
                        <?php if (isset($_SESSION['profile_image']) && ! empty($_SESSION['profile_image'])) { ?>
                            <div class="admin-user-image">
                                <img src="<?= htmlspecialchars($_SESSION['profile_image']) ?>" alt="Admin Profile" class="admin-avatar-img">
                            </div>
                        <?php } else { ?>
                            <div class="admin-user-image">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                            </div>
                        <?php } ?>
                        <div class="admin-user-info">
                            <div class="admin-user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></div>
                            <div class="admin-user-role">Administrator</div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash messages -->
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php } ?>
            
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php } ?>
            
            <!-- Main content -->
            <?= $content ?>
        </main>
    </div>
    
    <!-- Sidebar toggle button for mobile -->
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>
    
    <script src="/assets/js/main.js"></script>
    <?php if (isset($page_js)) { ?>
        <script src="/assets/js/pages/<?= $page_js ?>.js"></script>
    <?php } ?>
    
    <script>
        // Sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('adminWrapper');
            const toggle = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            if (toggle) {
                toggle.addEventListener('click', function() {
                    wrapper.classList.toggle('sidebar-visible');
                });
            }
            
            // Close sidebar when backdrop is clicked
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    wrapper.classList.remove('sidebar-visible');
                });
            }
            
            // Close sidebar when window is resized to desktop size
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991 && wrapper.classList.contains('sidebar-visible')) {
                    wrapper.classList.remove('sidebar-visible');
                }
            });
            
            // Close sidebar when escape key is pressed
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && wrapper.classList.contains('sidebar-visible')) {
                    wrapper.classList.remove('sidebar-visible');
                }
            });
        });
    </script>
</body>
</html>
