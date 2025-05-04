<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - Court Kart Store' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f8f9fa;
        }
        header {
            background-color: #34495e;
            color: white;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
        }
        nav a:hover {
            text-decoration: underline;
        }
        .admin-layout {
            display: flex;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-top: 1rem;
        }
        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 1rem;
            min-height: 80vh;
            border-radius: 5px 0 0 5px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }
        .sidebar li {
            margin-bottom: 1rem;
        }
        .sidebar a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            transition: all 0.2s ease;
        }
        .sidebar a:hover {
            color: #3498db;
            background-color: #ecf0f1;
        }
        .sidebar a.active {
            background-color: #3498db;
            color: white;
        }
        .main-content {
            flex-grow: 1;
            padding: 2rem;
        }
        footer {
            background-color: #34495e;
            color: white;
            padding: 1rem;
            text-align: center;
            margin-top: 2rem;
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
        .btn-danger {
            background-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
        }
        table, th, td {
            border: 1px solid #dee2e6;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background-color: white;
            border-radius: 5px;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #3498db;
        }
        .user-info {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-info span {
            color: #ecf0f1;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>Court Kart Admin</h1>
                <div class="user-info">
                    <span>Logged in as: <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></span>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                </div>
            </div>
            <nav>
                <a href="/">Store Front</a>
                <a href="/admin">Dashboard</a>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <div class="admin-layout">
            <div class="sidebar">
                <h3>Admin Menu</h3>
                <ul>
                    <li><a href="/admin" <?= $_SERVER['REQUEST_URI'] == '/admin' ? 'class="active"' : '' ?>>Dashboard</a></li>
                    <li><a href="/admin/products" <?= $_SERVER['REQUEST_URI'] == '/admin/products' ? 'class="active"' : '' ?>>Products</a></li>
                    <li><a href="/admin/orders" <?= $_SERVER['REQUEST_URI'] == '/admin/orders' ? 'class="active"' : '' ?>>Orders</a></li>
                    <li><a href="/admin/users" <?= $_SERVER['REQUEST_URI'] == '/admin/users' ? 'class="active"' : '' ?>>Users</a></li>
                </ul>
            </div>
            <div class="main-content">
                <?= $content ?>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="container">
            &copy; <?= date('Y') ?> Court Kart Admin Panel
        </div>
    </footer>
</body>
</html>
