<?php

define('BASE_PATH', __DIR__);

spl_autoload_register(function ($class) {
    $prefix = 'App\\';

    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $relative_class = substr($class, strlen($prefix));
    $file = BASE_PATH.'/src/'.str_replace('\\', '/', $relative_class).'.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Start session and initialize core
\App\Core\Session::start();
require_once BASE_PATH.'/src/Core/Database.php';
$router = new App\Core\Router;
require_once BASE_PATH.'/routes/web.php';

return $router;
