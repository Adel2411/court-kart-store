<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Court Kart - Basketball Equipment Store' ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/images/court-kart-logo-dark.ico">
    
    <!-- Load CSS files -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/layouts.css">
    <link rel="stylesheet" href="/assets/css/components/modal.css">
    <link rel="stylesheet" href="/assets/css/components/footer.css">
    
    <?php if (strpos($_SERVER['REQUEST_URI'], '/login') === 0 || strpos($_SERVER['REQUEST_URI'], '/register') === 0) { ?>
        <link rel="stylesheet" href="/assets/css/auth.css">
        <link rel="stylesheet" href="/assets/css/pages/auth.css">
    <?php } ?>
    
    <?php if (strpos($_SERVER['REQUEST_URI'], '/account') === 0) { ?>
        <link rel="stylesheet" href="/assets/css/pages/account.css">
    <?php } ?>
    
    <?php if (str_contains($_SERVER['REQUEST_URI'], '/orders') && !str_contains($_SERVER['REQUEST_URI'], '/admin')): ?>
        <link rel="stylesheet" href="/assets/css/pages/orders.css">
    <?php endif; ?>
    
    <?php if (isset($page_css)) { ?>
        <?php if (is_array($page_css)) { ?>
            <?php foreach ($page_css as $css) { ?>
                <link rel="stylesheet" href="/assets/css/pages/<?= $css ?>.css">
            <?php } ?>
        <?php } else { ?>
            <link rel="stylesheet" href="/assets/css/pages/<?= $page_css ?>.css">
        <?php } ?>
    <?php } ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/" aria-label="Court Kart Home">
                        <img src="/assets/images/court-kart-logo.svg" alt="Court Kart" width="120" height="40">
                    </a>
                </div>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mainNav">
                    <span class="hamburger-icon">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </span>
                </button>
                
                <nav class="main-nav" id="mainNav" aria-label="Main navigation">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="/" class="nav-link <?= $_SERVER['REQUEST_URI'] === '/' ? 'active' : '' ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="/shop" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/shop') === 0 ? 'active' : '' ?>">Shop</a>
                        </li>
                    </ul>
                    
                    <div class="nav-right">
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <?php if ($_SESSION['user_role'] === 'admin') { ?>
                                <a href="/admin" class="nav-link admin-link" aria-label="Admin Dashboard">
                                    <i class="fas fa-cog"></i>
                                    <span>Admin</span>
                                </a>
                            <?php } ?>
                            
                            <a href="/wishlist" class="nav-link wishlist-link" aria-label="My Wishlist">
                                <i class="fas fa-heart"></i>
                                <span class="sr-only">Wishlist</span>
                                <?php
                                $wishlistCount = 0;
                                if (\App\Core\Session::get('user_id')) {
                                    $wishlistModel = new \App\Models\Wishlist();
                                    $wishlistCount = $wishlistModel->countWishlistItems(\App\Core\Session::get('user_id'));
                                }
                                ?>
                                <?php if ($wishlistCount > 0) { ?>
                                    <span class="wishlist-count"><?= $wishlistCount ?></span>
                                <?php } ?>
                            </a>
                            
                            <a href="/cart" class="nav-link cart-link" aria-label="Shopping Cart">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="sr-only">Cart</span>
                                <?php
                                $cartCount = 0;
                            if (\App\Core\Session::get('user_id')) {
                                $cartCount = \App\Models\Cart::getItemCount(\App\Core\Session::get('user_id'));
                            }
                            ?>
                                <?php if ($cartCount > 0) { ?>
                                    <span class="cart-badge"><?= $cartCount ?></span>
                                <?php } ?>
                            </a>
                            
                            <div class="user-menu">
                                <button class="user-menu-btn" id="userMenuBtn" aria-label="User menu" aria-expanded="false" aria-haspopup="true">
                                    <?php if (isset($_SESSION['profile_image']) && ! empty($_SESSION['profile_image'])) { ?>
                                        <div class="user-avatar">
                                            <img src="<?= htmlspecialchars($_SESSION['profile_image']) ?>" alt="" class="user-avatar-img">
                                        </div>
                                    <?php } else { ?>
                                        <div class="user-avatar">
                                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                                        <span class="user-badge admin">Admin</span>
                                    <?php } ?>
                                </button>
                                <div class="user-dropdown" id="userDropdown">
                                    <div class="user-info">
                                        <div class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></div>
                                        <div class="user-email"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></div>
                                    </div>
                                    <ul class="dropdown-list">
                                        <li><a href="/account"><i class="fas fa-user"></i> My Account</a></li>
                                        <li><a href="/orders"><i class="fas fa-box"></i> My Orders</a></li>
                                        <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php } else { ?>
                            <a href="/login" class="btn btn-primary btn-sm login-btn">Login</a>
                            <a href="/register" class="btn btn-outline btn-sm register-btn">Register</a>
                        <?php } ?>
                    </div>
                </nav>
                
                <div class="nav-backdrop" id="navBackdrop"></div>
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
                    <div class="footer-social">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/shop">Shop</a></li>
                        <li><a href="/cart">Cart</a></li>
                        <li><a href="/account">My Account</a></li>
                        <li><a href="/about">About Us</a></li>
                        <li><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i>
                        <p>adel.info@courtkart.com</p>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-phone-alt"></i>
                        <p>(555) 123-4567</p>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>123 Basketball Ave, Belcourt City</p>
                    </div>
                </div>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> Court Kart. All rights reserved. | Developed by <a href="https://github.com/Adel2411" target="_blank">Adel</a>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="/assets/js/main.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mainNav = document.getElementById('mainNav');
            const navBackdrop = document.getElementById('navBackdrop');
            const body = document.body;
            
            function toggleMobileMenu() {
                const isExpanded = mainNav.classList.contains('active');
                mainNav.classList.toggle('active');
                mobileMenuBtn.classList.toggle('active');
                navBackdrop.classList.toggle('active');
                body.classList.toggle('menu-open');
                
                mobileMenuBtn.setAttribute('aria-expanded', !isExpanded);
            }
            
            if (mobileMenuBtn && mainNav) {
                mobileMenuBtn.addEventListener('click', toggleMobileMenu);
                navBackdrop.addEventListener('click', toggleMobileMenu);
                
                const navLinks = mainNav.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 768 && mainNav.classList.contains('active')) {
                            toggleMobileMenu();
                        }
                    });
                });
                
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768 && mainNav.classList.contains('active')) {
                        mainNav.classList.remove('active');
                        mobileMenuBtn.classList.remove('active');
                        navBackdrop.classList.remove('active');
                        body.classList.remove('menu-open');
                        mobileMenuBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
            
            // User dropdown menu
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');
            
            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isExpanded = userDropdown.classList.contains('active');
                    userDropdown.classList.toggle('active');
                    userMenuBtn.setAttribute('aria-expanded', !isExpanded);
                });
                
                document.addEventListener('click', function(e) {
                    if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.remove('active');
                        userMenuBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    </script>
    
    <?php if (isset($page_js)) { ?>
        <?php if (is_array($page_js)) { ?>
            <?php foreach ($page_js as $js) { ?>
                <script src="/assets/js/pages/<?= $js ?>.js"></script>
            <?php } ?>
        <?php } else { ?>
            <script src="/assets/js/pages/<?= $page_js ?>.js"></script>
        <?php } ?>
    <?php } ?>

    <?php if (str_contains($_SERVER['REQUEST_URI'], '/orders') && !str_contains($_SERVER['REQUEST_URI'], '/admin')): ?>
        <script src="/assets/js/pages/orders.js"></script>
    <?php endif; ?>

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
            <?php if (str_contains($_SERVER['REQUEST_URI'], '/orders') && !str_contains($_SERVER['REQUEST_URI'], '/admin')): ?>
                <li>/assets/css/pages/orders.css</li>
            <?php endif; ?>
        </ul>
    </div>
    <?php } ?>
</body>
</html>
