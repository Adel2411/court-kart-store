<?php
require_once 'router.php';

// Register routes
register_route('/', function () {
    echo '<h2>Home Page</h2>';
    echo '<p>This is the home page of Court Kart Store.</p>';
});

register_route('/shop', function () {
    echo '<h2>Shop</h2>';
    echo '<p>Browse our products.</p>';
});

register_route('/cart', function () {
    echo '<h2>Shopping Cart</h2>';
    echo '<p>Your cart is empty.</p>';
});

register_route('/about', function () {
    echo '<h2>About Us</h2>';
    echo '<p>We sell high-quality tennis equipment.</p>';
});

// HTML structure now wraps the router handling
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Court Kart Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .nav a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Court Kart Store</h1>
        <p>Welcome to our store!</p>
        
        <div class="nav">
            <a href="?page=home">Home</a>
            <a href="?page=shop">Shop</a>
            <a href="?page=cart">Cart</a>
            <a href="?page=about">About</a>
        </div>
        
        <hr>
        
        <?php
        // Handle the current request
        handle_request();
?>
    </div>
</body>
</html>
