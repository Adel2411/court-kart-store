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
        }
        nav a {
            color: white;
            text-decoration: none;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .main-content {
            padding: 1rem;
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
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2980b9;
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
                <a href="/cart">Cart</a>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
                <a href="/admin">Admin</a>
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
