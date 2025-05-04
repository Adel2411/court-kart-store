<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Court Kart Store' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }
        nav {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 4px;
        }
        nav a:hover {
            background-color: #34495e;
        }
        .nav-right {
            margin-left: auto;
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .main-content {
            padding: 1rem;
            min-height: calc(100vh - 200px);
        }
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
            text-align: center;
            margin-top: 2rem;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .user-badge {
            background-color: #34495e;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .user-badge.admin {
            background-color: #e74c3c;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Court Kart Store</h1>
            <nav>
                <a href="/">Home</a>
                <a href="/shop">Shop</a>
                
                <div class="nav-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <a href="/admin">Admin Dashboard</a>
                        <?php endif; ?>
                        
                        <a href="/cart">Cart</a>
                        <a href="/orders">My Orders</a>
                        <a href="/account">
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                            <span class="user-badge <?= $_SESSION['user_role'] === 'admin' ? 'admin' : '' ?>">
                                <?= ucfirst($_SESSION['user_role']) ?>
                            </span>
                        </a>
                        <a href="/logout" class="btn btn-danger">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="btn">Login</a>
                        <a href="/register">Register</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>
    
    <div class="container main-content">
        <?= $content ?>
    </div>
    
    <footer>
        <div class="container">
            &copy; <?= date('Y') ?> Court Kart Store. All rights reserved.
        </div>
    </footer>
</body>
</html>
