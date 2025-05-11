<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Court Kart - Basketball Equipment Store' ?></title>
    
    <!-- Load main CSS files -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/layouts.css">
    
    <!-- Authentication pages CSS -->
    <?php if (strpos($_SERVER['REQUEST_URI'], '/login') === 0 || strpos($_SERVER['REQUEST_URI'], '/register') === 0) { ?>
        <link rel="stylesheet" href="/assets/css/auth.css">
        <link rel="stylesheet" href="/assets/css/pages/auth.css">
    <?php } ?>
    
    <!-- Account pages CSS -->
    <?php if (strpos($_SERVER['REQUEST_URI'], '/account') === 0) { ?>
        <link rel="stylesheet" href="/assets/css/pages/account.css">
    <?php } ?>
    
    <!-- Include page-specific CSS when available -->
    <?php if (isset($page_css)) { ?>
        <?php if (is_array($page_css)) { ?>
            <?php foreach ($page_css as $css) { ?>
                <link rel="stylesheet" href="/assets/css/pages/<?= $css ?>.css">
            <?php } ?>
        <?php } else { ?>
            <link rel="stylesheet" href="/assets/css/pages/<?= $page_css ?>.css">
        <?php } ?>
    <?php } ?>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">
                        <img src="/assets/images/court-kart-logo.svg" alt="Court Kart">
                    </a>
                </div>
                
                <nav>
                    <a href="/">Home</a>
                    <a href="/shop">Shop</a>
                    
                    <div class="nav-right">
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <?php if ($_SESSION['user_role'] === 'admin') { ?>
                                <a href="/admin">Admin Dashboard</a>
                            <?php } ?>
                            
                            <a href="/cart" class="nav-link cart-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <?php
                                $cartCount = 0;
                                if (\App\Core\Session::get('user_id')) {
                                    $cartCount = \App\Models\Cart::getItemCount(\App\Core\Session::get('user_id'));
                                }
                                ?>
                                <?php if ($cartCount > 0) { ?>
                                    <span class="cart-count"><?= $cartCount ?></span>
                                <?php } ?>
                            </a>
                            
                            <a href="/account" class="nav-link user-profile-link">
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                                    <span class="user-badge admin">Admin</span>
                                <?php } ?>
                                
                                <?php if (isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])) { ?>
                                    <div class="user-avatar">
                                        <img src="<?= htmlspecialchars($_SESSION['profile_image']) ?>" alt="Profile" class="user-avatar-img">
                                    </div>
                                <?php } else { ?>
                                    <div class="user-avatar">
                                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                <?php } ?>
                            </a>
                        <?php } else { ?>
                            <a href="/login" class="btn btn-primary">Login</a>
                            <a href="/register" class="btn btn-outline">Register</a>
                        <?php } ?>
                    </div>
                </nav>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>
    
    <div class="container main-content">
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
        
        <?= $content ?>
    </div>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Court Kart</h3>
                    <p>Your one-stop shop for premium basketball equipment and apparel.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/shop">Shop</a></li>
                        <li><a href="/cart">Cart</a></li>
                        <li><a href="/account">My Account</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p>Email: info@courtkart.com</p>
                    <p>Phone: (555) 123-4567</p>
                </div>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> Court Kart. All rights reserved.
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="/assets/js/main.js"></script>
    
    <!-- Include page-specific JS when available -->
    <?php if (isset($page_js)) { ?>
        <?php if (is_array($page_js)) { ?>
            <?php foreach ($page_js as $js) { ?>
                <script src="/assets/js/pages/<?= $js ?>.js"></script>
            <?php } ?>
        <?php } else { ?>
            <script src="/assets/js/pages/<?= $page_js ?>.js"></script>
        <?php } ?>
    <?php } ?>

    <?php if (isset($_GET['debug_css']) && $_GET['debug_css'] === '1') { ?>
    <div style="position:fixed; bottom:0; left:0; background:#000; color:#fff; padding:5px; font-size:12px; z-index:9999;">
        <p>CSS Files:</p>
        <ul style="margin:0; padding-left:20px;">
            <?php if (isset($page_css)) { ?>
                <?php if (is_array($page_css)) { ?>
                    <?php foreach ($page_css as $css) { ?>
                        <li>/assets/css/pages/<?= $css ?>.css</li>
                    <?php } ?>
                <?php } else { ?>
                    <li>/assets/css/pages/<?= $page_css ?>.css</li>
                <?php } ?>
            <?php } ?>
            <?php if (strpos($_SERVER['REQUEST_URI'], '/login') === 0 || strpos($_SERVER['REQUEST_URI'], '/register') === 0) { ?>
                <li>/assets/css/auth.css</li>
                <li>/assets/css/pages/auth.css</li>
            <?php } ?>
            <?php if (strpos($_SERVER['REQUEST_URI'], '/account') === 0) { ?>
                <li>/assets/css/pages/account.css</li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>
</body>
</html>
