<?php

// Define base path
define('BASE_PATH', __DIR__);

// Register autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to path
    $prefix = 'App\\';

    // If the class doesn't use the prefix, exit
    if (strpos($class, $prefix) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, strlen($prefix));

    // Replace namespace with directory separator and append .php
    $file = BASE_PATH.'/src/'.str_replace('\\', '/', $relative_class).'.php';

    // If file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize session
\App\Core\Session::start();

// Load database connection
require_once BASE_PATH.'/src/Core/Database.php';

// Initialize router
$router = new App\Core\Router;

// Define routes
require_once BASE_PATH.'/routes/web.php';

// Return router instance
return $router;
