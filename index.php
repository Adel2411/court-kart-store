<?php
$db_status = 'Database connection: ';
$db = null;

if (! file_exists('includes/db.php')) {
    $db_status = 'Error: Database connection file not found!';
} else {
    require_once 'includes/db.php';

    try {
        DB::fromConfig();
        $db = DB::getInstance();
        $db_status .= 'Connected successfully';
    } catch (Exception $e) {
        $db_status .= 'Failed to connect: '.htmlspecialchars($e->getMessage());
    }
}

if ($db instanceof DB) {
    try {
        $query = 'SELECT * FROM users';
        $users = $db->fetchRows($query);

        if (! empty($users)) {
            echo '<h2>Users</h2>';
            echo '<table border="1" cellpadding="5" cellspacing="0">';
            echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>';
            foreach ($users as $row) {
                echo '<tr>';
                echo '<td>'.htmlspecialchars($row['id']).'</td>';
                echo '<td>'.htmlspecialchars($row['name']).'</td>';
                echo '<td>'.htmlspecialchars($row['email']).'</td>';
                echo '<td>'.htmlspecialchars($row['role']).'</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No users found.</p>';
        }
    } catch (PDOException $e) {
        echo '<p>Query failed: '.htmlspecialchars($e->getMessage()).'</p>';
    }
}

require_once 'router.php';

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
        .debug-info {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Court Kart Store</h1>
        <p>Welcome to our store!</p>
        
        <div class="debug-info">
            <?php echo $db_status; ?>
        </div>
        
        <div class="nav">
            <a href="/">Home</a>
            <a href="/shop">Shop</a>
            <a href="/cart">Cart</a>
            <a href="/about">About</a>
        </div>
        
        <hr>
        
        <?php
        // Handle the current request
        handle_request();
?>
    </div>
</body>
</html>
