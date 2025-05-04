<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Court Kart Store' ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/layouts.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <?php if (isset($page_css)) { ?>
        <link rel="stylesheet" href="/assets/css/pages/<?= $page_css ?>.css">
    <?php } ?>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">
                        <img src="../public/assets/images/court-kart-logo-dark.svg" alt="Court Kart Store" >
                        <span>Court Kart Store</span>
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
                            
                            <a href="/cart" class="cart-icon">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="cart-count">0</span>
                            </a>
                            <a href="/orders">My Orders</a>
                            <div class="dropdown">
                                <button class="dropdown-btn">
                                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                                    <span class="user-badge <?= $_SESSION['user_role'] === 'admin' ? 'admin' : '' ?>">
                                        <?= ucfirst($_SESSION['user_role']) ?>
                                    </span>
                                    <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="dropdown-content">
                                    <a href="/account">My Account</a>
                                    <a href="/orders">My Orders</a>
                                    <a href="/logout" class="btn-danger">Logout</a>
                                </div>
                            </div>
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
                    <h3>Court Kart Store</h3>
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
                &copy; <?= date('Y') ?> Court Kart Store. All rights reserved.
            </div>
        </div>
    </footer>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="/assets/js/main.js"></script>
    <?php if (isset($page_js)) { ?>
        <script src="/assets/js/pages/<?= $page_js ?>.js"></script>
    <?php } ?>
</body>
</html>
